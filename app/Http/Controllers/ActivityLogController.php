<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
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

        $userId = $request->input('user_id');

        $activitiesQuery = ActivityLog::query()
            ->with('user')
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59',
            ]);

        if ($userId) {
            $activitiesQuery->where('user_id', $userId);
        }

        $user = auth()->user();

        if ($user->can('activities.all.view')) {

            // Admin / Super-admin :
            // toutes les activités sont accessibles.

        } elseif ($user->can('activities.stock.view')) {

            // Gestionnaire-stock :
            // activités stock et inventaire de tous les utilisateurs.
            $activitiesQuery->where(function ($query) {
                $query->where('action', 'like', 'stock.%')
                    ->orWhere('action', 'like', 'inventory.%');
            });

        } elseif ($user->can('activities.payments.view')) {

            // Comptable :
            // activités ventes, paiements et remboursements
            // de tous les utilisateurs.
            $activitiesQuery->where(function ($query) {
                $query->where('action', 'like', 'sale.%')
                    ->orWhere('action', 'like', 'payment.%')
                    ->orWhere('action', 'like', 'refund.%');
            });

        } elseif ($user->can('activities.sales.view')) {

            // Vendeur :
            // uniquement ses propres activités commerciales.
            $activitiesQuery->where('user_id', $user->id)
                ->where(function ($query) {
                    $query->where('action', 'like', 'sale.%')
                        ->orWhere('action', 'like', 'payment.%')
                        ->orWhere('action', 'like', 'refund.%');
                });

        } else {

            $activitiesQuery->whereRaw('1 = 0');
        }

        $activities = $activitiesQuery
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('activities.index', compact(
            'activities',
            'startDate',
            'endDate'
        ));
    }
}
