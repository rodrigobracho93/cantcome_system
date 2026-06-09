<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Product::with(['category', 'creator', 'approver']);

        if (!$user->isAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('price_status', 'approved')
                  ->orWhere('created_by', $user->id);
            });
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->get('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($statusFilter = $request->get('price_status')) {
            $query->where('price_status', $statusFilter);
        }

        if ($stockFilter = $request->get('stock')) {
            if ($stockFilter === 'low') $query->where('stock', '<=', 5);
            elseif ($stockFilter === 'out') $query->where('stock', 0);
        }

        $query->where('is_active', true);

        if ($user->isAdmin()) {
            $query->orderByRaw("FIELD(price_status, 'pending', 'approved')")->orderBy('name');
        } else {
            $query->orderBy('price_status')->orderBy('name');
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        $baseQuery = Product::where('is_active', true);
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'lowStock' => (clone $baseQuery)->where('stock', '<=', 5)->count(),
            'outOfStock' => (clone $baseQuery)->where('stock', 0)->count(),
            'categories' => Category::where('is_active', true)->count(),
            'pendingPrices' => (clone $baseQuery)->where('price_status', 'pending')->count(),
        ];

        return view('products.index', compact('products', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'barcode' => 'nullable|string|unique:products,barcode',
        ]);

        $user = auth()->user();
        $isAdmin = $user->isAdmin();

        $validated['created_by'] = $user->id;
        $validated['price_status'] = $isAdmin ? 'approved' : 'pending';

        if ($isAdmin) {
            $validated['price_approved_by'] = $user->id;
            $validated['price_approved_at'] = now();
        }

        Product::create($validated);

        $message = $isAdmin
            ? 'Producto creado exitosamente.'
            : 'Producto creado. Queda pendiente de aprobación del precio por un administrador.';

        return redirect()->route('products.index')->with('success', $message);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'creator', 'approver']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorizeAdmin();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeAdmin();
        $product->update(['is_active' => false]);

        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente.');
    }

    public function approvePrice(Product $product)
    {
        $this->authorizeAdmin();

        if ($product->price_status !== 'pending') {
            return redirect()->route('products.index')->with('error', 'El precio de este producto ya fue aprobado.');
        }

        $product->update([
            'price_status' => 'approved',
            'price_approved_by' => auth()->id(),
            'price_approved_at' => now(),
        ]);

        return redirect()->route('products.index')->with('success', "Precio de '{$product->name}' aprobado exitosamente.");
    }

    private function authorizeAdmin(): void
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Solo el administrador puede realizar esta acción.');
        }
    }
}
