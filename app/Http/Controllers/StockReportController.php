<?php

namespace App\Http\Controllers;

use App\ActivityLogger;
use App\Exports\StockReportExport;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StockReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input(
            'start_date',
            now()->startOfMonth()->format('Y-m-d')
        );

        $endDate = $request->input(
            'end_date',
            now()->format('Y-m-d')
        );

        /*
        |--------------------------------------------------------------------------
        | Produits et stock actuel
        |--------------------------------------------------------------------------
        */

        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        $totalProducts = $products->count();

        $lowStockProducts = $products->filter(function ($product) {
            return $product->stock_quantity <= $product->minimum_stock;
        });

        $totalStockQuantity = $products->sum('stock_quantity');

        $totalStockValue = $products->sum(function ($product) {
            return (float) $product->stock_quantity
                * (float) $product->purchase_price;
        });

        /*
        |--------------------------------------------------------------------------
        | Mouvements de stock
        |--------------------------------------------------------------------------
        */

        $movementsQuery = StockMovement::query()
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59',
            ]);

        $entries = (clone $movementsQuery)
            ->where('type', 'entry')
            ->sum('quantity');

        $exits = (clone $movementsQuery)
            ->where('type', 'exit')
            ->sum('quantity');

        $adjustments = (clone $movementsQuery)
            ->where('type', 'adjustment')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Historique des mouvements
        |--------------------------------------------------------------------------
        */

        $movements = $movementsQuery
            ->with(['product', 'user'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

            ActivityLogger::log(
                'report.stock.viewed',
                'Rapport de stock consulté',
                null,
                [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]
            );

        return view('reports.stock', compact(
            'products',
            'movements',
            'startDate',
            'endDate',
            'totalProducts',
            'totalStockQuantity',
            'totalStockValue',
            'lowStockProducts',
            'entries',
            'exits',
            'adjustments'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input(
            'start_date',
            now()->startOfMonth()->format('Y-m-d')
        );

        $endDate = $request->input(
            'end_date',
            now()->format('Y-m-d')
        );


        ActivityLogger::log(
            'report.stock.exported',
            'Rapport de stock exporté',
            null,
            [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'format' => 'xlsx',
            ]
        );

        return Excel::download(
            new StockReportExport($startDate, $endDate),
            'rapport-stock-' . $startDate . '-au-' . $endDate . '.xlsx'
        );
    }
}
