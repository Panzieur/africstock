<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Nouvelle vente
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Enregistrez une nouvelle vente et déduisez automatiquement les produits du stock.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Erreurs --}}
            @if($errors->any())
                <div class="p-4 mb-6 border border-red-200 rounded-lg bg-red-50">
                    <p class="text-sm font-semibold text-red-800">
                        Vérifiez les informations saisies.
                    </p>

                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('sales.store') }}"
                class="space-y-6"
            >
                @csrf

                {{-- Informations générales --}}
                <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">
                            Informations générales
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

                        {{-- Client --}}
                        <div>
                            <label
                                for="customer_id"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Client
                            </label>

                            <select
                                name="customer_id"
                                id="customer_id"
                                class="block w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">
                                    Vente comptoir
                                </option>

                                @foreach($customers as $customer)
                                    <option
                                        value="{{ $customer->id }}"
                                        data-credit-allowed="{{ $customer->credit_allowed ? '1' : '0' }}"
                                        data-credit-available="{{ $customer->credit_available }}"
                                        @selected(old('customer_id') == $customer->id)
                                    >
                                        {{ $customer->name }}
                                        @if($customer->phone)
                                            — {{ $customer->phone }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            <p class="mt-1 text-xs text-gray-500">
                                Laissez « Vente comptoir » pour un client non enregistré.
                            </p>

                            <div
                                id="customer-credit-info"
                                class="hidden p-3 mt-3 text-sm border rounded-lg"
                            >
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label
                                for="notes"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Notes
                            </label>

                            <input
                                type="text"
                                name="notes"
                                id="notes"
                                value="{{ old('notes') }}"
                                placeholder="Note facultative..."
                                class="block w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                    </div>

                </div>

                {{-- Produits --}}
                <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">
                            Produits
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Ajoutez les produits vendus et indiquez les quantités.
                        </p>
                    </div>

                    <div class="p-6">

                        <div class="overflow-x-auto">
                            <table class="min-w-full">

                                <thead>
                                    <tr class="border-b border-gray-200">

                                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                            Produit
                                        </th>

                                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                            Stock disponible
                                        </th>

                                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                            Prix unitaire
                                        </th>

                                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                            Quantité
                                        </th>

                                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                            Sous-total
                                        </th>

                                    </tr>
                                </thead>

                                <tbody id="sale-items">

                                    <tr class="border-b border-gray-100 sale-item">

                                        {{-- Produit --}}
                                        <td class="px-4 py-4">

                                            <select
                                                name="items[0][product_id]"
                                                class="block w-full border-gray-300 rounded-lg shadow-sm product-select focus:border-indigo-500 focus:ring-indigo-500"
                                            >
                                                <option value="">
                                                    Sélectionner un produit
                                                </option>

                                                @foreach($products as $product)
                                                    <option
                                                        value="{{ $product->id }}"
                                                        data-stock="{{ $product->stock_quantity }}"
                                                        data-price="{{ $product->selling_price }}"
                                                    >
                                                        {{ $product->name }}
                                                        — {{ $product->sku }}
                                                    </option>
                                                @endforeach

                                            </select>

                                        </td>

                                        {{-- Stock --}}
                                        <td class="px-4 py-4 text-right">
                                            <span class="text-sm font-medium text-gray-700 stock-display">
                                                —
                                            </span>
                                        </td>

                                        {{-- Prix --}}
                                        <td class="px-4 py-4 text-right whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-700 price-display">
                                                —
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                FCFA
                                            </span>
                                        </td>

                                        {{-- Quantité --}}
                                        <td class="px-4 py-4">

                                            <input
                                                type="number"
                                                name="items[0][quantity]"
                                                value="1"
                                                min="1"
                                                class="block w-24 ml-auto text-right border-gray-300 rounded-lg shadow-sm quantity-input focus:border-indigo-500 focus:ring-indigo-500"
                                            >

                                        </td>

                                        {{-- Sous-total --}}
                                        <td class="px-4 py-4 text-right whitespace-nowrap">

                                            <span class="text-sm font-semibold text-gray-900 subtotal-display">
                                                0
                                            </span>

                                            <span class="text-xs text-gray-500">
                                                FCFA
                                            </span>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>
                        </div>

                        <div class="flex justify-end mt-4">

                            <button
                                type="button"
                                id="add-item"
                                class="inline-flex items-center px-4 py-2 text-sm font-semibold text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50"
                            >
                                + Ajouter un produit
                            </button>

                        </div>

                    </div>

                </div>

                {{-- Total --}}
                <div class="flex justify-end">

                    <div class="w-full max-w-md overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

                        <div class="p-6 space-y-3">

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">
                                    Sous-total
                                </span>

                                <span
                                    id="subtotal-total"
                                    class="font-medium text-gray-900"
                                >
                                    0 FCFA
                                </span>
                            </div>

                            <div class="flex justify-between pt-3 text-lg font-bold border-t border-gray-200">
                                <span>
                                    Total
                                </span>

                                <span id="sale-total">
                                    0 FCFA
                                </span>
                            </div>

                        </div>

                    </div>

                </div>

                {{-- Paiement --}}
                <div class="pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Paiement
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Une vente peut être payée immédiatement, partiellement ou à crédit.
                    </p>

                    <div class="grid grid-cols-1 gap-6 mt-6 md:grid-cols-2">

                        {{-- Mode de paiement --}}
                        <div>
                            <label
                                for="payment_method"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Mode de paiement
                            </label>

                            <select
                                name="payment_method"
                                id="payment_method"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Aucun paiement</option>
                                <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>
                                    Espèces
                                </option>
                                <option value="mobile_money" {{ old('payment_method') === 'mobile_money' ? 'selected' : '' }}>
                                    Mobile Money
                                </option>
                                <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>
                                    Virement bancaire
                                </option>
                                <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>
                                    Carte bancaire
                                </option>
                            </select>
                        </div>

                        {{-- Montant payé --}}
                        <div>
                            <label
                                for="payment_amount"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Montant payé
                            </label>

                            <input
                                type="number"
                                name="payment_amount"
                                id="payment_amount"
                                value="{{ old('payment_amount', 0) }}"
                                min="0"
                                step="0.01"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="0"
                            >
                        </div>

                        {{-- Référence --}}
                        <div>
                            <label
                                for="payment_reference"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Référence du paiement
                            </label>

                            <input
                                type="text"
                                name="payment_reference"
                                id="payment_reference"
                                value="{{ old('payment_reference') }}"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Ex. TXN123456"
                            >
                        </div>

                        {{-- Notes paiement --}}
                        <div>
                            <label
                                for="payment_notes"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Notes du paiement
                            </label>

                            <input
                                type="text"
                                name="payment_notes"
                                id="payment_notes"
                                value="{{ old('payment_notes') }}"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Informations complémentaires"
                            >
                        </div>

                    </div>

                    {{-- Reste à payer --}}
                    <div class="p-4 mt-6 rounded-lg bg-gray-50">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">
                                Reste à payer
                            </span>

                            <span
                                id="payment_remaining"
                                class="text-lg font-bold text-red-600"
                            >
                                0 FCFA
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3">

                    <a
                        href="{{ route('sales.index') }}"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                    >
                        Annuler
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
                    >
                        Enregistrer la vente
                    </button>

                </div>

            </form>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const itemsContainer = document.getElementById('sale-items');
            const addItemButton = document.getElementById('add-item');

            const paymentAmountInput = document.getElementById('payment_amount');
            const paymentRemaining = document.getElementById('payment_remaining');

            const customerSelect = document.getElementById('customer_id');
            const customerCreditInfo = document.getElementById('customer-credit-info');

            let itemIndex = 1;

            function formatPrice(value) {
                return new Intl.NumberFormat('fr-FR').format(value);
            }

            /*
            |--------------------------------------------------------------------------
            | Calcul du total
            |--------------------------------------------------------------------------
            */

            function calculateTotal() {

                let total = 0;

                document.querySelectorAll('.sale-item').forEach(row => {

                    const select = row.querySelector('.product-select');
                    const quantityInput = row.querySelector('.quantity-input');

                    if (!select || !quantityInput) {
                        return;
                    }

                    const option = select.options[select.selectedIndex];

                    if (!option || !option.value) {
                        return;
                    }

                    const price = parseFloat(option.dataset.price || 0);
                    const quantity = parseInt(quantityInput.value || 0);

                    total += price * quantity;
                });

                document.getElementById('subtotal-total').textContent =
                    formatPrice(total) + ' FCFA';

                document.getElementById('sale-total').textContent =
                    formatPrice(total) + ' FCFA';

                updatePaymentRemaining();
            }

            /*
            |--------------------------------------------------------------------------
            | Reste à payer
            |--------------------------------------------------------------------------
            */

            function updatePaymentRemaining() {

                let total = 0;

                document.querySelectorAll('.sale-item').forEach(row => {

                    const select = row.querySelector('.product-select');
                    const quantityInput = row.querySelector('.quantity-input');

                    if (!select || !quantityInput) {
                        return;
                    }

                    const option = select.options[select.selectedIndex];

                    if (!option || !option.value) {
                        return;
                    }

                    const price = parseFloat(option.dataset.price || 0);
                    const quantity = parseInt(quantityInput.value || 0);

                    total += price * quantity;
                });

                const paid = parseFloat(paymentAmountInput.value || 0);

                const remaining = Math.max(total - paid, 0);

                paymentRemaining.textContent =
                    formatPrice(remaining) + ' FCFA';
            }

            /*
            |--------------------------------------------------------------------------
            | Mise à jour d'une ligne produit
            |--------------------------------------------------------------------------
            */

            function updateRow(row) {

                const select = row.querySelector('.product-select');
                const option = select.options[select.selectedIndex];

                const stockDisplay = row.querySelector('.stock-display');
                const priceDisplay = row.querySelector('.price-display');
                const quantityInput = row.querySelector('.quantity-input');
                const subtotalDisplay = row.querySelector('.subtotal-display');

                if (!option || !option.value) {

                    stockDisplay.textContent = '—';
                    priceDisplay.textContent = '—';
                    subtotalDisplay.textContent = '0';

                    calculateTotal();

                    return;
                }

                const stock = parseInt(option.dataset.stock || 0);
                const price = parseFloat(option.dataset.price || 0);
                const quantity = parseInt(quantityInput.value || 0);

                stockDisplay.textContent = stock;
                priceDisplay.textContent = formatPrice(price);

                subtotalDisplay.textContent =
                    formatPrice(price * quantity);

                quantityInput.max = stock;

                calculateTotal();
            }

            /*
            |--------------------------------------------------------------------------
            | Événements des lignes
            |--------------------------------------------------------------------------
            */

            function attachRowEvents(row) {

                const select = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');

                select.addEventListener('change', function () {
                    updateRow(row);
                });

                quantityInput.addEventListener('input', function () {
                    updateRow(row);
                });
            }

            /*
            |--------------------------------------------------------------------------
            | Ajouter un produit
            |--------------------------------------------------------------------------
            */

            const firstRow = document.querySelector('.sale-item');

            if (firstRow) {
                attachRowEvents(firstRow);
            }

            addItemButton.addEventListener('click', function () {

                const firstRow = document.querySelector('.sale-item');

                const newRow = firstRow.cloneNode(true);

                newRow.querySelector('.product-select').name =
                    `items[${itemIndex}][product_id]`;

                newRow.querySelector('.quantity-input').name =
                    `items[${itemIndex}][quantity]`;

                newRow.querySelector('.product-select').selectedIndex = 0;

                newRow.querySelector('.quantity-input').value = 1;

                newRow.querySelector('.stock-display').textContent = '—';
                newRow.querySelector('.price-display').textContent = '—';
                newRow.querySelector('.subtotal-display').textContent = '0';

                itemsContainer.appendChild(newRow);

                attachRowEvents(newRow);

                itemIndex++;

                calculateTotal();
            });

            /*
            |--------------------------------------------------------------------------
            | Paiement
            |--------------------------------------------------------------------------
            */

            paymentAmountInput.addEventListener('input', function () {
                updatePaymentRemaining();
            });

            /*
            |--------------------------------------------------------------------------
            | Informations crédit du client
            |--------------------------------------------------------------------------
            */

            function updateCustomerCreditInfo() {

                const option =
                    customerSelect.options[customerSelect.selectedIndex];

                if (!option || !option.value) {

                    customerCreditInfo.classList.add('hidden');
                    customerCreditInfo.innerHTML = '';

                    return;
                }

                const creditAllowed =
                    option.dataset.creditAllowed === '1';

                const creditAvailable =
                    parseFloat(option.dataset.creditAvailable || 0);

                customerCreditInfo.classList.remove('hidden');

                if (creditAllowed) {

                    customerCreditInfo.className =
                        'p-3 mt-3 text-sm border rounded-lg bg-green-50 border-green-200 text-green-700';

                    customerCreditInfo.innerHTML =
                        '<strong>Crédit autorisé</strong>' +
                        '<span class="ml-2">' +
                        'Disponible : ' +
                        formatPrice(creditAvailable) +
                        ' FCFA' +
                        '</span>';

                } else {

                    customerCreditInfo.className =
                        'p-3 mt-3 text-sm border rounded-lg bg-gray-50 border-gray-200 text-gray-600';

                    customerCreditInfo.innerHTML =
                        '<strong>Crédit non autorisé</strong>';
                }
            }

            customerSelect.addEventListener(
                'change',
                updateCustomerCreditInfo
            );

            /*
            |--------------------------------------------------------------------------
            | Initialisation
            |--------------------------------------------------------------------------
            */

            updateCustomerCreditInfo();
            calculateTotal();

        });
    </script>

</x-app-layout>
