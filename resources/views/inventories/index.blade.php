<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Inventaires
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Gérez vos inventaires physiques et contrôlez les écarts de stock.
                </p>
            </div>

            @can('stock.inventory')
                <a
                    href="{{ route('inventories.create') }}"
                    class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                >
                    + Nouvel inventaire
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

                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">

                    <form
                        method="GET"
                        action="{{ route('inventories.index') }}"
                        class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
                    >

                        {{-- Recherche --}}
                        <div>
                            <label
                                for="search"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Recherche
                            </label>

                            <input
                                type="text"
                                name="search"
                                id="search"
                                value="{{ request('search') }}"
                                placeholder="Référence ou nom..."
                                class="block w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        {{-- Statut --}}
                        <div>
                            <label
                                for="status"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Statut
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="block w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Tous les statuts</option>

                                <option
                                    value="draft"
                                    @selected(request('status') === 'draft')
                                >
                                    Brouillon
                                </option>

                                <option
                                    value="in_progress"
                                    @selected(request('status') === 'in_progress')
                                >
                                    En cours
                                </option>

                                <option
                                    value="completed"
                                    @selected(request('status') === 'completed')
                                >
                                    Terminé
                                </option>

                                <option
                                    value="cancelled"
                                    @selected(request('status') === 'cancelled')
                                >
                                    Annulé
                                </option>
                            </select>
                        </div>

                        {{-- Boutons --}}
                        <div class="flex items-end gap-2">

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center px-3 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
                            >
                                Rechercher
                            </button>

                            <a
                                href="{{ route('inventories.index') }}"
                                class="inline-flex items-center justify-center px-3 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                            >
                                Réinitialiser
                            </a>

                        </div>

                    </form>

                </div>

                @if($inventories->count())

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Référence
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Nom
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Produits
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Créé par
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Début
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Validation
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

                                @foreach($inventories as $inventory)

                                    <tr class="hover:bg-gray-50">

                                        {{-- Référence --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-semibold text-gray-900">
                                                {{ $inventory->reference }}
                                            </span>
                                        </td>

                                        {{-- Nom --}}
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-700">
                                                {{ $inventory->name }}
                                            </span>
                                        </td>

                                        {{-- Nombre de produits --}}
                                        {{-- Progression --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="w-40">

                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="text-sm font-medium text-gray-700">
                                                        {{ $inventory->counted_items_count }} / {{ $inventory->items_count }}
                                                    </span>

                                                    @php
                                                        $progress = $inventory->items_count > 0
                                                            ? round(($inventory->counted_items_count / $inventory->items_count) * 100)
                                                            : 0;
                                                    @endphp

                                                    <span class="text-xs font-semibold text-gray-500">
                                                        {{ $progress }}%
                                                    </span>
                                                </div>

                                                <div class="w-full h-2 overflow-hidden bg-gray-200 rounded-full">
                                                    <div
                                                        class="h-2 bg-indigo-600 rounded-full"
                                                        style="width: {{ $progress }}%"
                                                    ></div>
                                                </div>

                                            </div>
                                        </td>

                                        {{-- Utilisateur --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-700">
                                                {{ $inventory->user->name }}
                                            </span>
                                        </td>

                                        {{-- Date de début --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-700">
                                                {{ $inventory->started_at ? $inventory->started_at->format('d/m/Y H:i') : '—' }}
                                            </span>
                                        </td>

                                        {{-- Date de validation --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-700">
                                                {{ $inventory->completed_at ? $inventory->completed_at->format('d/m/Y H:i') : '—' }}
                                            </span>
                                        </td>

                                        {{-- Statut --}}
                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @if($inventory->status === 'draft')

                                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">
                                                    Brouillon
                                                </span>

                                            @elseif($inventory->status === 'in_progress')

                                                <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                                    En cours
                                                </span>

                                            @elseif($inventory->status === 'completed')

                                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                                    Terminé
                                                </span>

                                            @elseif($inventory->status === 'cancelled')

                                                <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                                                    Annulé
                                                </span>

                                            @endif

                                        </td>

                                        {{-- Action --}}
                                        <td class="px-6 py-4 text-right whitespace-nowrap">

                                            <a
                                                href="{{ route('inventories.show', $inventory) }}"
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
                        {{ $inventories->links() }}
                    </div>

                @else

                    <div class="px-6 py-12 text-center">

                        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-gray-100 rounded-full">
                            <span class="text-xl text-gray-500">📦</span>
                        </div>

                        <h3 class="mt-4 text-sm font-semibold text-gray-900">
                            Aucun inventaire
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Vous n'avez encore créé aucun inventaire.
                        </p>

                        @can('stock.inventory')
                            <a
                                href="{{ route('inventories.create') }}"
                                class="inline-flex items-center px-4 py-2 mt-4 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
                            >
                                Créer un inventaire
                            </a>
                        @endcan

                    </div>

                @endif

            </div>

        </div>
    </div>

</x-app-layout>
