<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with('product', 'user');

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('created_at', $request->month)
                  ->whereYear('created_at', $request->year);
        }

        $movements = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total' => StockMovement::count(),
            'units' => StockMovement::sum('quantity'),
            'products' => StockMovement::distinct('product_id')->count('product_id'),
        ];

        $currentYear = date('Y');
        $years = range($currentYear, $currentYear - 5);

        return view('stock-movements.index', compact('movements', 'stats', 'years'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('stock-movements.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'motivo' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        StockMovement::create([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'type' => 'entrada',
            'motivo' => $validated['motivo'],
            'user_id' => auth()->id(),
        ]);

        $product->increment('stock', $validated['quantity']);

        return redirect()->route('stock-movements.index')
            ->with('success', "Stock agregado: {$product->name} +{$validated['quantity']}");
    }

    public function edit(StockMovement $stockMovement)
    {
        $products = Product::orderBy('name')->get();
        return view('stock-movements.edit', compact('stockMovement', 'products'));
    }

    public function update(Request $request, StockMovement $stockMovement)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'motivo' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $oldQty = $stockMovement->quantity;
        $newQty = $validated['quantity'];
        $diff = $newQty - $oldQty;

        $stockMovement->update([
            'product_id' => $validated['product_id'],
            'quantity' => $newQty,
            'motivo' => $validated['motivo'],
        ]);

        if ($diff !== 0) {
            $product->increment('stock', $diff);
        }

        return redirect()->route('stock-movements.index')
            ->with('success', "Movimiento actualizado: {$product->name} ({$oldQty} → {$newQty})");
    }

    public function destroy(StockMovement $stockMovement)
    {
        $product = $stockMovement->product;
        $product->decrement('stock', $stockMovement->quantity);

        $stockMovement->delete();

        return redirect()->route('stock-movements.index')
            ->with('success', "Movimiento eliminado y stock restado: {$product->name} -{$stockMovement->quantity}");
    }
}
