<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Rapport des ventes
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Analyse des ventes et des encaissements sur une période donnée.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Messages --}}
            @if(session('success'))
                <div class="px-4 py-3 mb-6 border border-green-200 rounded-lg bg-green-50">
                    <p class="text-sm font-medium text-green-700">
                        {{ session('success') }}
                    </p>
                </div>
            @endif

            {{-- Filtre période --}}
            <div class="p-5 mb-6 bg-white border border-gray-200 shadow-sm rounded-xl">

                <form method="GET" action="{{ route('reports.sales') }}">

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                        <div>
                            <label
                                for="start_date"
                                class="block mb-1 text-sm font-medium text-gray-700"
                            >
                                Date de début
                            </label>

                            <input
                                type="date"
                                id="start_date"
                                name="start_date"
                                value="{{ $startDate }}"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div>
                            <label
                                for="end_date"
                                class="block mb-1 text-sm font-medium text-gray-700"
                            >
                                Date de fin
                            </label>

                            <input
                                type="date"
                                id="end_date"
                                name="end_date"
                                value="{{ $endDate }}"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div class="flex items-end gap-2">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center w-full px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
                            >
                                Filtrer
                            </button>

                            @can('reports.export')
                                <a
                                    href="{{ route('reports.sales.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                                    class="inline-flex items-center justify-center w-full px-4 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700"
                                >
                                    Exporter Excel
                                </a>
                            @endcan
                        </div>

                    </div>

                </form>

            </div>

            {{-- Statistiques --}}
            <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-5">

                {{-- Ventes --}}
                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Ventes terminées
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalSales }}
                    </p>
                </div>

                {{-- Chiffre d'affaires --}}
                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Chiffre d'affaires
                    </p>

                    <p class="mt-2 text-xl font-bold text-indigo-600">
                        {{ number_format($totalAmount, 0, ',', ' ') }} FCFA
                    </p>
                </div>

                {{-- Encaissé --}}
                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Encaissé
                    </p>

                    <p class="mt-2 text-xl font-bold text-green-600">
                        {{ number_format($totalPaid, 0, ',', ' ') }} FCFA
                    </p>
                </div>

                {{-- Crédit --}}
                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Crédit / impayé
                    </p>

                    <p class="mt-2 text-xl font-bold text-orange-600">
                        {{ number_format($totalCredit, 0, ',', ' ') }} FCFA
                    </p>
                </div>

                {{-- Annulées --}}
                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Ventes annulées
                    </p>

                    <p class="mt-2 text-2xl font-bold text-red-600">
                        {{ $cancelledCount }}
                    </p>
                </div>

            </div>

            {{-- Tableau des ventes --}}
            <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Détail des ventes
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Ventes enregistrées du
                        {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                        au
                        {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}.
                    </p>
                </div>

                @if($sales->count())

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">
                                <tr>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Référence
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Client
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Vendeur
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Total
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Encaissé
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Reste
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Statut
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                        Action
                                    </th>

                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">

                                @foreach($sales as $sale)

                                    @php
                                        $paid = (float) ($sale->payments_sum_amount ?? 0);
                                        $remaining = max(0, (float) $sale->total - $paid);
                                    @endphp

                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            {{ $sale->reference }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                            {{ $sale->customer?->name ?? 'Vente comptoir' }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                            {{ $sale->user?->name ?? '—' }}
                                        </td>

                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 whitespace-nowrap">
                                            {{ number_format($sale->total, 0, ',', ' ') }} FCFA
                                        </td>

                                        <td class="px-6 py-4 text-sm font-medium text-green-600 whitespace-nowrap">
                                            {{ number_format($paid, 0, ',', ' ') }} FCFA
                                        </td>

                                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap
                                            {{ $remaining > 0 ? 'text-orange-600' : 'text-gray-500' }}"
                                        >
                                            {{ number_format($remaining, 0, ',', ' ') }} FCFA
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @if($sale->status === 'completed')

                                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                                    Terminée
                                                </span>

                                            @else

                                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">
                                                    Annulée
                                                </span>

                                            @endif

                                        </td>

                                        <td class="px-6 py-4 text-right whitespace-nowrap">

                                            <a
                                                href="{{ route('sales.show', $sale) }}"
                                                class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                                            >
                                                Voir
                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    {{-- Pagination --}}
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $sales->links() }}
                    </div>

                @else

                    <div class="px-6 py-12 text-center">

                        <p class="text-sm text-gray-500">
                            Aucune vente trouvée pour cette période.
                        </p>

                    </div>

                @endif

            </div>

        </div>
    </div>

</x-app-layout>
