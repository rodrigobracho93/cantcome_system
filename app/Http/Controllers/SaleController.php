<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Caja;
use App\Models\CajaMovimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $sales = Sale::with(['user', 'customer', 'items.product', 'cajaMovimientos'])
            ->when(!$user->isAdmin(), fn($q) => $q->where('user_id', $user->id))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::with('category')->where('is_active', true)->approved()->where('stock', '>', 0)->orderBy('name')->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_document' => 'nullable|string|max:50',
            'customer_company' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'payment_type' => 'required|in:contado,credito',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            if (!empty($validated['customer_id'])) {
                $customerId = $validated['customer_id'];
            } elseif (!empty($validated['customer_name'])) {
                $customer = Customer::create([
                    'name' => $validated['customer_name'],
                    'document' => $validated['customer_document'] ?? null,
                    'company' => $validated['customer_company'] ?? null,
                    'phone' => $validated['customer_phone'] ?? null,
                ]);
                $customerId = $customer->id;
            } else {
                $customerId = null;
            }

            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    return back()->withErrors("Stock insuficiente para {$product->name}");
                }

                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $itemSubtotal,
                ];

                $product->decrement('stock', $item['quantity']);
            }

            $tax = $subtotal * 0.10;
            $total = $subtotal + $tax;

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'customer_id' => $customerId,
                'payment_type' => $validated['payment_type'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'status' => 'completado',
                'notes' => $validated['notes'] ?? null,
                'synced' => false,
            ]);

            foreach ($itemsData as $item) {
                $sale->items()->create($item);
            }

            $cajaAbierta = Caja::where('estado', 'abierta')->first();
            if ($cajaAbierta) {
                CajaMovimiento::create([
                    'caja_id' => $cajaAbierta->id,
                    'tipo' => 'ingreso',
                    'concepto' => "Venta #{$sale->id} - " . ($sale->customer->name ?? 'Sin cliente'),
                    'monto' => $sale->total,
                    'referencia_type' => Sale::class,
                    'referencia_id' => $sale->id,
                ]);

                $cajaAbierta->increment('total_ingresos', $sale->total);
                $cajaAbierta->increment('monto_final_esperado', $sale->total);
            }

            return redirect()->route('sales.show', $sale)->with('success', 'Venta registrada exitosamente.');
        });
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'customer', 'items.product', 'cajaMovimientos.caja']);
        return view('sales.show', compact('sale'));
    }

    public function update(Request $request, Sale $sale)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_type' => 'required|in:contado,credito',
            'customer_id' => 'nullable|exists:customers,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $sale->update($validated);

        return redirect()->route('sales.show', $sale)->with('success', 'Venta actualizada exitosamente.');
    }

    public function destroy(Sale $sale)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
            $sale->update(['status' => 'anulado']);

            $movimiento = CajaMovimiento::where('referencia_type', Sale::class)
                ->where('referencia_id', $sale->id)
                ->where('tipo', 'ingreso')
                ->first();

            if ($movimiento) {
                $caja = $movimiento->caja;
                if ($caja && $caja->estado === 'abierta') {
                    CajaMovimiento::create([
                        'caja_id' => $caja->id,
                        'tipo' => 'egreso',
                        'concepto' => "Anulación de venta #{$sale->id}",
                        'monto' => $sale->total,
                        'referencia_type' => Sale::class,
                        'referencia_id' => $sale->id,
                    ]);

                    $caja->decrement('total_ingresos', $sale->total);
                    $caja->decrement('monto_final_esperado', $sale->total);
                }
            }
        });

        return redirect()->route('sales.index')->with('success', 'Venta anulada exitosamente.');
    }

    public function forceDestroy(Sale $sale)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            CajaMovimiento::where('referencia_type', Sale::class)
                ->where('referencia_id', $sale->id)
                ->delete();

            $sale->items()->delete();
            $sale->delete();
        });

        return redirect()->route('sales.index')->with('success', 'Venta eliminada permanentemente.');
    }

    public function receiptPdf(Sale $sale)
    {
        $sale->load(['user', 'customer', 'items.product']);
        $pdf = Pdf::loadView('sales.recibo', compact('sale'));
        $pdf->setPaper([0, 0, 300, 600], 'portrait');
        return $pdf->stream("recibo-{$sale->id}.pdf");
    }

    public function receiptPdfUrl(Sale $sale)
    {
        $sale->load(['user', 'customer', 'items.product']);
        $pdf = Pdf::loadView('sales.recibo', compact('sale'));
        $pdf->setPaper([0, 0, 300, 600], 'portrait');

        $dir = public_path('pdf');
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $pdf->save("{$dir}/recibo-{$sale->id}.pdf");

        return response()->json([
            'url' => url("pdf/recibo-{$sale->id}.pdf"),
        ]);
    }
}
