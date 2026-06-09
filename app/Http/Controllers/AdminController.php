<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function reports()
    {
        $dailySales = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(total) as total')
        )
            ->where('status', 'completado')
            ->groupBy('date')
            ->orderByDesc('date')
            ->limit(30)
            ->get();

        $monthlyRevenue = Sale::where('status', 'completado')
            ->where('created_at', '>=', now()->subMonth())
            ->sum('total');

        $totalCustomers = Customer::count();
        $totalSales = Sale::where('status', 'completado')->count();
        $totalRevenue = Sale::where('status', 'completado')->sum('total');

        return view('admin.reports', compact('dailySales', 'monthlyRevenue', 'totalCustomers', 'totalSales', 'totalRevenue'));
    }

    public function categories()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories')->with('success', 'Categoría creada exitosamente.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories')->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->withErrors('No se puede eliminar una categoría con productos asociados.');
        }

        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Categoría eliminada exitosamente.');
    }

    public function users()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'role' => 'required|in:cantina,admin,superadmin',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users')->with('success', 'Usuario creado exitosamente.');
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:cantina,admin,superadmin',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = bcrypt($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function toggleUserStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors('No puedes desactivarte a ti mismo.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activado' : 'desactivado';
        return redirect()->route('admin.users')->with('success', "Usuario {$status} exitosamente.");
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors('No puedes eliminarte a ti mismo.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Usuario eliminado permanentemente.');
    }
}
