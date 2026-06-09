<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\CustomerSalesController;
use App\Http\Controllers\SyncController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    Route::middleware('role:admin')->group(function () {
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/approve-price', [ProductController::class, 'approvePrice'])->name('products.approve-price');
    });

    Route::resource('customers', CustomerController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');

    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update');
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
    Route::delete('/sales/{sale}/force', [SaleController::class, 'forceDestroy'])->name('sales.force-destroy');

    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    });

    Route::get('/caja', [CajaController::class, 'index'])->name('caja.index');
    Route::get('/caja/create', [CajaController::class, 'create'])->name('caja.create');
    Route::post('/caja', [CajaController::class, 'store'])->name('caja.store');
    Route::get('/caja/{caja}', [CajaController::class, 'show'])->name('caja.show');
    Route::post('/caja/{caja}/close', [CajaController::class, 'close'])->name('caja.close');
    Route::delete('/caja/{caja}', [CajaController::class, 'destroy'])->name('caja.destroy');
    Route::post('/caja/{caja}/movimientos', [CajaController::class, 'storeMovimiento'])->name('caja.movimiento.store');
    Route::delete('/caja/{caja}/movimientos/{movimiento}', [CajaController::class, 'destroyMovimiento'])->name('caja.movimiento.destroy');
    Route::get('/libro-diario', [CajaController::class, 'libroDiario'])->name('caja.libro-diario');

    Route::get('/cuentas-por-cobrar', [CustomerSalesController::class, 'index'])->name('customer-sales.index');
    Route::post('/cuentas-por-cobrar/{sale}/pay', [CustomerSalesController::class, 'markAsPaid'])->name('customer-sales.pay');
    Route::post('/cuentas-por-cobrar/{sale}/unpay', [CustomerSalesController::class, 'markAsUnpaid'])->name('customer-sales.unpay');

    Route::get('/sync', [SyncController::class, 'pushUnsynced'])->name('sync.push');

    Route::post('/switch-role/{role}', function (string $role) {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        if (!in_array($role, ['admin', 'cantina', 'superadmin'])) {
            abort(400);
        }

        if (!$user->isSuperAdmin() && $role === 'superadmin') {
            abort(403);
        }

        session(['active_role' => $role]);
        return redirect()->back();
    })->name('switch.role');
});

require __DIR__.'/auth.php';
