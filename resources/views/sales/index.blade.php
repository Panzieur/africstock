<x-app-layout>
<x-slot name="header">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Ventes
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Consultez et gérez l'historique des ventes.
            </p>
        </div>

        @can('sales.create')
            <a
                href="{{ route('sales.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
            >
                + Nouvelle vente
            </a>
        @endcan
    </div>
</x-slot>

<div class="py-8">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

        {{-- Messages --}}
        @if(session('success'))
            <div class="px-4 py-3 mb-6 text-sm font-medium text-green-700 border border-green-200 rounded-lg bg-green-50">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="px-4 py-3 mb-6 text-sm font-medium text-red-700 border border-red-200 rounded-lg bg-red-50">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tableau --}}
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900">
                    Historique des ventes
                </h3>
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
                                    Produits
                                </th>

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Total
                                </th>

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Paiement
                                </th>

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Vendeur
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

                                <tr class="hover:bg-gray-50">

                                    {{-- Référence --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-semibold text-gray-900">
                                            {{ $sale->reference }}
                                        </span>

                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $sale->sold_at ? $sale->sold_at->format('d/m/Y H:i') : $sale->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </td>

                                    {{-- Client --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-700">
                                            {{ $sale->customer?->name ?? 'Vente comptoir' }}
                                        </span>
                                    </td>

                                    {{-- Produits --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-700">
                                            {{ $sale->items_count }}
                                        </span>
                                    </td>

                                    {{-- Total --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-gray-900">
                                            {{ number_format($sale->total, 0, ',', ' ') }} FCFA
                                        </span>
                                    </td>

                                    {{-- Paiement / Crédit --}}
                                    <td class="px-6 py-4 whitespace-nowrap">

                                        @if($sale->status === 'cancelled')

                                            <span class="text-sm text-gray-400">
                                                —
                                            </span>

                                        @elseif($sale->payment_status === 'paid')

                                            <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                                Payée
                                            </span>

                                        @elseif($sale->remaining_amount > 0 && $sale->credit_due_date)

                                            @if($sale->credit_due_date->isPast())

                                                <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                                                    Crédit en retard
                                                </span>

                                                <p class="mt-1 text-xs font-medium text-red-600">
                                                    Reste :
                                                    {{ number_format($sale->remaining_amount, 0, ',', ' ') }} FCFA
                                                </p>

                                                <p class="mt-1 text-xs text-red-500">
                                                    Échéance :
                                                    {{ $sale->credit_due_date->format('d/m/Y') }}
                                                </p>

                                            @else

                                                <span class="inline-flex rounded-full bg-orange-100 px-2.5 py-1 text-xs font-semibold text-orange-700">
                                                    Crédit en cours
                                                </span>

                                                <p class="mt-1 text-xs font-medium text-orange-700">
                                                    Reste :
                                                    {{ number_format($sale->remaining_amount, 0, ',', ' ') }} FCFA
                                                </p>

                                                <p class="mt-1 text-xs text-gray-500">
                                                    Échéance :
                                                    {{ $sale->credit_due_date->format('d/m/Y') }}
                                                </p>

                                            @endif

                                        @elseif($sale->remaining_amount > 0)

                                            <span class="inline-flex rounded-full bg-orange-100 px-2.5 py-1 text-xs font-semibold text-orange-700">
                                                Crédit
                                            </span>

                                            <p class="mt-1 text-xs text-orange-700">
                                                Reste :
                                                {{ number_format($sale->remaining_amount, 0, ',', ' ') }} FCFA
                                            </p>

                                        @else

                                            <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                                Payée
                                            </span>

                                        @endif

                                    </td>

                                    {{-- Vendeur --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-700">
                                            {{ $sale->user->name }}
                                        </span>
                                    </td>

                                    {{-- Statut --}}
                                    <td class="px-6 py-4 whitespace-nowrap">

                                        @if($sale->status === 'completed')

                                            <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                                Terminée
                                            </span>

                                        @elseif($sale->status === 'cancelled')

                                            <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                                                Annulée
                                            </span>

                                        @endif

                                    </td>

                                    {{-- Action --}}
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

                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-gray-100 rounded-full">
                        <span class="text-xl text-gray-500">🛒</span>
                    </div>

                    <h3 class="mt-4 text-sm font-semibold text-gray-900">
                        Aucune vente
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Vous n'avez encore enregistré aucune vente.
                    </p>

                    @can('sales.create')
                        <a
                            href="{{ route('sales.create') }}"
                            class="inline-flex items-center px-4 py-2 mt-4 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
                        >
                            Créer une vente
                        </a>
                    @endcan

                </div>

            @endif

        </div>

    </div>
</div>

</x-app-layout>
