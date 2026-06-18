<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerSalesController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::orderBy('name')->get();

        $query = Sale::with(['customer', 'user', 'items.product'])
            ->where('status', '!=', 'anulado');

        if ($request->filled('customer_id')) {
            if ($request->customer_id === '0') {
                $query->whereNull('customer_id');
            } else {
                $query->where('customer_id', $request->customer_id);
            }
        }

        $paymentType = $request->input('payment_type', 'credito');
        if ($paymentType) {
            $query->where('payment_type', $paymentType);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $showAll = $request->boolean('show_all');
        if (!$showAll && $paymentType === 'credito') {
            $query->whereNull('paid_at');
        }

        $query->orderByDesc('created_at');

        if ($request->filled('customer_id')) {
            $sales = $query->get();
            $customer = $request->customer_id === '0' ? null : Customer::find($request->customer_id);
            $totalGeneral = $sales->sum('total');
            $totalPendiente = $sales->where('payment_type', 'credito')->whereNull('paid_at')->sum('total');
            $totalCobrado = $sales->filter(fn($s) => $s->payment_type === 'contado' || $s->paid_at !== null)->sum('total');
            $porCliente = null;
        } else {
            $sales = $query->get();
            $customer = null;
            $porCliente = $sales->groupBy(fn($s) => $s->customer_id ? $s->customer->full_name : 'Sin cliente')
                ->map(fn($group) => [
                    'customer' => $group->first()->customer,
                    'total' => $group->sum('total'),
                    'count' => $group->count(),
                ])->sortByDesc('total');
            $totalGeneral = $sales->sum('total');
            $totalPendiente = $sales->whereNull('paid_at')->sum('total');
            $totalCobrado = $sales->whereNotNull('paid_at')->sum('total');
        }

        return view('customer-sales.index', compact(
            'customers', 'sales', 'customer', 'porCliente',
            'totalGeneral', 'totalPendiente', 'totalCobrado',
            'paymentType', 'showAll'
        ));
    }

    public function markAsPaid(Sale $sale)
    {
        if ($sale->payment_type !== 'credito' || $sale->status === 'anulado') {
            return back()->withErrors('Esta venta no puede marcarse como cobrada.');
        }

        $sale->update(['paid_at' => now()]);

        return back()->with('success', "Venta #{$sale->id} marcada como cobrada.");
    }

    public function markAsUnpaid(Sale $sale)
    {
        if ($sale->payment_type !== 'credito' || $sale->status === 'anulado') {
            return back()->withErrors('Esta venta no puede modificarse.');
        }

        $sale->update(['paid_at' => null]);

        return back()->with('success', "Venta #{$sale->id} marcada como pendiente.");
    }

    public function exportPdf(Request $request)
    {
        $customers = Customer::orderBy('name')->get();

        $query = Sale::with(['customer', 'user', 'items.product'])
            ->where('status', '!=', 'anulado');

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $paymentType = $request->input('payment_type', 'credito');
        if ($paymentType) {
            $query->where('payment_type', $paymentType);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $showAll = $request->boolean('show_all');
        if (!$showAll && $paymentType === 'credito') {
            $query->whereNull('paid_at');
        }

        $query->orderByDesc('created_at');

        $customer = null;
        if ($request->filled('customer_id')) {
            $customer = Customer::find($request->customer_id);
            $sales = $query->get();
        } else {
            $sales = $query->get();
        }

        $totalGeneral = $sales->sum('total');
        $totalPendiente = $sales->where('payment_type', 'credito')->whereNull('paid_at')->sum('total');
        $totalCobrado = $sales->filter(fn($s) => $s->payment_type === 'contado' || $s->paid_at !== null)->sum('total');

        $pdf = Pdf::loadView('customer-sales.pdf', compact(
            'sales', 'customer', 'totalGeneral', 'totalPendiente', 'totalCobrado', 'paymentType'
        ));

        return $pdf->stream('cuentas-por-cobrar.pdf');
    }
}
