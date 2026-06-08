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
        $query = Product::with('category')->where('is_active', true);

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

        if ($stockFilter = $request->get('stock')) {
            if ($stockFilter === 'low') $query->where('stock', '<=', 5);
            elseif ($stockFilter === 'out') $query->where('stock', 0);
        }

        $products = $query->orderBy('name')->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total' => Product::where('is_active', true)->count(),
            'lowStock' => Product::where('is_active', true)->where('stock', '<=', 5)->count(),
            'outOfStock' => Product::where('is_active', true)->where('stock', 0)->count(),
            'categories' => Category::where('is_active', true)->count(),
        ];

        return view('products.index', compact('products', 'categories', 'stats'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'barcode' => 'nullable|string|unique:products,barcode',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }

    public function show(Product $product)
    {
        $product->load('category');
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

    private function authorizeAdmin(): void
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Solo el administrador puede realizar esta acción.');
        }
    }
}
