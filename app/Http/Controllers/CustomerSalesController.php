<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerSalesController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::orderBy('name')->get();

        $query = Sale::with(['customer', 'user', 'items.product'])
            ->where('status', '!=', 'anulado');

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $query->orderByDesc('created_at');

        if ($request->filled('customer_id')) {
            $sales = $query->get();
            $customer = Customer::find($request->customer_id);
            $totalGeneral = $sales->sum('total');
            $totalPendiente = $sales->where('payment_type', 'credito')->whereNull('paid_at')->sum('total');
            $totalCobrado = $sales->filter(fn($s) => $s->payment_type === 'contado' || $s->paid_at !== null)->sum('total');
        } else {
            $sales = $query->paginate(30);
            $customer = null;
            $totalGeneral = $totalPendiente = $totalCobrado = null;
        }

        return view('customer-sales.index', compact(
            'customers', 'sales', 'customer',
            'totalGeneral', 'totalPendiente', 'totalCobrado'
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
}
