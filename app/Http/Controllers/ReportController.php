<?php

namespace App\Http\Controllers;

use App\ActivityLogger;
use App\Exports\SalesReportExport;
use App\Models\Sale;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = $request->input(
            'start_date',
            now()->startOfMonth()->format('Y-m-d')
        );

        $endDate = $request->input(
            'end_date',
            now()->format('Y-m-d')
        );

        $salesQuery = Sale::query()
            ->with(['customer', 'user'])
            ->withSum('payments', 'amount')
            ->whereBetween('sold_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Statistiques générales
        |--------------------------------------------------------------------------
        */

        $completedSales = (clone $salesQuery)
            ->where('status', 'completed');

        $cancelledSales = (clone $salesQuery)
            ->where('status', 'cancelled');

        $totalSales = (clone $completedSales)->count();

        $totalAmount = (float) $completedSales->sum('total');

        $totalPaid = (float) $completedSales
            ->get()
            ->sum(function ($sale) {
                return (float) ($sale->payments_sum_amount ?? 0);
            });

        $totalCredit = max(
            0,
            $totalAmount - $totalPaid
        );

        $cancelledCount = $cancelledSales->count();

        /*
        |--------------------------------------------------------------------------
        | Ventes
        |--------------------------------------------------------------------------
        */

        $sales = $salesQuery
            ->latest('sold_at')
            ->paginate(15)
            ->withQueryString();

        ActivityLogger::log(
            'report.sales.viewed',
            'Rapport des ventes consulté',
            null,
            [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Données pour la vue
        |--------------------------------------------------------------------------
        */

        return view('reports.sales', compact(
            'sales',
            'startDate',
            'endDate',
            'totalSales',
            'totalAmount',
            'totalPaid',
            'totalCredit',
            'cancelledCount'
        ));
    }

    public function exportSales(Request $request)
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
            'report.sales.exported',
            'Rapport des ventes exporté',
            null,
            [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'format' => 'xlsx',
            ]
        );


        return Excel::download(
            new SalesReportExport($startDate, $endDate),
            'rapport-ventes-' . $startDate . '-au-' . $endDate . '.xlsx'
        );
    }
}
