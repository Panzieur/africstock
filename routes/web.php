<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\StockReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:admin|super-admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy');

        Route::patch('/users/{user}/deactivate', [UserController::class, 'deactivate'])
            ->name('users.deactivate');

        Route::patch('/users/{user}/activate', [UserController::class, 'activate'])
            ->name('users.activate');

    });


Route::middleware(['auth'])->group(function () {

    Route::get('/categories', [CategoryController::class, 'index'])
        ->middleware('permission:categories.view')
        ->name('categories.index');

    Route::get('/categories/create', [CategoryController::class, 'create'])
        ->middleware('permission:categories.create')
        ->name('categories.create');

    Route::post('/categories', [CategoryController::class, 'store'])
        ->middleware('permission:categories.create')
        ->name('categories.store');

    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
        ->middleware('permission:categories.edit')
        ->name('categories.edit');

    Route::put('/categories/{category}', [CategoryController::class, 'update'])
        ->middleware('permission:categories.edit')
        ->name('categories.update');

    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
        ->middleware('permission:categories.delete')
        ->name('categories.destroy');



    Route::get('/products', [ProductController::class, 'index'])
    ->middleware('permission:products.view')
    ->name('products.index');

    Route::get('/products/create', [ProductController::class, 'create'])
        ->middleware('permission:products.create')
        ->name('products.create');

    Route::post('/products', [ProductController::class, 'store'])
        ->middleware('permission:products.create')
        ->name('products.store');

    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->middleware('permission:products.view')
        ->name('products.show');

    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->middleware('permission:products.edit')
        ->name('products.edit');

    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->middleware('permission:products.edit')
        ->name('products.update');

    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->middleware('permission:products.delete')
        ->name('products.destroy');


    Route::patch('/products/{product}/deactivate', [ProductController::class, 'deactivate'])
        ->middleware('permission:products.edit')
        ->name('products.deactivate');

    Route::patch('/products/{product}/activate', [ProductController::class, 'activate'])
        ->middleware('permission:products.edit')
        ->name('products.activate');


    Route::get('/stock', [StockMovementController::class, 'index'])
        ->middleware('permission:stock.view')
        ->name('stock.index');

    Route::get('/stock/entry', [StockMovementController::class, 'create'])
        ->middleware('permission:stock.entry')
        ->name('stock.entry.create');

    Route::post('/stock/entry', [StockMovementController::class, 'store'])
        ->middleware('permission:stock.entry')
        ->name('stock.entry.store');

    Route::get('/stock/exit', [StockMovementController::class, 'createExit'])
        ->middleware('permission:stock.exit')
        ->name('stock.exit.create');

    Route::post('/stock/exit', [StockMovementController::class, 'storeExit'])
        ->middleware('permission:stock.exit')
        ->name('stock.exit.store');

    Route::get('/stock/adjustment', [StockMovementController::class, 'createAdjustment'])
        ->middleware('permission:stock.adjust')
        ->name('stock.adjustment.create');

    Route::post('/stock/adjustment', [StockMovementController::class, 'storeAdjustment'])
        ->middleware('permission:stock.adjust')
        ->name('stock.adjustment.store');

    Route::get('/inventories', [InventoryController::class, 'index'])
    ->middleware('permission:stock.inventory')
    ->name('inventories.index');

    Route::get('/inventories/create', [InventoryController::class, 'create'])
        ->middleware('permission:stock.inventory')
        ->name('inventories.create');

    Route::post('/inventories', [InventoryController::class, 'store'])
        ->middleware('permission:stock.inventory')
        ->name('inventories.store');

    Route::get('/inventories/{inventory}', [InventoryController::class, 'show'])
        ->middleware('permission:stock.inventory')
        ->name('inventories.show');

    Route::put('/inventories/{inventory}/items', [InventoryController::class, 'updateItems'])
        ->middleware('permission:stock.inventory')
        ->name('inventories.items.update');

    Route::patch('/inventories/{inventory}/complete', [InventoryController::class, 'complete'])
        ->middleware('permission:stock.inventory')
        ->name('inventories.complete');



    Route::get('/sales', [SaleController::class, 'index'])
        ->middleware('permission:sales.view')
        ->name('sales.index');

    Route::get('/sales/create', [SaleController::class, 'create'])
        ->middleware('permission:sales.create')
        ->name('sales.create');

    Route::post('/sales', [SaleController::class, 'store'])
        ->middleware('permission:sales.create')
        ->name('sales.store');

    Route::get('/sales/{sale}', [SaleController::class, 'show'])
        ->middleware('permission:sales.view')
        ->name('sales.show');

    Route::patch('/sales/{sale}/cancel', [SaleController::class, 'cancel'])
        ->middleware('permission:sales.edit')
        ->name('sales.cancel');

    Route::post('/sales/{sale}/payments', [SaleController::class, 'storePayment'])
        ->middleware('permission:payments.create')
        ->name('sales.payments.store');



    Route::get('/customers', [CustomerController::class, 'index'])
        ->middleware('permission:customers.view')
        ->name('customers.index');

    Route::get('/customers/create', [CustomerController::class, 'create'])
        ->middleware('permission:customers.create')
        ->name('customers.create');

    Route::post('/customers', [CustomerController::class, 'store'])
        ->middleware('permission:customers.create')
        ->name('customers.store');

    Route::get('/customers/{customer}', [CustomerController::class, 'show'])
        ->middleware('permission:customers.view')
        ->name('customers.show');

    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])
        ->middleware('permission:customers.edit')
        ->name('customers.edit');

    Route::put('/customers/{customer}', [CustomerController::class, 'update'])
        ->middleware('permission:customers.edit')
        ->name('customers.update');

    Route::patch('/customers/{customer}/deactivate', [CustomerController::class, 'deactivate'])
        ->middleware('permission:customers.edit')
        ->name('customers.deactivate');

    Route::patch('/customers/{customer}/activate', [CustomerController::class, 'activate'])
        ->middleware('permission:customers.edit')
        ->name('customers.activate');


    Route::post('/sales/{sale}/refunds', [SaleController::class, 'storeRefund'])
        ->middleware('permission:refunds.create')
        ->name('sales.refunds.store');

    Route::get('/reports', function () {
        return view('reports.index');
    })
        ->middleware('permission:reports.view')
        ->name('reports.index');

    Route::get('/reports/sales', [ReportController::class, 'sales'])
        ->middleware('permission:reports.sales.view')
        ->name('reports.sales');

    Route::get('/reports/stock', [StockReportController::class, 'index'])
        ->middleware('permission:reports.stock.view')
        ->name('reports.stock');

    Route::get('/reports/financial', [FinancialReportController::class, 'index'])
        ->middleware('permission:reports.financial.view')
        ->name('reports.financial');

    Route::get('/reports/sales/export', [ReportController::class, 'exportSales'])
        ->middleware('permission:reports.export')
        ->name('reports.sales.export');

    Route::get('/reports/stock/export', [StockReportController::class, 'export'])
        ->middleware('permission:reports.export')
        ->name('reports.stock.export');

    Route::get('/reports/financial/export', [FinancialReportController::class, 'export'])
        ->middleware('permission:reports.export')
        ->name('reports.financial.export');


    Route::get('/activities', [ActivityLogController::class, 'index'])
        ->middleware('permission:activities.view')
        ->name('activities.index');


    Route::get('/settings', [SettingController::class, 'index'])
        ->middleware('permission:settings.view')
        ->name('settings.index');

    Route::put('/settings', [SettingController::class, 'update'])
        ->middleware('permission:settings.edit')
        ->name('settings.update');



});







require __DIR__.'/auth.php';
