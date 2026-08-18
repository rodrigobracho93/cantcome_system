<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\CajaMovimiento;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    public function index()
    {
        $cajas = Caja::with('user')->orderByDesc('fecha_apertura')->paginate(15);
        $cajaAbierta = Caja::where('estado', 'abierta')->withCount(['movimientos as ventas_count' => function ($q) {
            $q->where('tipo', 'ingreso')->where('referencia_type', 'App\Models\Sale');
        }])->first();
        return view('caja.index', compact('cajas', 'cajaAbierta'));
    }

    public function create()
    {
        if (Caja::where('estado', 'abierta')->exists()) {
            return redirect()->route('caja.index')->withErrors('Ya hay una caja abierta. Ciérrala antes de abrir una nueva.');
        }
        return view('caja.create');
    }

    public function store(Request $request)
    {
        if (Caja::where('estado', 'abierta')->exists()) {
            return redirect()->route('caja.index')->withErrors('Ya hay una caja abierta.');
        }

        $validated = $request->validate([
            'monto_inicial' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $caja = Caja::create([
            'user_id' => auth()->id(),
            'fecha_apertura' => now(),
            'monto_inicial' => $validated['monto_inicial'],
            'monto_final_esperado' => $validated['monto_inicial'],
            'total_ingresos' => 0,
            'total_egresos' => 0,
            'estado' => 'abierta',
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        return redirect()->route('caja.show', $caja)->with('success', 'Caja abierta exitosamente.');
    }

    public function show(Caja $caja)
    {
        $caja->load('user', 'movimientos.referencia');
        return view('caja.show', compact('caja'));
    }

    public function close(Request $request, Caja $caja)
    {
        if ($caja->estado === 'cerrada') {
            return redirect()->route('caja.index')->withErrors('Esta caja ya está cerrada.');
        }

        $validated = $request->validate([
            'monto_final_real' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $diferencia = $validated['monto_final_real'] - $caja->monto_final_esperado;

        $caja->update([
            'fecha_cierre' => now(),
            'monto_final_real' => $validated['monto_final_real'],
            'diferencia' => $diferencia,
            'estado' => 'cerrada',
            'observaciones' => $caja->observaciones ? $caja->observaciones . "\n" . ($validated['observaciones'] ?? '') : ($validated['observaciones'] ?? null),
        ]);

        return redirect()->route('caja.show', $caja)->with('success', 'Caja cerrada exitosamente.');
    }

    public function destroy(Caja $caja)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permiso para eliminar registros de caja.');
        }

        $caja->movimientos()->delete();
        $caja->delete();

        return redirect()->route('caja.index')->with('success', 'Registro de caja eliminado.');
    }

    public function libroDiario(Request $request)
    {
        $fecha = $request->get('fecha', today()->format('Y-m-d'));

        $ventas = Sale::with(['user', 'customer'])
            ->whereDate('created_at', $fecha)
            ->orderBy('created_at')
            ->get();

        $cajas = Caja::with('movimientos')
            ->whereDate('fecha_apertura', $fecha)
            ->get();

        $movimientos = CajaMovimiento::with('caja')
            ->whereHas('caja', function ($q) use ($fecha) {
                $q->whereDate('fecha_apertura', $fecha);
            })
            ->orderBy('created_at')
            ->get()
            ->load('referencia');

        $totalVentas = $ventas->where('status', '!=', 'anulado')->sum('total');
        $totalMovimientosIngreso = $movimientos->where('tipo', 'ingreso')->sum('monto');
        $totalMovimientosEgreso = $movimientos->where('tipo', 'egreso')->sum('monto');

        return view('caja.libro-diario', compact(
            'fecha', 'ventas', 'cajas', 'movimientos',
            'totalVentas', 'totalMovimientosIngreso', 'totalMovimientosEgreso'
        ));
    }

    public function storeMovimiento(Request $request, Caja $caja)
    {
        if ($caja->estado === 'cerrada') {
            return redirect()->route('caja.show', $caja)->withErrors('No se pueden agregar movimientos a una caja cerrada.');
        }

        $validated = $request->validate([
            'tipo' => 'required|in:ingreso,egreso',
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($caja, $validated) {
            CajaMovimiento::create([
                'caja_id' => $caja->id,
                'tipo' => $validated['tipo'],
                'concepto' => $validated['concepto'],
                'monto' => $validated['monto'],
            ]);

            $monto = $validated['monto'];
            if ($validated['tipo'] === 'ingreso') {
                $caja->increment('total_ingresos', $monto);
                $caja->increment('monto_final_esperado', $monto);
            } else {
                $caja->increment('total_egresos', $monto);
                $caja->decrement('monto_final_esperado', $monto);
            }
        });

        return redirect()->route('caja.show', $caja)->with('success', 'Movimiento registrado exitosamente.');
    }

    public function destroyMovimiento(Caja $caja, CajaMovimiento $movimiento)
    {
        if ($caja->estado === 'cerrada') {
            return redirect()->route('caja.show', $caja)->withErrors('No se pueden eliminar movimientos de una caja cerrada.');
        }

        DB::transaction(function () use ($caja, $movimiento) {
            $monto = $movimiento->monto;
            if ($movimiento->tipo === 'ingreso') {
                $caja->decrement('total_ingresos', $monto);
                $caja->decrement('monto_final_esperado', $monto);
            } else {
                $caja->decrement('total_egresos', $monto);
                $caja->increment('monto_final_esperado', $monto);
            }
            $movimiento->delete();
        });

        return redirect()->route('caja.show', $caja)->with('success', 'Movimiento eliminado.');
    }
}