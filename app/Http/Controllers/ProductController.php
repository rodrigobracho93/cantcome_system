<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Productos');

        $headers = ['Nombre*', 'Categoría', 'Precio*', 'Stock*', 'Código de barras', 'Descripción'];
        $sheet->fromArray([$headers], null, 'A1');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle('A1:F1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E7FF');

        $writer = new Xlsx($spreadsheet);
        $fileName = 'plantilla_productos.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function importExcel(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $rows = $spreadsheet->getActiveSheet()->toArray();

        if (empty($rows) || count($rows) < 2) {
            return back()->with('error', 'El archivo está vacío o no tiene datos.');
        }

        // Remove header row
        array_shift($rows);

        $categories = Category::where('is_active', true)->pluck('id', 'name')->map(fn($v) => strtolower($v));
        $user = auth()->user();
        $isAdmin = $user->isAdmin();
        $imported = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;

            $name = trim($row[0] ?? '');
            $categoryName = trim($row[1] ?? '');
            $price = trim($row[2] ?? '');
            $stock = trim($row[3] ?? '');
            $barcode = trim($row[4] ?? '');
            $description = trim($row[5] ?? '');

            if (empty($name) && empty($price) && empty($stock)) {
                continue;
            }

            if (empty($name)) {
                $errors[] = "Fila {$rowNum}: El nombre es obligatorio.";
                continue;
            }

            if (!is_numeric($price) || $price < 0) {
                $errors[] = "Fila {$rowNum} ({$name}): El precio debe ser un número válido mayor o igual a 0.";
                continue;
            }

            if (!is_numeric($stock) || $stock < 0 || (int)$stock != $stock) {
                $errors[] = "Fila {$rowNum} ({$name}): El stock debe ser un número entero válido mayor o igual a 0.";
                continue;
            }

            $categoryId = null;
            if (!empty($categoryName)) {
                $categorySlug = strtolower($categoryName);
                $found = $categories->first(fn($v, $k) => strtolower($k) === $categorySlug);
                if ($found !== null) {
                    $categoryId = $found;
                } else {
                    $errors[] = "Fila {$rowNum} ({$name}): Categoría '{$categoryName}' no encontrada. Se asignará 'Sin categoría'.";
                }
            }

            if (!empty($barcode) && Product::where('barcode', $barcode)->exists()) {
                $errors[] = "Fila {$rowNum} ({$name}): El código de barras '{$barcode}' ya existe. Se ignoró.";
                continue;
            }

            try {
                Product::create([
                    'name' => $name,
                    'category_id' => $categoryId,
                    'price' => (float)$price,
                    'stock' => (int)$stock,
                    'barcode' => $barcode ?: null,
                    'description' => $description ?: null,
                    'created_by' => $user->id,
                    'price_status' => $isAdmin ? 'approved' : 'pending',
                    'price_approved_by' => $isAdmin ? $user->id : null,
                    'price_approved_at' => $isAdmin ? now() : null,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Fila {$rowNum} ({$name}): Error al crear: {$e->getMessage()}";
            }
        }

        $message = "Se importaron {$imported} producto(s) exitosamente.";
        if (!empty($errors)) {
            $message .= ' ' . implode(' | ', $errors);
        }

        return redirect()->route('products.index')->with(
            empty($errors) ? 'success' : ($imported > 0 ? 'success' : 'error'),
            $message
        );
    }

    public function search(Request $request)
    {
        $q = $request->get('q');
        $products = Product::where('name', 'like', "%{$q}%")
            ->where('is_active', true)
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'stock', 'barcode', 'price']);

        return response()->json($products);
    }

    private function authorizeAdmin(): void
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Solo el administrador puede realizar esta acción.');
        }
    }
}
