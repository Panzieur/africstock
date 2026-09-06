<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ $inventory->name }}
                    </h2>

                    @if($inventory->status === 'draft')
                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">
                            Brouillon
                        </span>
                    @elseif($inventory->status === 'in_progress')
                        <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">
                            En cours
                        </span>
                    @elseif($inventory->status === 'completed')
                        <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                            Terminé
                        </span>
                    @elseif($inventory->status === 'cancelled')
                        <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                            Annulé
                        </span>
                    @endif
                </div>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $inventory->reference }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">

                @if($inventory->status === 'in_progress')

                    <form
                        method="POST"
                        action="{{ route('inventories.complete', $inventory) }}"
                        onsubmit="return confirm('Êtes-vous sûr de vouloir valider cet inventaire ? Les écarts seront appliqués au stock.')"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700"
                        >
                            ✓ Valider l'inventaire
                        </button>
                    </form>

                @endif

                <a
                    href="{{ route('inventories.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    ← Retour
                </a>

            </div>
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

            {{-- Informations --}}
            <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm text-gray-500">
                        Référence
                    </p>

                    <p class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $inventory->reference }}
                    </p>
                </div>

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm text-gray-500">
                        Créé par
                    </p>

                    <p class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $inventory->user->name }}
                    </p>
                </div>

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm text-gray-500">
                        Produits
                    </p>

                    <p class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $inventory->items->count() }}
                    </p>
                </div>

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm text-gray-500">
                        Créé le
                    </p>

                    <p class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $inventory->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

            </div>

            {{-- Notes --}}
            @if($inventory->notes)
                <div class="p-6 mb-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <h3 class="text-base font-semibold text-gray-900">
                        Notes
                    </h3>

                    <p class="mt-2 text-sm text-gray-600 whitespace-pre-line">
                        {{ $inventory->notes }}
                    </p>
                </div>
            @endif



            <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">

                {{-- Produits --}}
                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Produits
                    </p>

                    <p class="mt-2 text-xl font-bold text-gray-900">
                        {{ $totalProducts }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        Produits dans l'inventaire
                    </p>
                </div>

                {{-- Comptés --}}
                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Produits comptés
                    </p>

                    <p class="mt-2 text-xl font-bold text-indigo-600">
                        {{ $countedProducts }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        Sur {{ $totalProducts }} produits
                    </p>
                </div>

                {{-- Écarts --}}
                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Produits avec écart
                    </p>

                    <p class="mt-2 text-xl font-bold text-orange-600">
                        {{ $productsWithDifference }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        Nécessitent une vérification
                    </p>
                </div>

                {{-- Écart total --}}
                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Écart total
                    </p>

                    <p class="mt-2 text-xl font-bold
                        {{ $totalDifference > 0 ? 'text-green-600' : ($totalDifference < 0 ? 'text-red-600' : 'text-gray-900') }}">
                        {{ $totalDifference > 0 ? '+' : '' }}{{ $totalDifference }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        Différence de stock
                    </p>
                </div>

            </div>


            @if($inventory->status === 'completed')
                <div class="px-4 py-3 mb-6 border border-green-200 rounded-lg bg-green-50">
                    <p class="text-sm font-semibold text-green-800">
                        ✓ Inventaire terminé
                    </p>

                    <p class="mt-1 text-sm text-green-700">
                        Cet inventaire a été validé et les écarts ont été appliqués au stock.
                        Le comptage n'est plus modifiable.
                    </p>
                </div>
            @endif

            {{-- Produits --}}
            <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">
                        Comptage des produits
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Saisissez la quantité réellement comptée pour chaque produit.
                    </p>
                </div>

                @if($inventory->items->count())

                    <form
                        method="POST"
                        action="{{ route('inventories.items.update', $inventory) }}"
                    >
                        @csrf
                        @method('PUT')

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">

                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                            Produit
                                        </th>

                                        <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                            SKU
                                        </th>

                                        <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                            Stock théorique
                                        </th>

                                        <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                            Stock réel
                                        </th>

                                        <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                            Écart
                                        </th>

                                        <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                            Note
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">

                                    @foreach($inventory->items as $item)

                                        <tr class="hover:bg-gray-50">

                                            {{-- Produit --}}
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="font-medium text-gray-900">
                                                    {{ $item->product->name }}
                                                </span>
                                            </td>

                                            {{-- SKU --}}
                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                {{ $item->product->sku }}
                                            </td>

                                            {{-- Stock théorique --}}
                                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                                <span class="font-semibold text-gray-700">
                                                    {{ $item->stock_theoretical }}
                                                </span>
                                            </td>

                                            {{-- Stock réel --}}
                                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                                <input
                                                    type="number"
                                                    name="items[{{ $item->id }}][stock_actual]"
                                                    value="{{ old('items.' . $item->id . '.stock_actual', $item->stock_actual) }}"
                                                    min="0"
                                                    class="text-right border-gray-300 rounded-lg shadow-sm w-28 focus:border-indigo-500 focus:ring-indigo-500"
                                                >
                                            </td>

                                            {{-- Écart --}}
                                            <td class="px-6 py-4 text-right whitespace-nowrap">

                                                @if(is_null($item->difference))

                                                    <span class="text-sm text-gray-400">
                                                        —
                                                    </span>

                                                @elseif($item->difference > 0)

                                                    <span class="font-semibold text-green-600">
                                                        +{{ $item->difference }}
                                                    </span>

                                                @elseif($item->difference < 0)

                                                    <span class="font-semibold text-red-600">
                                                        {{ $item->difference }}
                                                    </span>

                                                @else

                                                    <span class="font-semibold text-gray-600">
                                                        0
                                                    </span>

                                                @endif

                                            </td>

                                            {{-- Note --}}
                                            <td class="px-6 py-4">
                                                <input
                                                    type="text"
                                                    name="items[{{ $item->id }}][notes]"
                                                    value="{{ old('items.' . $item->id . '.notes', $item->notes) }}"
                                                    placeholder="Observation..."
                                                    class="w-48 text-sm border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                >
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>
                        </div>

                        {{-- Actions --}}
                        @if($inventory->status === 'in_progress')

                            <div class="flex items-center justify-end px-6 py-4 border-t border-gray-200">
                                <button
                                    type="submit"
                                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                                >
                                    Enregistrer le comptage
                                </button>
                            </div>

                        @endif

                    </form>

                @else

                    <div class="px-6 py-12 text-center">

                        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-gray-100 rounded-full">
                            <span class="text-xl text-gray-500">📦</span>
                        </div>

                        <h3 class="mt-4 text-sm font-semibold text-gray-900">
                            Aucun produit
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Aucun produit n'a encore été ajouté à cet inventaire.
                        </p>

                    </div>

                @endif

            </div>

        </div>
    </div>

</x-app-layout>
