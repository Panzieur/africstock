<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Modifier le produit
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $product->name }}
                </p>
            </div>

            <a
                href="{{ route('products.show', $product) }}"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-gray-700 transition bg-gray-100 rounded-md hover:bg-gray-200"
            >
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl px-4 mx-auto sm:px-6 lg:px-8">

            <div class="p-6 bg-white shadow-sm rounded-xl">

                <form
                    method="POST"
                    action="{{ route('products.update', $product) }}"
                    class="space-y-6"
                >
                    @csrf
                    @method('PUT')

                    {{-- Nom --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nom du produit
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name', $product->name) }}"
                            required
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- SKU + Catégorie --}}
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700">
                                SKU / Référence
                            </label>

                            <input
                                type="text"
                                name="sku"
                                id="sku"
                                value="{{ old('sku', $product->sku) }}"
                                required
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('sku')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">
                                Catégorie
                            </label>

                            <select
                                name="category_id"
                                id="category_id"
                                required
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Sélectionner une catégorie</option>

                                @foreach($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        @selected(old('category_id', $product->category_id) == $category->id)
                                    >
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="4"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ old('description', $product->description ?? '') }}</textarea>

                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Unité --}}
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700">
                            Unité
                        </label>

                        <select
                            name="unit"
                            id="unit"
                            required
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            @foreach([
                                'piece' => 'Pièce',
                                'bouteille' => 'Bouteille',
                                'carton' => 'Carton',
                                'sac' => 'Sac',
                                'kg' => 'Kg',
                                'litre' => 'Litre'
                            ] as $value => $label)

                                <option
                                    value="{{ $value }}"
                                    @selected(old('unit', $product->unit) === $value)
                                >
                                    {{ $label }}
                                </option>

                            @endforeach
                        </select>

                        @error('unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Prix --}}
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        <div>
                            <label for="purchase_price" class="block text-sm font-medium text-gray-700">
                                Prix d'achat (FCFA)
                            </label>

                            <input
                                type="number"
                                name="purchase_price"
                                id="purchase_price"
                                value="{{ old('purchase_price', $product->purchase_price) }}"
                                min="0"
                                step="0.01"
                                required
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('purchase_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="selling_price" class="block text-sm font-medium text-gray-700">
                                Prix de vente (FCFA)
                            </label>

                            <input
                                type="number"
                                name="selling_price"
                                id="selling_price"
                                value="{{ old('selling_price', $product->selling_price) }}"
                                min="0"
                                step="0.01"
                                required
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('selling_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Stock --}}
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        <div>
                            <label for="stock_quantity" class="block text-sm font-medium text-gray-700">
                                Stock actuel
                            </label>

                            <input
                                type="number"
                                name="stock_quantity"
                                id="stock_quantity"
                                value="{{ old('stock_quantity', $product->stock_quantity) }}"
                                min="0"
                                required
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('stock_quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="minimum_stock" class="block text-sm font-medium text-gray-700">
                                Stock minimum
                            </label>

                            <input
                                type="number"
                                name="minimum_stock"
                                id="minimum_stock"
                                value="{{ old('minimum_stock', $product->minimum_stock) }}"
                                min="0"
                                required
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('minimum_stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Statut --}}
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            name="is_active"
                            id="is_active"
                            value="1"
                            @checked(old('is_active', $product->is_active))
                            class="text-indigo-600 border-gray-300 rounded shadow-sm focus:ring-indigo-500"
                        >

                        <label for="is_active" class="ml-2 text-sm text-gray-700">
                            Produit actif
                        </label>
                    </div>

                    {{-- Boutons --}}
                    <div class="flex flex-col-reverse gap-3 pt-6 border-t sm:flex-row sm:justify-end">

                        <a
                            href="{{ route('products.show', $product) }}"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                        >
                            Annuler
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
                        >
                            Enregistrer les modifications
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>
