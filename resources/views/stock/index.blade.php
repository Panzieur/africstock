<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Gestion du stock
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Historique des mouvements de stock
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Message de succès --}}
            @if(session('success'))
                <div class="px-4 py-3 mb-6 text-sm font-medium text-green-700 border border-green-200 rounded-lg bg-green-50">
                    {{ session('success') }}
                </div>
            @endif

            {{-- En-tête --}}
            <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Mouvements de stock
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Consultez toutes les entrées, sorties et ajustements.
                    </p>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row">

                    @can('stock.entry')
                        <a
                            href="{{ route('stock.entry.create') }}"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-md hover:bg-green-700"
                        >
                            + Entrée
                        </a>
                    @endcan

                    @can('stock.exit')
                        <a
                            href="{{ route('stock.exit.create') }}"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-md hover:bg-red-700"
                        >
                            − Sortie
                        </a>
                    @endcan

                    @can('stock.adjust')
                        <a
                            href="{{ route('stock.adjustment.create') }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-yellow-600 rounded-md hover:bg-yellow-700"
                        >
                            Ajustement
                        </a>
                    @endcan

                </div>

            </div>

            {{-- Statistiques du stock --}}
            <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">

                {{-- Stock total --}}
                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Stock total
                            </p>

                            <p class="mt-2 text-2xl font-bold text-gray-900">
                                {{ number_format($totalStock, 0, ',', ' ') }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Unités en stock
                            </p>
                        </div>

                        <div class="p-3 rounded-lg bg-indigo-50">
                            <span class="text-xl">📦</span>
                        </div>
                    </div>
                </div>

                {{-- Entrées --}}
                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Entrées
                            </p>

                            <p class="mt-2 text-2xl font-bold text-green-600">
                                +{{ number_format($totalEntries, 0, ',', ' ') }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Quantité enregistrée
                            </p>
                        </div>

                        <div class="p-3 rounded-lg bg-green-50">
                            <span class="text-xl">↓</span>
                        </div>
                    </div>
                </div>

                {{-- Sorties --}}
                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Sorties
                            </p>

                            <p class="mt-2 text-2xl font-bold text-red-600">
                                −{{ number_format($totalExits, 0, ',', ' ') }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Quantité enregistrée
                            </p>
                        </div>

                        <div class="p-3 rounded-lg bg-red-50">
                            <span class="text-xl">↑</span>
                        </div>
                    </div>
                </div>

                {{-- Stock faible --}}
                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Stock faible
                            </p>

                            <p class="mt-2 text-2xl font-bold text-yellow-600">
                                {{ $lowStockProducts }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Produits à surveiller
                            </p>
                        </div>

                        <div class="p-3 rounded-lg bg-yellow-50">
                            <span class="text-xl">⚠️</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Filtres --}}
            <div class="p-5 mb-6 bg-white border border-gray-200 shadow-sm rounded-xl">

                <form method="GET" action="{{ route('stock.index') }}">

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">

                        {{-- Recherche --}}
                        <div>
                            <label
                                for="search"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Rechercher
                            </label>

                            <input
                                type="text"
                                name="search"
                                id="search"
                                value="{{ request('search') }}"
                                placeholder="Produit ou SKU..."
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        {{-- Type --}}
                        <div>
                            <label
                                for="type"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Type
                            </label>

                            <select
                                name="type"
                                id="type"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Tous</option>
                                <option value="entry" {{ request('type') === 'entry' ? 'selected' : '' }}>
                                    Entrées
                                </option>
                                <option value="exit" {{ request('type') === 'exit' ? 'selected' : '' }}>
                                    Sorties
                                </option>
                                <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>
                                    Ajustements
                                </option>
                            </select>
                        </div>

                        {{-- Utilisateur --}}
                        <div>
                            <label
                                for="user_id"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Utilisateur
                            </label>

                            <select
                                name="user_id"
                                id="user_id"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Tous</option>

                                @foreach($users as $user)
                                    <option
                                        value="{{ $user->id }}"
                                        {{ request('user_id') == $user->id ? 'selected' : '' }}
                                    >
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date début --}}
                        <div>
                            <label
                                for="date_from"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Date début
                            </label>

                            <input
                                type="date"
                                name="date_from"
                                id="date_from"
                                value="{{ request('date_from') }}"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        {{-- Date fin --}}
                        <div>
                            <label
                                for="date_to"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Date fin
                            </label>

                            <input
                                type="date"
                                name="date_to"
                                id="date_to"
                                value="{{ request('date_to') }}"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                    </div>

                    {{-- Boutons --}}
                    <div class="flex flex-wrap items-center gap-3 mt-5">

                        <button
                            type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
                        >
                            Filtrer
                        </button>

                        <a
                            href="{{ route('stock.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                        >
                            Réinitialiser
                        </a>

                    </div>

                </form>

            </div>

            {{-- Tableau --}}
            <div class="overflow-hidden bg-white shadow-sm rounded-xl">

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

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Quantité
                                </th>

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Stock avant
                                </th>

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Stock après
                                </th>

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Motif
                                </th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Utilisateur
                                </th>

                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                            @forelse($movements as $movement)

                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                        {{ $movement->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $movement->product->name }}
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            {{ $movement->product->sku }}
                                        </div>

                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        @if($movement->type === 'entry')

                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                                Entrée
                                            </span>

                                        @elseif($movement->type === 'exit')

                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                                                Sortie
                                            </span>

                                        @else

                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">
                                                Ajustement
                                            </span>

                                        @endif

                                    </td>

                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 whitespace-nowrap">

                                        @if($movement->type === 'entry')
                                            <span class="font-semibold text-green-600">
                                                +{{ $movement->quantity }}
                                            </span>
                                        @elseif($movement->type === 'exit')
                                            <span class="font-semibold text-red-600">
                                                −{{ $movement->quantity }}
                                            </span>
                                        @else
                                            @if($movement->quantity > 0)
                                                <span class="font-semibold text-yellow-600">
                                                    +{{ $movement->quantity }}
                                                </span>
                                            @else
                                                <span class="font-semibold text-yellow-600">
                                                    {{ $movement->quantity }}
                                                </span>
                                            @endif
                                        @endif

                                        {{ $movement->product->unit }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                        {{ $movement->stock_before }}
                                    </td>

                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 whitespace-nowrap">
                                        {{ $movement->stock_after }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 whitespace-nowrap">
                                        <span class="text-sm text-gray-600">
                                            {{ $movement->reason ?: '—' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                        {{ $movement->user->name }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">

                                        <p class="text-sm font-medium text-gray-900">
                                            Aucun mouvement de stock
                                        </p>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Les mouvements apparaîtront ici après les premières opérations.
                                        </p>

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                @if($movements->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $movements->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>

</x-app-layout>
