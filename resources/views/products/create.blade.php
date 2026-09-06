<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Nouveau produit
            </h2>

            <p class="text-sm text-gray-500">
                Ajoutez un nouveau produit à votre catalogue.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl px-4 mx-auto sm:px-6 lg:px-8">

            <div class="p-6 bg-white shadow-sm rounded-xl">

                <form method="POST" action="{{ route('products.store') }}">
                    @csrf

                    {{-- Informations générales --}}
                    <div class="mb-8">
                        <h3 class="mb-4 text-lg font-semibold text-gray-800">
                            Informations générales
                        </h3>

                        <div class="grid gap-6 md:grid-cols-2">

                            {{-- Nom --}}
                            <div>
                                <label for="name"
                                       class="block text-sm font-medium text-gray-700">
                                    Nom du produit *
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    value="{{ old('name') }}"
                                    required
                                    placeholder="Ex : Coca-Cola 33cl"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- SKU --}}
                            <div>
                                <label for="sku"
                                       class="block text-sm font-medium text-gray-700">
                                    SKU / Référence *
                                </label>

                                <input
                                    type="text"
                                    name="sku"
                                    id="sku"
                                    value="{{ old('sku') }}"
                                    required
                                    placeholder="Ex : COC-33CL"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                                @error('sku')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Catégorie --}}
                            <div>
                                <label for="category_id"
                                       class="block text-sm font-medium text-gray-700">
                                    Catégorie *
                                </label>

                                <select
                                    name="category_id"
                                    id="category_id"
                                    required
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="">
                                        Sélectionner une catégorie
                                    </option>

                                    @foreach($categories as $category)
                                        <option
                                            value="{{ $category->id }}"
                                            @selected(old('category_id') == $category->id)
                                        >
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                                @if($categories->isEmpty())
                                    <p class="mt-1 text-sm text-amber-600">
                                        Aucune catégorie active disponible.
                                    </p>
                                @endif
                            </div>

                            {{-- Unité --}}
                            <div>
                                <label for="unit"
                                       class="block text-sm font-medium text-gray-700">
                                    Unité *
                                </label>

                                <select
                                    name="unit"
                                    id="unit"
                                    required
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="piece" @selected(old('unit', 'piece') === 'piece')>
                                        Pièce
                                    </option>

                                    <option value="bouteille" @selected(old('unit') === 'bouteille')>
                                        Bouteille
                                    </option>

                                    <option value="carton" @selected(old('unit') === 'carton')>
                                        Carton
                                    </option>

                                    <option value="sac" @selected(old('unit') === 'sac')>
                                        Sac
                                    </option>

                                    <option value="kg" @selected(old('unit') === 'kg')>
                                        Kilogramme
                                    </option>

                                    <option value="litre" @selected(old('unit') === 'litre')>
                                        Litre
                                    </option>
                                </select>

                                @error('unit')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>

                        {{-- Description --}}
                        <div class="mt-6">
                            <label for="description"
                                   class="block text-sm font-medium text-gray-700">
                                Description
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                rows="4"
                                placeholder="Description du produit..."
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Prix --}}
                    <div class="mb-8">
                        <h3 class="mb-4 text-lg font-semibold text-gray-800">
                            Tarification
                        </h3>

                        <div class="grid gap-6 md:grid-cols-2">

                            {{-- Prix achat --}}
                            <div>
                                <label for="purchase_price"
                                       class="block text-sm font-medium text-gray-700">
                                    Prix d'achat (FCFA) *
                                </label>

                                <input
                                    type="number"
                                    name="purchase_price"
                                    id="purchase_price"
                                    value="{{ old('purchase_price', 0) }}"
                                    min="0"
                                    step="0.01"
                                    required
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                                @error('purchase_price')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Prix vente --}}
                            <div>
                                <label for="selling_price"
                                       class="block text-sm font-medium text-gray-700">
                                    Prix de vente (FCFA) *
                                </label>

                                <input
                                    type="number"
                                    name="selling_price"
                                    id="selling_price"
                                    value="{{ old('selling_price', 0) }}"
                                    min="0"
                                    step="0.01"
                                    required
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                                @error('selling_price')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Stock --}}
                    <div class="mb-8">
                        <h3 class="mb-4 text-lg font-semibold text-gray-800">
                            Gestion du stock
                        </h3>

                        <div class="grid gap-6 md:grid-cols-2">

                            {{-- Stock initial --}}
                            <div>
                                <label for="stock_quantity"
                                       class="block text-sm font-medium text-gray-700">
                                    Stock initial *
                                </label>

                                <input
                                    type="number"
                                    name="stock_quantity"
                                    id="stock_quantity"
                                    value="{{ old('stock_quantity', 0) }}"
                                    min="0"
                                    required
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                                @error('stock_quantity')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Stock minimum --}}
                            <div>
                                <label for="minimum_stock"
                                       class="block text-sm font-medium text-gray-700">
                                    Stock minimum *
                                </label>

                                <input
                                    type="number"
                                    name="minimum_stock"
                                    id="minimum_stock"
                                    value="{{ old('minimum_stock', 0) }}"
                                    min="0"
                                    required
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >

                                @error('minimum_stock')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                                <p class="mt-1 text-xs text-gray-500">
                                    Une alerte sera affichée lorsque le stock atteindra ce niveau.
                                </p>
                            </div>

                        </div>
                    </div>

                    {{-- Statut --}}
                    <div class="mb-8">
                        <label class="inline-flex items-center">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                @checked(old('is_active', true))
                                class="text-indigo-600 border-gray-300 rounded shadow-sm focus:ring-indigo-500"
                            >

                            <span class="ml-2 text-sm text-gray-700">
                                Produit actif
                            </span>
                        </label>
                    </div>

                    {{-- Boutons --}}
                    <div class="flex items-center justify-end gap-3 pt-6 border-t">

                        <a
                            href="{{ route('products.index') }}"
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                        >
                            Annuler
                        </a>

                        <button
                            type="submit"
                            class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
                        >
                            Enregistrer le produit
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>
