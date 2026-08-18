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
use App\Http\Controllers\AlmuerzoController;
use App\Http\Controllers\StockMovementController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BackupController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/reset-photo', [ProfileController::class, 'resetPhoto'])->name('profile.reset-photo');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    Route::middleware('role:admin')->group(function () {
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/approve-price', [ProductController::class, 'approvePrice'])->name('products.approve-price');
        Route::get('/products/import/template', [ProductController::class, 'downloadTemplate'])->name('products.import.template');
        Route::post('/products/import', [ProductController::class, 'importExcel'])->name('products.import');
    });

    Route::resource('customers', CustomerController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/customers/destroy-multiple', [CustomerController::class, 'destroyMultiple'])->name('customers.destroy-multiple');
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::get('/customers/import/template', [CustomerController::class, 'downloadTemplate'])->name('customers.import.template');
    Route::post('/customers/import', [CustomerController::class, 'importExcel'])->name('customers.import');

    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update');
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
    Route::delete('/sales/{sale}/force', [SaleController::class, 'forceDestroy'])->name('sales.force-destroy');
    Route::get('/sales/{sale}/recibo', [SaleController::class, 'receiptPdf'])->name('sales.receipt-pdf');
    Route::get('/sales/{sale}/recibo-url', [SaleController::class, 'receiptPdfUrl'])->name('sales.receipt-pdf-url');

    Route::get('/almuerzos', [AlmuerzoController::class, 'index'])->name('almuerzos.index');
    Route::post('/almuerzos', [AlmuerzoController::class, 'store'])->name('almuerzos.store');
    Route::post('/almuerzos/{almuerzo}/toggle', [AlmuerzoController::class, 'toggle'])->name('almuerzos.toggle');
    Route::delete('/almuerzos/{almuerzo}', [AlmuerzoController::class, 'destroy'])->name('almuerzos.destroy');
    Route::get('/almuerzos/reporte', [AlmuerzoController::class, 'reporteMensual'])->name('almuerzos.reporte');
    Route::get('/almuerzos/daily-pdf', [AlmuerzoController::class, 'dailyPdf'])->name('almuerzos.daily-pdf');
    Route::get('/almuerzos/reporte/pdf', [AlmuerzoController::class, 'reportePdf'])->name('almuerzos.reporte-pdf');
    Route::get('/almuerzos/crear-cliente', [AlmuerzoController::class, 'createCliente'])->name('almuerzos.create-cliente');
    Route::post('/almuerzos/crear-cliente', [AlmuerzoController::class, 'storeCliente'])->name('almuerzos.store-cliente');

    Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
    Route::get('/stock-movements/create', [StockMovementController::class, 'create'])->name('stock-movements.create');
    Route::post('/stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');
    Route::get('/stock-movements/{stockMovement}/edit', [StockMovementController::class, 'edit'])->name('stock-movements.edit');
    Route::put('/stock-movements/{stockMovement}', [StockMovementController::class, 'update'])->name('stock-movements.update');
    Route::delete('/stock-movements/{stockMovement}', [StockMovementController::class, 'destroy'])->name('stock-movements.destroy');

    Route::middleware('role:admin')->group(function () {
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/reports/pdf', [AdminController::class, 'reportsPdf'])->name('reports.pdf');
        Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
        Route::put('/settings/branding', [AdminController::class, 'updateBranding'])->name('settings.branding');
        Route::post('/settings/branding/reset', [AdminController::class, 'resetBranding'])->name('settings.branding.reset');

        Route::middleware('role:superadmin')->group(function () {
            Route::post('/backups', [BackupController::class, 'create'])->name('backups.create');
            Route::get('/backups', [BackupController::class, 'list'])->name('backups.list');
            Route::get('/backups/{filename}/download', [BackupController::class, 'download'])->name('backups.download');
            Route::post('/backups/restore', [BackupController::class, 'restore'])->name('backups.restore');
            Route::delete('/backups/{filename}', [BackupController::class, 'destroy'])->name('backups.destroy');
        });
    });

    Route::get('/caja', [CajaController::class, 'index'])->name('caja.index');
    Route::get('/caja/create', [CajaController::class, 'create'])->name('caja.create');
    Route::post('/caja', [CajaController::class, 'store'])->name('caja.store');
    Route::get('/caja/{caja}', [CajaController::class, 'show'])->name('caja.show');
    Route::post('/caja/{caja}/close', [CajaController::class, 'close'])->name('caja.close');
    Route::delete('/caja/{caja}', [CajaController::class, 'destroy'])->name('caja.destroy');
    Route::post('/caja/{caja}/movimientos', [CajaController::class, 'storeMovimiento'])->name('caja.movimiento.store');
    Route::delete('/caja/{caja}/movimientos/{movimiento}', [CajaController::class, 'destroyMovimiento'])->name('caja.movimiento.destroy');
    Route::get('/libro-diario', [CajaController::class, 'libroDiario'])->name('caja.libro-diario')->middleware('role:admin');

    Route::get('/cuentas-por-cobrar', [CustomerSalesController::class, 'index'])->name('customer-sales.index');
    Route::get('/cuentas-por-cobrar/pdf', [CustomerSalesController::class, 'exportPdf'])->name('customer-sales.pdf');
    Route::post('/cuentas-por-cobrar/{sale}/pay', [CustomerSalesController::class, 'markAsPaid'])->name('customer-sales.pay');
    Route::post('/cuentas-por-cobrar/{sale}/unpay', [CustomerSalesController::class, 'markAsUnpaid'])->name('customer-sales.unpay');

    // Route::get('/sync', [SyncController::class, 'pushUnsynced'])->name('sync.push');

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
