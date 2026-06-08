<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $salesToday = Sale::whereDate('created_at', today())->where('status', 'completado');
        $salesYesterday = Sale::whereDate('created_at', today()->subDay())->where('status', 'completado');

        $todayCount = (clone $salesToday)->count();
        $yesterdayCount = (clone $salesYesterday)->count();
        $salesTrend = $yesterdayCount > 0 ? round((($todayCount - $yesterdayCount) / $yesterdayCount) * 100) : ($todayCount > 0 ? 100 : 0);

        $todayRevenue = (clone $salesToday)->sum('total');
        $yesterdayRevenue = (clone $salesYesterday)->sum('total');
        $revenueTrend = $yesterdayRevenue > 0 ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100) : ($todayRevenue > 0 ? 100 : 0);

        $data = [
            'salesToday' => $todayCount,
            'salesTrend' => $salesTrend,
            'revenueToday' => $todayRevenue,
            'revenueTrend' => $revenueTrend,
            'totalProducts' => Product::where('is_active', true)->count(),
            'pendingSales' => Sale::where('status', 'pendiente')->count(),
        ];

        $data['lowStockProducts'] = Product::where('is_active', true)
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->take(5)
            ->get();

        if ($user->isAdmin()) {
            $data['weeklySales'] = Sale::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total')
            )
                ->where('created_at', '>=', now()->subDays(7))
                ->where('status', 'completado')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $data['topProducts'] = DB::table('sale_items')
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_qty'))
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_qty')
                ->limit(5)
                ->get();

            $data['salesByPayment'] = Sale::select('payment_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
                ->where('created_at', '>=', now()->subDays(30))
                ->where('status', 'completado')
                ->groupBy('payment_type')
                ->get();
        }

        return view('dashboard', compact('data'));
    }
}
