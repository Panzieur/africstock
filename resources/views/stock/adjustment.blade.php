<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Ajustement de stock
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Corriger le stock après un inventaire physique
                </p>
            </div>

            <a
                href="{{ route('stock.index') }}"
                class="text-sm font-medium text-gray-600 hover:text-gray-900"
            >
                ← Retour au stock
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl px-4 mx-auto sm:px-6 lg:px-8">

            <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Nouvel ajustement
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Indiquez la quantité réellement constatée lors de l'inventaire.
                    </p>
                </div>

                <form
                    method="POST"
                    action="{{ route('stock.adjustment.store') }}"
                    class="p-6 space-y-6"
                >
                    @csrf

                    {{-- Produit --}}
                    <div>
                        <label
                            for="product_id"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Produit
                        </label>

                        <select
                            name="product_id"
                            id="product_id"
                            required
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Sélectionner un produit</option>

                            @foreach($products as $product)
                                <option
                                    value="{{ $product->id }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}
                                >
                                    {{ $product->name }} — Stock actuel : {{ $product->stock_quantity }}
                                </option>
                            @endforeach
                        </select>

                        @error('product_id')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Stock réel --}}
                    <div>
                        <label
                            for="stock_actual"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Stock réellement constaté
                        </label>

                        <input
                            type="number"
                            name="stock_actual"
                            id="stock_actual"
                            value="{{ old('stock_actual') }}"
                            min="0"
                            required
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Ex : 40"
                        >

                        <p class="mt-1 text-xs text-gray-500">
                            Entrez la quantité réellement présente après comptage physique.
                        </p>

                        @error('stock_actual')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Motif --}}
                    <div>
                        <label
                            for="reason"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Motif de l'ajustement
                        </label>

                        <textarea
                            name="reason"
                            id="reason"
                            rows="4"
                            required
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Ex : Inventaire physique, produit endommagé, erreur de saisie..."
                        >{{ old('reason') }}</textarea>

                        @error('reason')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Information --}}
                    <div class="px-4 py-3 border border-yellow-200 rounded-lg bg-yellow-50">
                        <p class="text-sm text-yellow-800">
                            <strong>Attention :</strong>
                            l'ajustement remplacera le stock actuel par la quantité réellement constatée.
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">

                        <a
                            href="{{ route('stock.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                        >
                            Annuler
                        </a>

                        <button
                            type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-yellow-600 rounded-md hover:bg-yellow-700"
                        >
                            Enregistrer l'ajustement
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>
