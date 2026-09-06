<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Rapport du stock
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Suivez les niveaux de stock et les mouvements sur une période donnée.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Filtres --}}
            <div class="p-5 mb-6 bg-white border border-gray-200 shadow-sm rounded-xl">

                <form method="GET" action="{{ route('reports.stock') }}">

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
                                    href="{{ route('reports.stock.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
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

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Produits
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalProducts }}
                    </p>
                </div>

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Quantité en stock
                    </p>

                    <p class="mt-2 text-2xl font-bold text-indigo-600">
                        {{ number_format($totalStockQuantity, 0, ',', ' ') }}
                    </p>
                </div>

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Valeur du stock
                    </p>

                    <p class="mt-2 text-xl font-bold text-green-600">
                        {{ number_format($totalStockValue, 0, ',', ' ') }} FCFA
                    </p>
                </div>

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Entrées
                    </p>

                    <p class="mt-2 text-2xl font-bold text-green-600">
                        +{{ number_format($entries, 0, ',', ' ') }}
                    </p>
                </div>

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Sorties
                    </p>

                    <p class="mt-2 text-2xl font-bold text-red-600">
                        -{{ number_format($exits, 0, ',', ' ') }}
                    </p>
                </div>

            </div>

            {{-- Produits en stock faible --}}
            <div class="mb-6 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

                <div class="px-6 py-5 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Produits en stock faible
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Produits dont le stock est inférieur ou égal au seuil minimum.
                            </p>
                        </div>

                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-orange-700 bg-orange-100 rounded-full">
                            {{ $lowStockProducts->count() }} produit(s)
                        </span>
                    </div>
                </div>

                @if($lowStockProducts->count())

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Produit
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Catégorie
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                        Stock actuel
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                        Stock minimum
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">

                                @foreach($lowStockProducts as $product)

                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            {{ $product->name }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $product->category?->name ?? '—' }}
                                        </td>

                                        <td class="px-6 py-4 text-sm font-semibold text-right text-orange-600">
                                            {{ number_format($product->stock_quantity, 0, ',', ' ') }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-right text-gray-600">
                                            {{ number_format($product->minimum_stock, 0, ',', ' ') }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="px-6 py-10 text-center">
                        <p class="text-sm text-green-600">
                            Aucun produit n'est actuellement en stock faible.
                        </p>
                    </div>

                @endif

            </div>

            {{-- Historique des mouvements --}}
            <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

                <div class="px-6 py-5 border-b border-gray-200">

                    <h3 class="text-lg font-semibold text-gray-900">
                        Historique des mouvements
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Mouvements enregistrés du
                        {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                        au
                        {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}.
                    </p>

                </div>

                @if($movements->count())

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Date
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Produit
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Type
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                        Quantité
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                        Stock après
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Utilisateur
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Motif
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">

                                @foreach($movements as $movement)

                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                            {{ $movement->created_at?->format('d/m/Y H:i') }}
                                        </td>

                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            {{ $movement->product?->name ?? '—' }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @if($movement->type === 'entry')

                                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                                    Entrée
                                                </span>

                                            @elseif($movement->type === 'exit')

                                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">
                                                    Sortie
                                                </span>

                                            @else

                                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-orange-700 bg-orange-100 rounded-full">
                                                    Ajustement
                                                </span>

                                            @endif

                                        </td>

                                        <td class="px-6 py-4 text-sm font-semibold text-right whitespace-nowrap
                                            {{ $movement->type === 'entry' ? 'text-green-600' : ($movement->type === 'exit' ? 'text-red-600' : 'text-orange-600') }}"
                                        >
                                            {{ $movement->type === 'entry' ? '+' : ($movement->type === 'exit' ? '-' : '') }}{{ number_format(abs($movement->quantity), 0, ',', ' ') }}
                                        </td>

                                        <td class="px-6 py-4 text-sm font-medium text-right text-gray-900 whitespace-nowrap">
                                            {{ number_format($movement->stock_after, 0, ',', ' ') }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                            {{ $movement->user?->name ?? '—' }}
                                        </td>

                                        <td class="max-w-xs px-6 py-4 text-sm text-gray-600">
                                            {{ $movement->reason ?? '—' }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $movements->links() }}
                    </div>

                @else

                    <div class="px-6 py-12 text-center">
                        <p class="text-sm text-gray-500">
                            Aucun mouvement de stock trouvé pour cette période.
                        </p>
                    </div>

                @endif

            </div>

        </div>
    </div>

</x-app-layout>
