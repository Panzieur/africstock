<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between gap-2">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Produits
                </h2>

                <p class="text-sm text-gray-500">
                    Gérez votre catalogue et surveillez vos niveaux de stock.
                </p>
            </div>

            @can('products.create')
                <a
                    href="{{ route('products.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
                >
                    + Nouveau produit
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="px-4 mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">

            {{-- Message de succès --}}
            @if(session('success'))
                <div class="px-4 py-3 text-sm text-green-700 border border-green-200 rounded-lg bg-green-50">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Message d'erreur --}}
            @if(session('error'))
                <div class="px-4 py-3 text-sm text-red-700 border border-red-200 rounded-lg bg-red-50">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Tableau --}}
            <div class="overflow-hidden bg-white shadow-sm rounded-xl">

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

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Catégorie
                                </th>

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                    Prix de vente
                                </th>

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                    Stock
                                </th>

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-gray-500 uppercase">
                                    Statut
                                </th>

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                            @forelse($products as $product)

                                <tr class="hover:bg-gray-50">

                                    {{-- Produit --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">
                                            {{ $product->name }}
                                        </div>

                                        @if($product->description)
                                            <div class="max-w-xs text-sm text-gray-500 truncate">
                                                {{ $product->description }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- SKU --}}
                                    <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                        {{ $product->sku }}
                                    </td>

                                    {{-- Catégorie --}}
                                    <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                        {{ $product->category->name ?? 'Sans catégorie' }}
                                    </td>

                                    {{-- Prix --}}
                                    <td class="px-6 py-4 text-sm font-medium text-right text-gray-900 whitespace-nowrap">
                                        {{ number_format($product->selling_price, 0, ',', ' ') }}
                                        FCFA
                                    </td>

                                    {{-- Stock --}}
                                    <td class="px-6 py-4 text-right whitespace-nowrap">

                                        <div class="font-semibold
                                            @if($product->stock_quantity <= $product->minimum_stock)
                                                text-red-600
                                            @else
                                                text-gray-900
                                            @endif
                                        ">
                                            {{ $product->stock_quantity }}
                                            {{ $product->unit }}
                                        </div>

                                        @if($product->stock_quantity <= $product->minimum_stock)
                                            <span class="text-xs font-medium text-red-600">
                                                ⚠ Stock faible
                                            </span>
                                        @endif

                                    </td>

                                    {{-- Statut --}}
                                    <td class="px-6 py-4 text-center whitespace-nowrap">

                                        @if($product->is_active)

                                            <span class="inline-flex px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                                Actif
                                            </span>

                                        @else

                                            <span class="inline-flex px-3 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded-full">
                                                Inactif
                                            </span>

                                        @endif

                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-sm text-right whitespace-nowrap">

                                        <div class="flex justify-end gap-3">

                                            @can('products.edit')

                                                @if($product->is_active)

                                                    <form
                                                        method="POST"
                                                        action="{{ route('products.deactivate', $product) }}"
                                                        class="inline"
                                                        onsubmit="return confirm('Voulez-vous vraiment désactiver ce produit ?')"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            class="text-sm font-medium text-red-600 hover:text-red-800"
                                                        >
                                                            Désactiver
                                                        </button>
                                                    </form>

                                                @else

                                                    <form
                                                        method="POST"
                                                        action="{{ route('products.activate', $product) }}"
                                                        class="inline"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            class="text-sm font-medium text-green-600 hover:text-green-800"
                                                        >
                                                            Activer
                                                        </button>
                                                    </form>

                                                @endif

                                            @endcan

                                            @can('products.view')
                                                <a
                                                    href="{{ route('products.show', $product) }}"
                                                    class="font-medium text-indigo-600 hover:text-indigo-800"
                                                >
                                                    Voir
                                                </a>
                                            @endcan

                                            @can('products.edit')
                                                <a
                                                    href="{{ route('products.edit', $product) }}"
                                                    class="font-medium text-gray-600 hover:text-gray-900"
                                                >
                                                    Modifier
                                                </a>
                                            @endcan

                                            @can('products.delete')
                                                <form
                                                    method="POST"
                                                    action="{{ route('products.destroy', $product) }}"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="font-medium text-red-600 hover:text-red-800"
                                                    >
                                                        Supprimer
                                                    </button>
                                                </form>
                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">

                                        <div class="text-gray-500">
                                            <div class="mb-2 text-4xl">
                                                📦
                                            </div>

                                            <p class="font-medium text-gray-700">
                                                Aucun produit trouvé
                                            </p>

                                            <p class="mt-1 text-sm">
                                                Commencez par ajouter votre premier produit.
                                            </p>
                                        </div>

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>
                </div>

                {{-- Pagination --}}
                @if($products->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $products->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>

</x-app-layout>
