<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between gap-2">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $product->name }}
                </h2>

                <p class="text-sm text-gray-500">
                    Détails du produit
                </p>
            </div>

            <div class="flex gap-2">

                <a
                    href="{{ route('products.index') }}"
                    class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                >
                    Retour
                </a>

                @can('products.edit')
                    <a
                        href="{{ route('products.edit', $product) }}"
                        class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
                    >
                        Modifier
                    </a>
                @endcan

            </div>
        </div>
    </x-slot>

    <div class="py-6">

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

        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                {{-- Informations principales --}}
                <div class="p-4 bg-white shadow-sm rounded-xl">

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                Informations générales
                            </h3>

                            <p class="text-sm text-gray-500">
                                Informations principales du produit
                            </p>
                        </div>

                        @if($product->is_active)
                            <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                Actif
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded-full">
                                Inactif
                            </span>
                        @endif
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">

                        <div>
                            <p class="text-sm text-gray-500">
                                Nom du produit
                            </p>

                            <p class="mt-1 font-medium text-gray-900">
                                {{ $product->name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                SKU / Référence
                            </p>

                            <p class="mt-1 font-medium text-gray-900">
                                {{ $product->sku }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Catégorie
                            </p>

                            <p class="mt-1 font-medium text-gray-900">
                                {{ $product->category->name ?? 'Sans catégorie' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Unité
                            </p>

                            <p class="mt-1 font-medium text-gray-900">
                                {{ ucfirst($product->unit) }}
                            </p>
                        </div>

                    </div>

                    @if($product->description)

                        <div class="pt-6 mt-6 border-t">

                            <p class="text-sm text-gray-500">
                                Description
                            </p>

                            <p class="mt-1 text-gray-700">
                                {{ $product->description }}
                            </p>

                        </div>

                    @endif

                </div>


                {{-- Tarification --}}
                <div class="p-4 bg-white shadow-sm rounded-xl">

                    <h3 class="mb-6 text-lg font-semibold text-gray-800">
                        Tarification
                    </h3>

                    <div class="grid gap-6 md:grid-cols-2">

                        <div class="p-4 rounded-lg bg-gray-50">

                            <p class="text-sm text-gray-500">
                                Prix d'achat
                            </p>

                            <p class="mt-1 text-2xl font-bold text-gray-900">
                                {{ number_format($product->purchase_price, 0, ',', ' ') }}
                                FCFA
                            </p>

                        </div>

                        <div class="p-4 rounded-lg bg-gray-50">

                            <p class="text-sm text-gray-500">
                                Prix de vente
                            </p>

                            <p class="mt-1 text-2xl font-bold text-indigo-600">
                                {{ number_format($product->selling_price, 0, ',', ' ') }}
                                FCFA
                            </p>

                        </div>

                    </div>

                    {{-- Marge --}}
                    <div class="pt-6 mt-6 border-t">

                        <p class="text-sm text-gray-500">
                            Marge unitaire
                        </p>

                        <p class="mt-1 text-xl font-semibold text-gray-900">
                            {{ number_format($product->selling_price - $product->purchase_price, 0, ',', ' ') }}
                            FCFA
                        </p>

                    </div>

                </div>


                {{-- Stock --}}
                <div class="p-4 bg-white shadow-sm rounded-xl">

                    <h3 class="mb-6 text-lg font-semibold text-gray-800">
                        Gestion du stock
                    </h3>

                    <div class="grid gap-6 md:grid-cols-3">

                        {{-- Stock actuel --}}
                        <div class="p-4 rounded-lg bg-gray-50">

                            <p class="text-sm text-gray-500">
                                Stock actuel
                            </p>

                            <p class="mt-1 text-2xl font-bold
                                @if($product->stock_quantity <= $product->minimum_stock)
                                    text-red-600
                                @else
                                    text-gray-900
                                @endif
                            ">
                                {{ $product->stock_quantity }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $product->unit }}
                            </p>

                        </div>

                        {{-- Stock minimum --}}
                        <div class="p-4 rounded-lg bg-gray-50">

                            <p class="text-sm text-gray-500">
                                Stock minimum
                            </p>

                            <p class="mt-1 text-2xl font-bold text-gray-900">
                                {{ $product->minimum_stock }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $product->unit }}
                            </p>

                        </div>

                        {{-- État du stock --}}
                        <div class="rounded-lg p-4
                            @if($product->stock_quantity <= $product->minimum_stock)
                                bg-red-50
                            @else
                                bg-green-50
                            @endif
                        ">

                            <p class="text-sm text-gray-500">
                                État du stock
                            </p>

                            @if($product->stock_quantity <= $product->minimum_stock)

                                <p class="mt-1 font-bold text-red-600">
                                    ⚠ Stock faible
                                </p>

                                <p class="text-sm text-red-500">
                                    Réapprovisionnement recommandé
                                </p>

                            @else

                                <p class="mt-1 font-bold text-green-600">
                                    ✓ Stock normal
                                </p>

                                <p class="text-sm text-green-500">
                                    Niveau de stock satisfaisant
                                </p>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Informations système --}}
                <div class="p-4 bg-white shadow-sm rounded-xl lg:col-span-3">

                    <h3 class="mb-6 text-lg font-semibold text-gray-800">
                        Informations système
                    </h3>

                    <div class="grid gap-6 md:grid-cols-2">

                        <div>
                            <p class="text-sm text-gray-500">
                                Créé le
                            </p>

                            <p class="mt-1 text-gray-900">
                                {{ $product->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Dernière modification
                            </p>

                            <p class="mt-1 text-gray-900">
                                {{ $product->updated_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>
