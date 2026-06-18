<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::orderBy('name');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('document', 'like', "%{$q}%")
                    ->orWhere('company', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($request->filled('company')) {
            $query->where('company', $request->company);
        }

        $companies = Customer::whereNotNull('company')->where('company', '!=', '')
            ->distinct()->orderBy('company')->pluck('company');

        $customers = $query->get();
        return view('customers.index', compact('customers', 'companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'document' => 'nullable|string|max:50|unique:customers,document',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $customer = Customer::create($validated);

        if ($request->ajax()) {
            return response()->json($customer);
        }

        return redirect()->route('customers.index')->with('success', 'Cliente registrado exitosamente.');
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'document' => 'nullable|string|max:50|unique:customers,document,' . $customer->id,
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Customer $customer)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $salesCount = $customer->sales()->count();
        if ($salesCount > 0) {
            return redirect()->route('customers.index')->with('error', "No se puede eliminar \"{$customer->name}\" porque tiene {$salesCount} venta(s) asociada(s). Primero eliminá el historial de ventas de este cliente.");
        }

        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Cliente eliminado exitosamente.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $customers = Customer::where('name', 'like', "%{$query}%")
            ->orWhere('document', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('company', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json($customers);
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Clientes');

        $headers = ['Nombre*', 'Cédula/RUC', 'Empresa', 'Teléfono', 'Email'];
        $sheet->fromArray([$headers], null, 'A1');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle('A1:E1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E7FF');

        $writer = new Xlsx($spreadsheet);
        $fileName = 'plantilla_clientes.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $rows = $spreadsheet->getActiveSheet()->toArray();

        if (empty($rows) || count($rows) < 2) {
            return back()->with('error', 'El archivo está vacío o no tiene datos.');
        }

        array_shift($rows);

        $imported = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;

            $name = trim($row[0] ?? '');
            $document = trim($row[1] ?? '');
            $company = trim($row[2] ?? '');
            $phone = trim($row[3] ?? '');
            $email = trim($row[4] ?? '');

            if (empty($name) && empty($document) && empty($company) && empty($phone) && empty($email)) {
                continue;
            }

            if (empty($name)) {
                $errors[] = "Fila {$rowNum}: El nombre es obligatorio.";
                continue;
            }

            if (!empty($document) && Customer::where('document', $document)->exists()) {
                $errors[] = "Fila {$rowNum} ({$name}): La cédula/RUC '{$document}' ya existe. Se ignoró.";
                continue;
            }

            if (!empty($email) && Customer::where('email', $email)->exists()) {
                $errors[] = "Fila {$rowNum} ({$name}): El email '{$email}' ya existe. Se ignoró.";
                continue;
            }

            try {
                Customer::create([
                    'name' => $name,
                    'document' => $document ?: null,
                    'company' => $company ?: null,
                    'phone' => $phone ?: null,
                    'email' => $email ?: null,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Fila {$rowNum} ({$name}): Error al guardar. " . $e->getMessage();
            }
        }

        $message = "{$imported} cliente(s) importado(s) exitosamente.";
        if (!empty($errors)) {
            $message .= ' ' . implode(' | ', $errors);
        }

        return redirect()->route('customers.index')->with(
            empty($errors) ? 'success' : 'error',
            $message
        );
    }

    public function destroyMultiple(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron clientes.');
        }

        $count = Customer::whereIn('id', $ids)->count();
        Customer::whereIn('id', $ids)->delete();

        return back()->with('success', "{$count} cliente(s) eliminado(s) permanentemente.");
    }
}
