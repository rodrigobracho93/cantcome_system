<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $sales = Sale::with(['user', 'customer', 'items.product'])
            ->when(!$user->isAdmin(), fn($q) => $q->where('user_id', $user->id))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::with('category')->where('is_active', true)->where('stock', '>', 0)->orderBy('name')->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_first_name' => 'required_without:customer_id|string|max:255',
            'customer_last_name' => 'required_without:customer_id|string|max:255',
            'customer_document' => 'nullable|string|max:50',
            'customer_company' => 'nullable|string|max:255',
            'payment_type' => 'required|in:contado,credito',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            if (!empty($validated['customer_id'])) {
                $customerId = $validated['customer_id'];
            } else {
                $customer = Customer::create([
                    'first_name' => $validated['customer_first_name'],
                    'last_name' => $validated['customer_last_name'],
                    'document' => $validated['customer_document'] ?? null,
                    'company' => $validated['customer_company'] ?? null,
                ]);
                $customerId = $customer->id;
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

            return redirect()->route('sales.show', $sale)->with('success', 'Venta registrada exitosamente.');
        });
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'customer', 'items.product']);
        return view('sales.show', compact('sale'));
    }

    public function destroy(Sale $sale)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $sale->update(['status' => 'anulado']);

        return redirect()->route('sales.index')->with('success', 'Venta anulada exitosamente.');
    }
}
