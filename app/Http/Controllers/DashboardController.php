<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Product;
use App\Models\Refund;
use App\Models\Sale;
use App\Models\StockMovement;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        /*
        |--------------------------------------------------------------------------
        | Rôle de l'utilisateur
        |--------------------------------------------------------------------------
        */

        $dashboardRole = $user->getRoleNames()->first();

        /*
        |--------------------------------------------------------------------------
        | Valeurs par défaut
        |--------------------------------------------------------------------------
        */

        $totalProducts = 0;
        $totalStock = 0;
        $lowStockProducts = 0;

        $todaySales = 0;
        $todayRevenue = 0;

        $todayEntries = 0;
        $todayExits = 0;
        $todayAdjustments = 0;

        $todayPayments = 0;
        $todayRefunds = 0;
        $todayCredit = 0;

        $myTodaySales = 0;
        $myTodayRevenue = 0;
        $myTodayPayments = 0;

        $recentSales = collect();
        $recentMovements = collect();
        $lowStockItems = collect();
        $recentPayments = collect();
        $recentActivities = collect();

        /*
        |--------------------------------------------------------------------------
        | Clients ayant des dettes
        |--------------------------------------------------------------------------
        */

        $creditSales = collect();

        /*
        |--------------------------------------------------------------------------
        | SUPER-ADMIN / ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole(['super-admin', 'admin'])) {

            $totalProducts = Product::count();

            $totalStock = Product::sum('stock_quantity');

            $lowStockProducts = Product::whereColumn(
                'stock_quantity',
                '<=',
                'minimum_stock'
            )->count();

            $todaySales = Sale::whereDate('sold_at', $today)
                ->where('status', 'completed')
                ->count();

            $todayRevenue = Sale::whereDate('sold_at', $today)
                ->where('status', 'completed')
                ->sum('total');

            $recentSales = Sale::with([
                    'customer',
                    'user',
                    'items.product',
                ])
                ->where('status', 'completed')
                ->latest('sold_at')
                ->take(5)
                ->get();

            $recentMovements = StockMovement::with([
                    'product',
                    'user',
                ])
                ->latest()
                ->take(5)
                ->get();

            $lowStockItems = Product::whereColumn(
                'stock_quantity',
                '<=',
                'minimum_stock'
            )
                ->orderBy('stock_quantity')
                ->take(5)
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Ventes avec crédit restant
            |--------------------------------------------------------------------------
            */

            $creditSales = Sale::with([
                    'customer',
                    'payments',
                ])
                ->where('status', 'completed')
                ->whereNotNull('customer_id')
                ->whereNotNull('due_date')
                ->get()
                ->filter(function ($sale) {
                    $paidAmount = $sale->payments->sum('amount');

                    return $paidAmount < $sale->total;
                })
                ->sortBy('due_date')
                ->take(10);
        }

        /*
        |--------------------------------------------------------------------------
        | VENDEUR
        |--------------------------------------------------------------------------
        */

        elseif ($user->hasRole('vendeur')) {

            $myTodaySales = Sale::where('user_id', $user->id)
                ->whereDate('sold_at', $today)
                ->where('status', 'completed')
                ->count();

            $myTodayRevenue = Sale::where('user_id', $user->id)
                ->whereDate('sold_at', $today)
                ->where('status', 'completed')
                ->sum('total');

            $myTodayPayments = Payment::whereHas('sale', function ($query) use ($user, $today) {
                    $query->where('user_id', $user->id)
                        ->whereDate('sold_at', $today)
                        ->where('status', 'completed');
                })
                ->sum('amount');

            $recentSales = Sale::with([
                    'customer',
                    'items.product',
                ])
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->latest('sold_at')
                ->take(5)
                ->get();

            $recentPayments = Payment::with([
                    'sale.items.product',
                ])
                ->whereHas('sale', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->latest('paid_at')
                ->take(5)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | GESTIONNAIRE STOCK
        |--------------------------------------------------------------------------
        */

        elseif ($user->hasRole('gestionnaire-stock')) {

            $totalProducts = Product::count();

            $totalStock = Product::sum('stock_quantity');

            $lowStockProducts = Product::whereColumn(
                'stock_quantity',
                '<=',
                'minimum_stock'
            )->count();

            $todayEntries = StockMovement::whereDate('created_at', $today)
                ->where('type', 'entry')
                ->sum('quantity');

            $todayExits = StockMovement::whereDate('created_at', $today)
                ->where('type', 'exit')
                ->sum('quantity');

            $todayAdjustments = StockMovement::whereDate('created_at', $today)
                ->where('type', 'adjustment')
                ->sum('quantity');

            $recentMovements = StockMovement::with([
                    'product',
                    'user',
                ])
                ->latest()
                ->take(5)
                ->get();

            $lowStockItems = Product::whereColumn(
                'stock_quantity',
                '<=',
                'minimum_stock'
            )
                ->orderBy('stock_quantity')
                ->take(5)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | COMPTABLE
        |--------------------------------------------------------------------------
        */

        elseif ($user->hasRole('comptable')) {

            $todaySales = Sale::whereDate('sold_at', $today)
                ->where('status', 'completed')
                ->count();

            $todayPayments = Payment::whereDate('paid_at', $today)
                ->sum('amount');

            $todayRefunds = Refund::whereDate('refunded_at', $today)
                ->sum('amount');

            $todayRevenue = Sale::whereDate('sold_at', $today)
                ->where('status', 'completed')
                ->sum('total');

            $todayCredit = max(
                0,
                $todayRevenue - $todayPayments
            );

            $recentSales = Sale::with([
                    'customer',
                    'user',
                    'items.product',
                ])
                ->where('status', 'completed')
                ->latest('sold_at')
                ->take(5)
                ->get();

            $recentPayments = Payment::with([
                    'sale.user',
                    'sale.customer',
                    'sale.items.product',
                ])
                ->latest('paid_at')
                ->take(5)
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Clients ayant des dettes
            |--------------------------------------------------------------------------
            */

            $creditSales = Sale::with([
                    'customer',
                    'payments',
                ])
                ->where('status', 'completed')
                ->whereNotNull('customer_id')
                ->whereNotNull('due_date')
                ->get()
                ->filter(function ($sale) {
                    $paidAmount = $sale->payments->sum('amount');

                    return $paidAmount < $sale->total;
                })
                ->sortBy('due_date')
                ->take(10);
        }

        /*
        |--------------------------------------------------------------------------
        | LECTEUR
        |--------------------------------------------------------------------------
        */

        elseif ($user->hasRole('lecteur')) {

            $totalProducts = Product::count();

            $totalStock = Product::sum('stock_quantity');

            $lowStockProducts = Product::whereColumn(
                'stock_quantity',
                '<=',
                'minimum_stock'
            )->count();

            $recentSales = Sale::with([
                    'customer',
                    'user',
                    'items.product',
                ])
                ->where('status', 'completed')
                ->latest('sold_at')
                ->take(5)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return view('dashboard', compact(
            'dashboardRole',

            'totalProducts',
            'totalStock',
            'lowStockProducts',

            'todaySales',
            'todayRevenue',

            'todayEntries',
            'todayExits',
            'todayAdjustments',

            'todayPayments',
            'todayRefunds',
            'todayCredit',

            'myTodaySales',
            'myTodayRevenue',
            'myTodayPayments',

            'recentSales',
            'recentMovements',
            'lowStockItems',
            'recentPayments',
            'recentActivities',
            'creditSales'
        ));
    }
}