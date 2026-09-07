<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                Tableau de bord
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                @if(in_array($dashboardRole, ['super-admin', 'admin']))
                    Vue globale de l'activité de l'entreprise.
                @elseif($dashboardRole === 'vendeur')
                    Suivez votre activité commerciale du jour.
                @elseif($dashboardRole === 'gestionnaire-stock')
                    Suivez l'état et les mouvements du stock.
                @elseif($dashboardRole === 'comptable')
                    Suivez les ventes et les opérations financières.
                @elseif($dashboardRole === 'lecteur')
                    Consultez les principales informations de l'entreprise.
                @endif
            </p>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="px-4 mx-auto space-y-5 max-w-7xl sm:px-6 lg:px-8">

            {{-- ========================================================= --}}
            {{-- ADMIN / SUPER-ADMIN --}}
            {{-- ========================================================= --}}
            @if(in_array($dashboardRole, ['super-admin', 'admin']))

                {{-- Statistiques principales --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    {{-- Produits --}}
                    <div class="p-4 overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Produits
                                </p>
                                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ number_format($totalProducts) }}
                                </p>
                            </div>

                            <div class="p-2 bg-blue-100 rounded-lg dark:bg-blue-900/30">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Stock total --}}
                    <div class="p-4 overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Stock total
                                </p>
                                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ number_format($totalStock) }}
                                </p>
                            </div>

                            <div class="p-2 bg-green-100 rounded-lg dark:bg-green-900/30">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M3 7h18M3 12h18M3 17h18"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Ventes --}}
                    <div class="p-4 overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Ventes aujourd'hui
                                </p>
                                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ number_format($todaySales) }}
                                </p>
                            </div>

                            <div class="p-2 bg-purple-100 rounded-lg dark:bg-purple-900/30">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M9 14l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- CA --}}
                    <div class="p-4 overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    CA aujourd'hui
                                </p>
                                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ number_format($todayRevenue, 0, ',', ' ') }} F
                                </p>
                            </div>

                            <div class="p-2 rounded-lg bg-emerald-100 dark:bg-emerald-900/30">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v1m0 10v1m9-6a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alerte stock faible --}}
                @if($lowStockProducts > 0)
                    <div class="p-4 border rounded-lg border-amber-200 bg-amber-50 dark:border-amber-800 dark:bg-amber-900/20">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0 text-amber-600 dark:text-amber-400"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 9v2m0 4h.01M10.29 3.86l-7.82 14a2 2 0 001.74 3h15.58a2 2 0 001.74-3l-7.82-14a2 2 0 00-3.42 0z"/>
                            </svg>

                            <div>
                                <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">
                                    Attention : stock faible
                                </p>
                                <p class="text-xs text-amber-700 dark:text-amber-400">
                                    {{ $lowStockProducts }} produit(s) ont atteint leur seuil minimum.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Stock faible + dernières ventes --}}
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

                    {{-- Stock faible --}}
                    <div class="overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                Produits à stock faible
                            </h3>
                        </div>

                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($lowStockItems as $product)
                                <div class="flex items-center justify-between px-4 py-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            {{ $product->name }}
                                        </p>

                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Minimum : {{ $product->minimum_stock }}
                                        </p>
                                    </div>

                                    <span class="ml-3 rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                        {{ $product->stock_quantity }}
                                    </span>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-sm text-center text-gray-500 dark:text-gray-400">
                                    Aucun produit en stock faible.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Dernières ventes --}}
                    <div class="overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                Dernières ventes
                            </h3>
                        </div>

                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($recentSales as $sale)
                                <div class="flex items-center justify-between gap-3 px-4 py-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            @foreach($sale->items as $item)
                                                {{ $item->product?->name ?? 'Produit supprimé' }} × {{ $item->quantity }}@if(!$loop->last), @endif
                                            @endforeach
                                        </p>

                                        <p class="text-xs text-gray-500 truncate dark:text-gray-400">
                                            {{ $sale->customer?->name ?? 'Client comptoir' }}
                                            · {{ $sale->user?->name ?? 'Utilisateur' }}
                                            · Vente #{{ $sale->id }}
                                        </p>
                                    </div>

                                    <div class="text-right shrink-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ number_format($sale->total, 0, ',', ' ') }} F
                                        </p>

                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $sale->sold_at?->format('d/m H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-sm text-center text-gray-500 dark:text-gray-400">
                                    Aucune vente récente.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Clients ayant des dettes --}}
                <div class="overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">

                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">

                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                Clients ayant des dettes
                            </h3>

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Liste des crédits encore non remboursés.
                            </p>
                        </div>

                        <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-400">
                            {{ $creditSales->count() }}
                        </span>

                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-700">

                        @forelse($creditSales as $sale)

                            @php
                                $paidAmount = $sale->payments->sum('amount');
                                $remainingAmount = max(0, $sale->total - $paidAmount);

                                $isOverdue = $sale->due_date &&
                                    \Carbon\Carbon::parse($sale->due_date)->isPast();
                            @endphp

                            <div class="flex items-center justify-between gap-4 px-4 py-3">

                                <div class="min-w-0">

                                    <p class="text-sm font-semibold text-gray-900 truncate dark:text-white">
                                        {{ $sale->customer?->name ?? 'Client inconnu' }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Vente {{ $sale->reference }}
                                        · Échéance :
                                        {{ \Carbon\Carbon::parse($sale->due_date)->format('d/m/Y') }}
                                    </p>

                                </div>

                                <div class="text-right shrink-0">

                                    <p class="text-sm font-bold text-red-600 dark:text-red-400">
                                        {{ number_format($remainingAmount, 0, ',', ' ') }} F
                                    </p>

                                    @if($isOverdue)

                                        <span class="mt-1 inline-block rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                            En retard
                                        </span>

                                    @else

                                        <span class="mt-1 inline-block rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                            En attente
                                        </span>

                                    @endif

                                </div>

                            </div>

                        @empty

                            <div class="px-4 py-6 text-sm text-center text-gray-500 dark:text-gray-400">
                                Aucun client n'a actuellement de dette.
                            </div>

                        @endforelse

                    </div>

                </div>

                {{-- Mouvements de stock --}}
                <div class="overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                            Derniers mouvements de stock
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Produit
                                    </th>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Type
                                    </th>
                                    <th class="px-4 py-2.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Quantité
                                    </th>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Utilisateur
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($recentMovements as $movement)
                                    <tr>
                                        <td class="px-4 py-2.5 text-sm text-gray-900 dark:text-white">
                                            {{ $movement->product?->name ?? 'Produit supprimé' }}
                                        </td>

                                        <td class="px-4 py-2.5">
                                            @if($movement->type === 'entry')
                                                <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/30 dark:text-green-400">
                                                    Entrée
                                                </span>
                                            @elseif($movement->type === 'exit')
                                                <span class="px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full dark:bg-red-900/30 dark:text-red-400">
                                                    Sortie
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                                    Ajustement
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-2.5 text-right text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $movement->quantity }}
                                        </td>

                                        <td class="px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $movement->user?->name ?? '—' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-sm text-center text-gray-500 dark:text-gray-400">
                                            Aucun mouvement récent.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>


            {{-- ========================================================= --}}
            {{-- VENDEUR --}}
            {{-- ========================================================= --}}
            @elseif($dashboardRole === 'vendeur')

                {{-- Statistiques vendeur --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Mes ventes aujourd'hui
                        </p>

                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($myTodaySales) }}
                        </p>
                    </div>

                    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Mon chiffre d'affaires
                        </p>

                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($myTodayRevenue, 0, ',', ' ') }} F
                        </p>
                    </div>

                    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Paiements encaissés
                        </p>

                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($myTodayPayments, 0, ',', ' ') }} F
                        </p>
                    </div>
                </div>

                {{-- Ventes + paiements vendeur --}}
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

                    {{-- Mes dernières ventes --}}
                    <div class="overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                Mes dernières ventes
                            </h3>
                        </div>

                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($recentSales as $sale)
                                <div class="flex items-center justify-between gap-3 px-4 py-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            @foreach($sale->items as $item)
                                                {{ $item->product?->name ?? 'Produit supprimé' }} × {{ $item->quantity }}@if(!$loop->last), @endif
                                            @endforeach
                                        </p>

                                        <p class="text-xs text-gray-500 truncate dark:text-gray-400">
                                            {{ $sale->customer?->name ?? 'Client comptoir' }}
                                            · Vente #{{ $sale->id }}
                                        </p>
                                    </div>

                                    <div class="text-right shrink-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ number_format($sale->total, 0, ',', ' ') }} F
                                        </p>

                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $sale->sold_at?->format('d/m H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-sm text-center text-gray-500 dark:text-gray-400">
                                    Aucune vente récente.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Mes derniers paiements --}}
                    <div class="overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                Mes derniers paiements
                            </h3>
                        </div>

                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($recentPayments as $payment)
                                <div class="flex items-center justify-between gap-3 px-4 py-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            @if($payment->sale)
                                                @foreach($payment->sale->items as $item)
                                                    {{ $item->product?->name ?? 'Produit supprimé' }} × {{ $item->quantity }}@if(!$loop->last), @endif
                                                @endforeach
                                            @else
                                                Vente introuvable
                                            @endif
                                        </p>

                                        <p class="text-xs text-gray-500 truncate dark:text-gray-400">
                                            {{ $payment->method ?? 'Paiement' }}

                                            @if($payment->sale)
                                                · Vente #{{ $payment->sale->id }}
                                            @endif
                                        </p>
                                    </div>

                                    <div class="text-right shrink-0">
                                        <p class="text-sm font-semibold text-green-600 dark:text-green-400">
                                            +{{ number_format($payment->amount, 0, ',', ' ') }} F
                                        </p>

                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $payment->paid_at?->format('d/m H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-sm text-center text-gray-500 dark:text-gray-400">
                                    Aucun paiement récent.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>


            {{-- ========================================================= --}}
            {{-- GESTIONNAIRE STOCK --}}
            {{-- ========================================================= --}}
            @elseif($dashboardRole === 'gestionnaire-stock')

                {{-- Statistiques stock --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Produits
                        </p>
                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($totalProducts) }}
                        </p>
                    </div>

                    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Stock total
                        </p>
                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($totalStock) }}
                        </p>
                    </div>

                    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Entrées aujourd'hui
                        </p>
                        <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">
                            +{{ number_format($todayEntries) }}
                        </p>
                    </div>

                    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Sorties aujourd'hui
                        </p>
                        <p class="mt-1 text-2xl font-bold text-red-600 dark:text-red-400">
                            -{{ number_format($todayExits) }}
                        </p>
                    </div>
                </div>

                {{-- Alerte stock --}}
                @if($lowStockProducts > 0)
                    <div class="p-4 border rounded-lg border-amber-200 bg-amber-50 dark:border-amber-800 dark:bg-amber-900/20">
                        <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">
                            {{ $lowStockProducts }} produit(s) nécessitent une attention.
                        </p>
                    </div>
                @endif

                {{-- Stock faible + mouvements --}}
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

                    {{-- Produits stock faible --}}
                    <div class="overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                Produits à stock faible
                            </h3>
                        </div>

                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($lowStockItems as $product)
                                <div class="flex items-center justify-between px-4 py-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            {{ $product->name }}
                                        </p>

                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Minimum : {{ $product->minimum_stock }}
                                        </p>
                                    </div>

                                    <span class="ml-3 rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                        {{ $product->stock_quantity }}
                                    </span>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-sm text-center text-gray-500 dark:text-gray-400">
                                    Aucun produit en stock faible.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Derniers mouvements --}}
                    <div class="overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                Derniers mouvements
                            </h3>
                        </div>

                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($recentMovements as $movement)
                                <div class="flex items-center justify-between gap-3 px-4 py-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            {{ $movement->product?->name ?? 'Produit supprimé' }}
                                        </p>

                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $movement->user?->name ?? 'Utilisateur' }}
                                        </p>
                                    </div>

                                    <div class="text-right shrink-0">
                                        @if($movement->type === 'entry')
                                            <p class="text-sm font-semibold text-green-600 dark:text-green-400">
                                                +{{ $movement->quantity }}
                                            </p>
                                        @elseif($movement->type === 'exit')
                                            <p class="text-sm font-semibold text-red-600 dark:text-red-400">
                                                -{{ $movement->quantity }}
                                            </p>
                                        @else
                                            <p class="text-sm font-semibold text-amber-600 dark:text-amber-400">
                                                {{ $movement->quantity }}
                                            </p>
                                        @endif

                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $movement->created_at?->format('d/m H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-sm text-center text-gray-500 dark:text-gray-400">
                                    Aucun mouvement récent.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>


            {{-- ========================================================= --}}
            {{-- COMPTABLE --}}
            {{-- ========================================================= --}}
            @elseif($dashboardRole === 'comptable')

                {{-- Statistiques financières --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Ventes
                        </p>

                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($todaySales) }}
                        </p>
                    </div>

                    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Encaissements
                        </p>

                        <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ number_format($todayPayments, 0, ',', ' ') }} F
                        </p>
                    </div>

                    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Crédit estimé
                        </p>

                        <p class="mt-1 text-2xl font-bold text-amber-600 dark:text-amber-400">
                            {{ number_format($todayCredit, 0, ',', ' ') }} F
                        </p>
                    </div>

                    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Remboursements
                        </p>

                        <p class="mt-1 text-2xl font-bold text-red-600 dark:text-red-400">
                            {{ number_format($todayRefunds, 0, ',', ' ') }} F
                        </p>
                    </div>
                </div>

                {{-- Ventes + paiements --}}
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

                    {{-- Dernières ventes --}}
                    <div class="overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                Dernières ventes
                            </h3>

                            @can('reports.sales.view')
                                <a href="{{ route('reports.sales') }}"
                                   class="text-xs font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                                    Rapport
                                </a>
                            @endcan
                        </div>

                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($recentSales as $sale)
                                <div class="flex items-center justify-between gap-3 px-4 py-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            @foreach($sale->items as $item)
                                                {{ $item->product?->name ?? 'Produit supprimé' }} × {{ $item->quantity }}@if(!$loop->last), @endif
                                            @endforeach
                                        </p>

                                        <p class="text-xs text-gray-500 truncate dark:text-gray-400">
                                            {{ $sale->customer?->name ?? 'Client comptoir' }}
                                            · {{ $sale->user?->name ?? 'Utilisateur' }}
                                            · Vente #{{ $sale->id }}
                                        </p>
                                    </div>

                                    <div class="text-right shrink-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ number_format($sale->total, 0, ',', ' ') }} F
                                        </p>

                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $sale->sold_at?->format('d/m H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-sm text-center text-gray-500 dark:text-gray-400">
                                    Aucune vente récente.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Derniers encaissements --}}
                    <div class="overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                Derniers encaissements
                            </h3>
                        </div>

                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($recentPayments as $payment)
                                <div class="flex items-center justify-between gap-3 px-4 py-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            @if($payment->sale)
                                                @foreach($payment->sale->items as $item)
                                                    {{ $item->product?->name ?? 'Produit supprimé' }} × {{ $item->quantity }}@if(!$loop->last), @endif
                                                @endforeach
                                            @else
                                                Vente introuvable
                                            @endif
                                        </p>

                                        <p class="text-xs text-gray-500 truncate dark:text-gray-400">
                                            {{ $payment->method ?? 'Paiement' }}

                                            @if($payment->sale)
                                                · {{ $payment->sale->user?->name ?? 'Utilisateur' }}
                                                · Vente #{{ $payment->sale->id }}
                                            @endif
                                        </p>
                                    </div>

                                    <div class="text-right shrink-0">
                                        <p class="text-sm font-semibold text-green-600 dark:text-green-400">
                                            +{{ number_format($payment->amount, 0, ',', ' ') }} F
                                        </p>

                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $payment->paid_at?->format('d/m H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-sm text-center text-gray-500 dark:text-gray-400">
                                    Aucun encaissement récent.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Clients ayant des dettes --}}
                <div class="overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">

                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">

                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                Clients ayant des dettes
                            </h3>

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Liste des crédits encore non remboursés.
                            </p>
                        </div>

                        <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-400">
                            {{ $creditSales->count() }}
                        </span>

                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-700">

                        @forelse($creditSales as $sale)

                            @php
                                $paidAmount = $sale->payments->sum('amount');
                                $remainingAmount = max(0, $sale->total - $paidAmount);

                                $isOverdue = $sale->due_date &&
                                    \Carbon\Carbon::parse($sale->due_date)->isPast();
                            @endphp

                            <div class="flex items-center justify-between gap-4 px-4 py-3">

                                <div class="min-w-0">

                                    <p class="text-sm font-semibold text-gray-900 truncate dark:text-white">
                                        {{ $sale->customer?->name ?? 'Client inconnu' }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Vente {{ $sale->reference }}
                                        · Échéance :
                                        {{ \Carbon\Carbon::parse($sale->due_date)->format('d/m/Y') }}
                                    </p>

                                </div>

                                <div class="text-right shrink-0">

                                    <p class="text-sm font-bold text-red-600 dark:text-red-400">
                                        {{ number_format($remainingAmount, 0, ',', ' ') }} F
                                    </p>

                                    @if($isOverdue)

                                        <span class="mt-1 inline-block rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                            En retard
                                        </span>

                                    @else

                                        <span class="mt-1 inline-block rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                            En attente
                                        </span>

                                    @endif

                                </div>

                            </div>

                        @empty

                            <div class="px-4 py-6 text-sm text-center text-gray-500 dark:text-gray-400">
                                Aucun client n'a actuellement de dette.
                            </div>

                        @endforelse

                    </div>

                </div>


            {{-- ========================================================= --}}
            {{-- LECTEUR --}}
            {{-- ========================================================= --}}
            @elseif($dashboardRole === 'lecteur')

                {{-- Statistiques --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Produits
                        </p>

                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($totalProducts) }}
                        </p>
                    </div>

                    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Stock total
                        </p>

                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($totalStock) }}
                        </p>
                    </div>

                    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Stock faible
                        </p>

                        <p class="mt-1 text-2xl font-bold text-amber-600 dark:text-amber-400">
                            {{ number_format($lowStockProducts) }}
                        </p>
                    </div>
                </div>

                {{-- Dernières ventes --}}
                <div class="overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                            Dernières ventes
                        </h3>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($recentSales as $sale)
                            <div class="flex items-center justify-between gap-3 px-4 py-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        @foreach($sale->items as $item)
                                            {{ $item->product?->name ?? 'Produit supprimé' }} × {{ $item->quantity }}@if(!$loop->last), @endif
                                        @endforeach
                                    </p>

                                    <p class="text-xs text-gray-500 truncate dark:text-gray-400">
                                        {{ $sale->customer?->name ?? 'Client comptoir' }}
                                        · Vente #{{ $sale->id }}
                                    </p>
                                </div>

                                <div class="text-right shrink-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ number_format($sale->total, 0, ',', ' ') }} F
                                    </p>

                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $sale->sold_at?->format('d/m H:i') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-6 text-sm text-center text-gray-500 dark:text-gray-400">
                                Aucune vente récente.
                            </div>
                        @endforelse
                    </div>
                </div>

            @endif

        </div>
    </div>
</x-app-layout>
