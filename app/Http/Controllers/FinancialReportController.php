<?php

namespace App\Http\Controllers;

use App\ActivityLogger;
use App\Exports\FinancialReportExport;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Sale;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FinancialReportController extends Controller
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
        | Ventes
        |--------------------------------------------------------------------------
        */

        $salesQuery = Sale::query()
            ->where('status', 'completed')
            ->whereBetween('sold_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59',
            ]);

        $totalSales = (clone $salesQuery)->count();

        $totalSalesAmount = (float) (clone $salesQuery)->sum('total');

        /*
        |--------------------------------------------------------------------------
        | Paiements
        |--------------------------------------------------------------------------
        */

        $paymentsQuery = Payment::query()
            ->whereHas('sale', function ($query) use ($startDate, $endDate) {
                $query->where('status', 'completed')
                    ->whereBetween('sold_at', [
                        $startDate . ' 00:00:00',
                        $endDate . ' 23:59:59',
                    ]);
            });

        $totalPaid = (float) (clone $paymentsQuery)->sum('amount');

        $cashPayments = (float) (clone $paymentsQuery)
            ->where('method', 'cash')
            ->sum('amount');

        $mobileMoneyPayments = (float) (clone $paymentsQuery)
            ->where('method', 'mobile_money')
            ->sum('amount');

        $bankTransferPayments = (float) (clone $paymentsQuery)
            ->where('method', 'bank_transfer')
            ->sum('amount');

        $cardPayments = (float) (clone $paymentsQuery)
            ->where('method', 'card')
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Crédit / impayés
        |--------------------------------------------------------------------------
        */

        $totalCredit = max(
            0,
            $totalSalesAmount - $totalPaid
        );

        /*
        |--------------------------------------------------------------------------
        | Remboursements
        |--------------------------------------------------------------------------
        */

        $refundsQuery = Refund::query()
            ->whereHas('sale', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('sold_at', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59',
                ]);
            });

        $totalRefunds = (float) (clone $refundsQuery)->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Résultat financier
        |--------------------------------------------------------------------------
        */

        $netPaid = max(
            0,
            $totalPaid - $totalRefunds
        );

        ActivityLogger::log(
            'report.financial.viewed',
            'Rapport financier consulté',
            null,
            [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]
        );

        return view('reports.financial', compact(
            'startDate',
            'endDate',
            'totalSales',
            'totalSalesAmount',
            'totalPaid',
            'totalCredit',
            'totalRefunds',
            'netPaid',
            'cashPayments',
            'mobileMoneyPayments',
            'bankTransferPayments',
            'cardPayments'
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
            'report.financial.exported',
            'Rapport financier exporté',
            null,
            [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'format' => 'xlsx',
            ]
        );

        return Excel::download(
            new FinancialReportExport($startDate, $endDate),
            'rapport-financier-' . $startDate . '-au-' . $endDate . '.xlsx'
        );
    }
}
