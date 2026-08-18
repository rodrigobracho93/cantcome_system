<?php

namespace App\Http\Controllers;

use App\Models\Almuerzo;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AlmuerzoController extends Controller
{
    public function index(Request $request)
    {
        $fecha = $request->filled('fecha') ? \Carbon\Carbon::parse($request->fecha) : today();
        $customers = Customer::orderBy('name')->get();

        $almuerzos = Almuerzo::with('customer')
            ->whereDate('fecha', $fecha)
            ->orderBy('entregado')
            ->orderBy(Customer::select('name')->whereColumn('customers.id', 'almuerzos.customer_id'))
            ->get();

        $entregadosHoy = $almuerzos->where('entregado', true)->count();
        $pendientesHoy = $almuerzos->where('entregado', false)->count();
        $totalHoy = $almuerzos->count();

        return view('almuerzos.index', compact(
            'almuerzos', 'customers', 'fecha',
            'entregadosHoy', 'pendientesHoy', 'totalHoy'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'fecha' => 'required|date',
        ]);

        $exists = Almuerzo::where('customer_id', $validated['customer_id'])
            ->whereDate('fecha', $validated['fecha'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Esta persona ya está registrada para almorzar en esta fecha.');
        }

        Almuerzo::create([
            'customer_id' => $validated['customer_id'],
            'fecha' => $validated['fecha'],
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Agregado a la lista de almuerzos.');
    }

    public function toggle(Request $request, Almuerzo $almuerzo)
    {
        $data = [
            'entregado' => !$almuerzo->entregado,
            'entregado_at' => $almuerzo->entregado ? null : now(),
        ];

        if (!$almuerzo->entregado) {
            $data['observacion'] = $request->input('observacion');
        } else {
            $data['observacion'] = null;
        }

        $almuerzo->update($data);

        return back()->with('success', $almuerzo->entregado ? 'Almuerzo marcado como entregado.' : 'Almuerzo marcado como pendiente.');
    }

    public function destroy(Almuerzo $almuerzo)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permiso para eliminar registros de almuerzo.');
        }

        $almuerzo->delete();
        return back()->with('success', 'Registro de almuerzo eliminado.');
    }

    public function reporteMensual(Request $request)
    {
        $mes = (int) $request->input('mes', now()->month);
        $anio = (int) $request->input('anio', now()->year);

        $entregados = Almuerzo::with('customer')
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->where('entregado', true)
            ->orderBy('fecha')
            ->get();

        $totalPlatos = $entregados->count();

        $porDia = $entregados->groupBy(fn($a) => $a->fecha->format('Y-m-d'))
            ->map(fn($group, $dia) => [
                'fecha' => \Carbon\Carbon::parse($dia),
                'cantidad' => $group->count(),
            ])->sortBy('fecha');

        $porCliente = $entregados->groupBy(fn($a) => $a->customer->name)
            ->map(fn($group, $nombre) => [
                'customer' => $group->first()->customer,
                'cantidad' => $group->count(),
            ])->sortByDesc('cantidad');

        return view('almuerzos.reporte', compact(
            'entregados', 'totalPlatos', 'porDia', 'porCliente', 'mes', 'anio'
        ));
    }

    private function themeColor(?string $theme): string
    {
        return match ($theme) {
            'blue' => '#2563eb',
            'green' => '#16a34a',
            'red' => '#dc2626',
            'purple' => '#9333ea',
            'orange' => '#ea580c',
            'teal' => '#0d9488',
            'pink' => '#db2777',
            'neutro' => '#64748b',
            'celeste' => '#0284c7',
            default => '#4f46e5',
        };
    }

    public function dailyPdf(Request $request)
    {
        $fecha = $request->filled('fecha') ? \Carbon\Carbon::parse($request->fecha) : today();
        $primaryColor = $this->themeColor($request->input('theme'));

        $almuerzos = Almuerzo::with('customer')
            ->whereDate('fecha', $fecha)
            ->orderBy('entregado')
            ->orderBy(Customer::select('name')->whereColumn('customers.id', 'almuerzos.customer_id'))
            ->get();

        $entregados = $almuerzos->where('entregado', true)->count();
        $pendientes = $almuerzos->where('entregado', false)->count();
        $total = $almuerzos->count();

        $pdf = Pdf::loadView('almuerzos.daily-pdf', compact(
            'almuerzos', 'fecha', 'entregados', 'pendientes', 'total', 'primaryColor'
        ));

        return $pdf->stream("almuerzos-{$fecha->format('Y-m-d')}.pdf");
    }

    public function reportePdf(Request $request)
    {
        $mes = (int) $request->input('mes', now()->month);
        $anio = (int) $request->input('anio', now()->year);
        $primaryColor = $this->themeColor($request->input('theme'));

        $entregados = Almuerzo::with('customer')
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->where('entregado', true)
            ->orderBy('fecha')
            ->get();

        $totalPlatos = $entregados->count();

        $porDia = $entregados->groupBy(fn($a) => $a->fecha->format('Y-m-d'))
            ->map(fn($group, $dia) => [
                'fecha' => \Carbon\Carbon::parse($dia),
                'cantidad' => $group->count(),
            ])->sortBy('fecha');

        $porCliente = $entregados->groupBy(fn($a) => $a->customer->name)
            ->map(fn($group, $nombre) => [
                'customer' => $group->first()->customer,
                'cantidad' => $group->count(),
            ])->sortByDesc('cantidad');

        $pdf = Pdf::loadView('almuerzos.reporte-pdf', compact(
            'totalPlatos', 'porDia', 'porCliente', 'mes', 'anio', 'primaryColor'
        ));

        return $pdf->stream("reporte-almuerzos-{$mes}-{$anio}.pdf");
    }

    public function createCliente()
    {
        return view('almuerzos.create-cliente');
    }

    public function storeCliente(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer = Customer::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
        ]);

        return redirect()->route('almuerzos.index', ['fecha' => $request->input('fecha', today()->format('Y-m-d'))])
            ->with('success', "Cliente {$customer->name} creado. Ahora agregalo a la lista.");
    }
}
