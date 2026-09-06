<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Rapport financier
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Analysez les ventes, encaissements, crédits et remboursements.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Filtres --}}
            <div class="p-5 mb-6 bg-white border border-gray-200 shadow-sm rounded-xl">

                <form method="GET" action="{{ route('reports.financial') }}">

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
                                    href="{{ route('reports.financial.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                                    class="inline-flex items-center justify-center w-full px-4 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700"
                                >
                                    Exporter Excel
                                </a>
                            @endcan
                        </div>

                    </div>

                </form>

            </div>

            {{-- Statistiques principales --}}
            <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-5">

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Ventes
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalSales }}
                    </p>
                </div>

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Chiffre d'affaires
                    </p>

                    <p class="mt-2 text-xl font-bold text-indigo-600">
                        {{ number_format($totalSalesAmount, 0, ',', ' ') }} FCFA
                    </p>
                </div>

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Encaissé
                    </p>

                    <p class="mt-2 text-xl font-bold text-green-600">
                        {{ number_format($totalPaid, 0, ',', ' ') }} FCFA
                    </p>
                </div>

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Crédit / impayé
                    </p>

                    <p class="mt-2 text-xl font-bold text-orange-600">
                        {{ number_format($totalCredit, 0, ',', ' ') }} FCFA
                    </p>
                </div>

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Remboursements
                    </p>

                    <p class="mt-2 text-xl font-bold text-red-600">
                        {{ number_format($totalRefunds, 0, ',', ' ') }} FCFA
                    </p>
                </div>

            </div>

            {{-- Net encaissé --}}
            <div class="p-6 mb-6 bg-white border border-gray-200 shadow-sm rounded-xl">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Net encaissé
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Total des encaissements après déduction des remboursements.
                        </p>
                    </div>

                    <p class="text-2xl font-bold text-green-600">
                        {{ number_format($netPaid, 0, ',', ' ') }} FCFA
                    </p>

                </div>

            </div>

            {{-- Répartition des paiements --}}
            <div class="mb-6 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

                <div class="px-6 py-5 border-b border-gray-200">

                    <h3 class="text-lg font-semibold text-gray-900">
                        Répartition des encaissements
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Montants encaissés selon le moyen de paiement.
                    </p>

                </div>

                <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2 lg:grid-cols-4">

                    {{-- Espèces --}}
                    <div class="p-5 border border-gray-200 rounded-xl">

                        <p class="text-sm font-medium text-gray-500">
                            Espèces
                        </p>

                        <p class="mt-2 text-xl font-bold text-gray-900">
                            {{ number_format($cashPayments, 0, ',', ' ') }} FCFA
                        </p>

                    </div>

                    {{-- Mobile Money --}}
                    <div class="p-5 border border-gray-200 rounded-xl">

                        <p class="text-sm font-medium text-gray-500">
                            Mobile Money
                        </p>

                        <p class="mt-2 text-xl font-bold text-gray-900">
                            {{ number_format($mobileMoneyPayments, 0, ',', ' ') }} FCFA
                        </p>

                    </div>

                    {{-- Virement --}}
                    <div class="p-5 border border-gray-200 rounded-xl">

                        <p class="text-sm font-medium text-gray-500">
                            Virement bancaire
                        </p>

                        <p class="mt-2 text-xl font-bold text-gray-900">
                            {{ number_format($bankTransferPayments, 0, ',', ' ') }} FCFA
                        </p>

                    </div>

                    {{-- Carte --}}
                    <div class="p-5 border border-gray-200 rounded-xl">

                        <p class="text-sm font-medium text-gray-500">
                            Carte bancaire
                        </p>

                        <p class="mt-2 text-xl font-bold text-gray-900">
                            {{ number_format($cardPayments, 0, ',', ' ') }} FCFA
                        </p>

                    </div>

                </div>

            </div>

            {{-- Résumé financier --}}
            <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

                <div class="px-6 py-5 border-b border-gray-200">

                    <h3 class="text-lg font-semibold text-gray-900">
                        Résumé financier
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Synthèse de la situation financière sur la période sélectionnée.
                    </p>

                </div>

                <div class="divide-y divide-gray-200">

                    <div class="flex items-center justify-between px-6 py-4">
                        <span class="text-sm text-gray-600">
                            Chiffre d'affaires
                        </span>

                        <span class="text-sm font-semibold text-gray-900">
                            {{ number_format($totalSalesAmount, 0, ',', ' ') }} FCFA
                        </span>
                    </div>

                    <div class="flex items-center justify-between px-6 py-4">
                        <span class="text-sm text-gray-600">
                            Total encaissé
                        </span>

                        <span class="text-sm font-semibold text-green-600">
                            {{ number_format($totalPaid, 0, ',', ' ') }} FCFA
                        </span>
                    </div>

                    <div class="flex items-center justify-between px-6 py-4">
                        <span class="text-sm text-gray-600">
                            Crédit / impayé
                        </span>

                        <span class="text-sm font-semibold text-orange-600">
                            {{ number_format($totalCredit, 0, ',', ' ') }} FCFA
                        </span>
                    </div>

                    <div class="flex items-center justify-between px-6 py-4">
                        <span class="text-sm text-gray-600">
                            Remboursements
                        </span>

                        <span class="text-sm font-semibold text-red-600">
                            {{ number_format($totalRefunds, 0, ',', ' ') }} FCFA
                        </span>
                    </div>

                    <div class="flex items-center justify-between px-6 py-4 bg-gray-50">
                        <span class="text-sm font-semibold text-gray-900">
                            Net encaissé
                        </span>

                        <span class="text-lg font-bold text-green-600">
                            {{ number_format($netPaid, 0, ',', ' ') }} FCFA
                        </span>
                    </div>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>
