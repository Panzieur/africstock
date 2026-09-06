<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Détail de la vente
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Consultez les informations et les produits de cette vente.
                </p>
            </div>

            <div class="flex gap-2">
                <a
                    href="{{ route('sales.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    ← Retour
                </a>

                @if($sale->status === 'completed' && auth()->user()->can('sales.edit'))

                    <form
                        method="POST"
                        action="{{ route('sales.cancel', $sale) }}"
                        onsubmit="return confirm('Voulez-vous vraiment annuler cette vente ? Le stock sera automatiquement rétabli.')"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-700 border border-red-200 rounded-lg bg-red-50 hover:bg-red-100"
                        >
                            Annuler la vente
                        </button>
                    </form>

                @endif
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

            {{-- Informations générales --}}
            <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm text-gray-500">Référence</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $sale->reference }}
                    </p>
                </div>

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm text-gray-500">Client</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $sale->customer?->name ?? 'Vente comptoir' }}
                    </p>
                </div>

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm text-gray-500">Vendeur</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $sale->user->name }}
                    </p>
                </div>

            </div>

            {{-- Date / statut --}}
            <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2">

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm text-gray-500">Date de vente</p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $sale->sold_at ? $sale->sold_at->format('d/m/Y H:i') : $sale->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm text-gray-500">Statut</p>

                    <div class="mt-2">
                        @if($sale->status === 'completed')
                            <span class="inline-flex px-3 py-1 text-sm font-medium text-green-700 bg-green-100 rounded-full">
                                Terminée
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 text-sm font-medium text-red-700 bg-red-100 rounded-full">
                                Annulée
                            </span>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Produits --}}
            <div class="mb-6 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">
                        Produits vendus
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Produit
                                </th>

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                    Quantité
                                </th>

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                    Prix unitaire
                                </th>

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                    Sous-total
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                            @foreach($sale->items as $item)

                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">
                                            {{ $item->product->name }}
                                        </div>

                                        <div class="text-sm text-gray-500">
                                            {{ $item->product->sku }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-right text-gray-700">
                                        {{ $item->quantity }}
                                    </td>

                                    <td class="px-6 py-4 text-right text-gray-700">
                                        {{ number_format($item->unit_price, 0, ',', ' ') }} FCFA
                                    </td>

                                    <td class="px-6 py-4 font-medium text-right text-gray-900">
                                        {{ number_format($item->subtotal, 0, ',', ' ') }} FCFA
                                    </td>
                                </tr>

                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>


            {{-- Paiement --}}
            <div class="mb-6 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">
                        Paiement
                    </h3>
                </div>

                <div class="p-6">

                    {{-- Résumé paiement --}}
                    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-3">

                        <div class="p-4 rounded-lg bg-gray-50">
                            <p class="text-sm text-gray-500">
                                Montant total
                            </p>

                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ number_format($sale->total, 0, ',', ' ') }} FCFA
                            </p>
                        </div>

                        <div class="p-4 rounded-lg bg-green-50">
                            <p class="text-sm text-green-700">
                                Montant payé
                            </p>

                            <p class="mt-1 text-lg font-semibold text-green-800">
                                {{ number_format($sale->paid_amount, 0, ',', ' ') }} FCFA
                            </p>
                        </div>

                        <div class="p-4 rounded-lg bg-orange-50">
                            <p class="text-sm text-orange-700">
                                Reste à payer
                            </p>

                            <p class="mt-1 text-lg font-semibold text-orange-800">
                                {{ number_format($sale->remaining_amount, 0, ',', ' ') }} FCFA
                            </p>
                        </div>

                    </div>

                    {{-- Statut du paiement --}}
                    <div class="mb-6">

                        <p class="mb-2 text-sm text-gray-500">
                            État du paiement
                        </p>

                        @if($sale->payment_status === 'paid')

                            <span class="inline-flex px-3 py-1 text-sm font-medium text-green-700 bg-green-100 rounded-full">
                                Payée
                            </span>

                        @elseif($sale->payment_status === 'partial')

                            <span class="inline-flex px-3 py-1 text-sm font-medium text-orange-700 bg-orange-100 rounded-full">
                                Partiellement payée
                            </span>

                        @else

                            <span class="inline-flex px-3 py-1 text-sm font-medium text-red-700 bg-red-100 rounded-full">
                                Non payée
                            </span>

                        @endif

                    </div>

                    {{-- Échéance du crédit --}}
                    @if($sale->remaining_amount > 0 && $sale->credit_due_date)

                        <div class="p-4 mb-6 border rounded-lg
                            {{ $sale->credit_due_date->isPast()
                                ? 'border-red-200 bg-red-50'
                                : 'border-blue-200 bg-blue-50'
                            }}">

                            <div class="flex items-center justify-between gap-4">

                                <div>
                                    <p class="text-sm font-medium
                                        {{ $sale->credit_due_date->isPast()
                                            ? 'text-red-700'
                                            : 'text-blue-700'
                                        }}">
                                        Date d'échéance
                                    </p>

                                    <p class="mt-1 text-lg font-semibold
                                        {{ $sale->credit_due_date->isPast()
                                            ? 'text-red-800'
                                            : 'text-blue-800'
                                        }}">
                                        {{ $sale->credit_due_date->format('d/m/Y') }}
                                    </p>
                                </div>

                                @if($sale->credit_due_date->isPast())

                                    <span class="inline-flex px-3 py-1 text-sm font-medium text-red-700 bg-red-100 rounded-full">
                                        En retard
                                    </span>

                                @else

                                    <span class="inline-flex px-3 py-1 text-sm font-medium text-blue-700 bg-blue-100 rounded-full">
                                        En cours
                                    </span>

                                @endif

                            </div>

                        </div>

                    @endif

                    {{-- Historique des paiements --}}
                    @if($sale->payments->count())

                        <div>
                            <h4 class="mb-3 text-sm font-semibold text-gray-900">
                                Historique des paiements
                            </h4>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">

                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                                Date
                                            </th>

                                            <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                                Mode
                                            </th>

                                            <th class="px-4 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                                Montant
                                            </th>

                                            <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                                Référence
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-200">

                                        @foreach($sale->payments as $payment)

                                            <tr>

                                                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                                    {{ $payment->paid_at?->format('d/m/Y H:i') ?? $payment->created_at->format('d/m/Y H:i') }}
                                                </td>

                                                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">

                                                    @switch($payment->method)

                                                        @case('cash')
                                                            Espèces
                                                            @break

                                                        @case('mobile_money')
                                                            Mobile Money
                                                            @break

                                                        @case('bank_transfer')
                                                            Virement bancaire
                                                            @break

                                                        @case('card')
                                                            Carte bancaire
                                                            @break

                                                        @default
                                                            {{ $payment->method }}

                                                    @endswitch

                                                </td>

                                                <td class="px-4 py-3 text-sm font-medium text-right text-gray-900 whitespace-nowrap">
                                                    {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                                                </td>

                                                <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                                                    {{ $payment->reference ?? '—' }}
                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>
                            </div>

                        </div>

                    @else

                        <div class="px-4 py-3 text-sm text-red-700 border border-red-200 rounded-lg bg-red-50">
                            Aucun paiement enregistré pour cette vente.
                        </div>

                    @endif

                    {{-- Ajouter un paiement --}}
                    @if(
                            $sale->status === 'completed' &&
                            auth()->user()->can('payments.create') &&
                            $sale->remaining_amount > 0
                        )


                        <div class="pt-6 mt-6 border-t border-gray-200">

                            <h4 class="mb-4 text-sm font-semibold text-gray-900">
                                Ajouter un paiement
                            </h4>

                            <form
                                method="POST"
                                action="{{ route('sales.payments.store', $sale) }}"
                                class="space-y-4"
                            >
                                @csrf

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                    {{-- Mode de paiement --}}
                                    <div>
                                        <label
                                            for="payment_method"
                                            class="block mb-1 text-sm font-medium text-gray-700"
                                        >
                                            Mode de paiement
                                        </label>

                                        <select
                                            id="payment_method"
                                            name="payment_method"
                                            required
                                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                            <option value="">Sélectionner</option>
                                            <option value="cash">Espèces</option>
                                            <option value="mobile_money">Mobile Money</option>
                                            <option value="bank_transfer">Virement bancaire</option>
                                            <option value="card">Carte bancaire</option>
                                        </select>
                                    </div>

                                    {{-- Montant --}}
                                    <div>
                                        <label
                                            for="payment_amount"
                                            class="block mb-1 text-sm font-medium text-gray-700"
                                        >
                                            Montant
                                        </label>

                                        <input
                                            type="number"
                                            id="payment_amount"
                                            name="payment_amount"
                                            min="0.01"
                                            max="{{ $sale->remaining_amount }}"
                                            step="0.01"
                                            value="{{ $sale->remaining_amount }}"
                                            required
                                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >

                                        <p class="mt-1 text-xs text-gray-500">
                                            Maximum :
                                            {{ number_format($sale->remaining_amount, 0, ',', ' ') }} FCFA
                                        </p>
                                    </div>

                                    {{-- Référence --}}
                                    <div>
                                        <label
                                            for="payment_reference"
                                            class="block mb-1 text-sm font-medium text-gray-700"
                                        >
                                            Référence
                                        </label>

                                        <input
                                            type="text"
                                            id="payment_reference"
                                            name="payment_reference"
                                            maxlength="255"
                                            placeholder="Ex : TXN123456"
                                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                    </div>

                                    {{-- Notes --}}
                                    <div>
                                        <label
                                            for="payment_notes"
                                            class="block mb-1 text-sm font-medium text-gray-700"
                                        >
                                            Notes
                                        </label>

                                        <input
                                            type="text"
                                            id="payment_notes"
                                            name="payment_notes"
                                            maxlength="1000"
                                            placeholder="Note facultative"
                                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                    </div>

                                </div>

                                <div class="flex justify-end pt-2">

                                    <button
                                        type="submit"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
                                    >
                                        Enregistrer le paiement
                                    </button>

                                </div>

                            </form>

                        </div>

                    @endif

                </div>

            </div>

            {{-- Remboursements --}}
            @if($sale->refunds->count() || auth()->user()->can('refunds.create'))

                <div class="mb-6 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">
                            Remboursements
                        </h3>
                    </div>

                    <div class="p-6">

                        {{-- Résumé des remboursements --}}
                        <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-3">

                            <div class="p-4 rounded-lg bg-gray-50">
                                <p class="text-sm text-gray-500">
                                    Montant payé
                                </p>

                                <p class="mt-1 text-lg font-semibold text-gray-900">
                                    {{ number_format($sale->paid_amount, 0, ',', ' ') }} FCFA
                                </p>
                            </div>

                            <div class="p-4 rounded-lg bg-red-50">
                                <p class="text-sm text-red-700">
                                    Montant remboursé
                                </p>

                                <p class="mt-1 text-lg font-semibold text-red-800">
                                    {{ number_format($sale->refunded_amount, 0, ',', ' ') }} FCFA
                                </p>
                            </div>

                            <div class="p-4 rounded-lg bg-orange-50">
                                <p class="text-sm text-orange-700">
                                    Encore remboursable
                                </p>

                                <p class="mt-1 text-lg font-semibold text-orange-800">
                                    {{ number_format($sale->refundable_amount, 0, ',', ' ') }} FCFA
                                </p>
                            </div>

                        </div>

                        {{-- Historique des remboursements --}}
                        @if($sale->refunds->count())

                            <div>

                                <h4 class="mb-3 text-sm font-semibold text-gray-900">
                                    Historique des remboursements
                                </h4>

                                <div class="overflow-x-auto">

                                    <table class="min-w-full divide-y divide-gray-200">

                                        <thead class="bg-gray-50">
                                            <tr>

                                                <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                                    Date
                                                </th>

                                                <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                                    Effectué par
                                                </th>

                                                <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                                    Mode
                                                </th>

                                                <th class="px-4 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                                    Montant
                                                </th>

                                                <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                                    Référence
                                                </th>

                                                <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                                    Motif
                                                </th>

                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-gray-200">

                                            @foreach($sale->refunds as $refund)

                                                <tr>

                                                    <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                                        {{ $refund->refunded_at?->format('d/m/Y H:i') ?? $refund->created_at->format('d/m/Y H:i') }}
                                                    </td>

                                                    <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                                        {{ $refund->user->name }}
                                                    </td>

                                                    <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">

                                                        @switch($refund->method)

                                                            @case('cash')
                                                                Espèces
                                                                @break

                                                            @case('mobile_money')
                                                                Mobile Money
                                                                @break

                                                            @case('bank_transfer')
                                                                Virement bancaire
                                                                @break

                                                            @case('card')
                                                                Carte bancaire
                                                                @break

                                                            @default
                                                                {{ $refund->method }}

                                                        @endswitch

                                                    </td>

                                                    <td class="px-4 py-3 text-sm font-medium text-right text-red-700 whitespace-nowrap">
                                                        - {{ number_format($refund->amount, 0, ',', ' ') }} FCFA
                                                    </td>

                                                    <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                                                        {{ $refund->reference ?? '—' }}
                                                    </td>

                                                    <td class="px-4 py-3 text-sm text-gray-600">
                                                        {{ $refund->reason }}
                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        @else

                            <div class="px-4 py-3 text-sm text-gray-600 border border-gray-200 rounded-lg bg-gray-50">
                                Aucun remboursement enregistré pour cette vente.
                            </div>

                        @endif

                        {{-- Effectuer un remboursement --}}
                        @if(
                            $sale->status === 'completed' &&
                            auth()->user()->can('refunds.create') &&
                            $sale->refundable_amount > 0
                        )

                            <div class="pt-6 mt-6 border-t border-gray-200">

                                <h4 class="mb-1 text-sm font-semibold text-gray-900">
                                    Effectuer un remboursement
                                </h4>

                                <p class="mb-4 text-xs text-gray-500">
                                    Montant maximum remboursable :
                                    {{ number_format($sale->refundable_amount, 0, ',', ' ') }} FCFA
                                </p>

                                <form
                                    method="POST"
                                    action="{{ route('sales.refunds.store', $sale) }}"
                                    class="space-y-4"
                                >
                                    @csrf

                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                        {{-- Montant --}}
                                        <div>
                                            <label
                                                for="refund_amount"
                                                class="block mb-1 text-sm font-medium text-gray-700"
                                            >
                                                Montant du remboursement
                                            </label>

                                            <input
                                                type="number"
                                                id="refund_amount"
                                                name="refund_amount"
                                                min="0.01"
                                                max="{{ $sale->refundable_amount }}"
                                                step="0.01"
                                                value="{{ $sale->refundable_amount }}"
                                                required
                                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >
                                        </div>

                                        {{-- Mode --}}
                                        <div>
                                            <label
                                                for="refund_method"
                                                class="block mb-1 text-sm font-medium text-gray-700"
                                            >
                                                Mode de remboursement
                                            </label>

                                            <select
                                                id="refund_method"
                                                name="refund_method"
                                                required
                                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >
                                                <option value="">Sélectionner</option>
                                                <option value="cash">Espèces</option>
                                                <option value="mobile_money">Mobile Money</option>
                                                <option value="bank_transfer">Virement bancaire</option>
                                                <option value="card">Carte bancaire</option>
                                            </select>
                                        </div>

                                        {{-- Référence --}}
                                        <div>
                                            <label
                                                for="refund_reference"
                                                class="block mb-1 text-sm font-medium text-gray-700"
                                            >
                                                Référence
                                            </label>

                                            <input
                                                type="text"
                                                id="refund_reference"
                                                name="refund_reference"
                                                maxlength="255"
                                                placeholder="Ex : REF123456"
                                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >
                                        </div>

                                        {{-- Motif --}}
                                        <div>
                                            <label
                                                for="refund_reason"
                                                class="block mb-1 text-sm font-medium text-gray-700"
                                            >
                                                Motif du remboursement
                                            </label>

                                            <input
                                                type="text"
                                                id="refund_reason"
                                                name="refund_reason"
                                                maxlength="1000"
                                                required
                                                placeholder="Ex : Produit retourné par le client"
                                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >
                                        </div>

                                    </div>

                                    <div class="flex justify-end pt-2">

                                        <button
                                            type="submit"
                                            onclick="return confirm('Confirmez-vous ce remboursement ?')"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
                                        >
                                            Effectuer le remboursement
                                        </button>

                                    </div>

                                </form>

                            </div>

                        @endif

                    </div>

                </div>

            @endif

            {{-- Résumé --}}
            <div class="flex justify-end">

                <div class="w-full p-6 bg-white border border-gray-200 shadow-sm rounded-xl md:w-96">

                    <div class="flex justify-between py-2 text-sm">
                        <span class="text-gray-500">
                            Sous-total
                        </span>

                        <span class="font-medium text-gray-900">
                            {{ number_format($sale->subtotal, 0, ',', ' ') }} FCFA
                        </span>
                    </div>

                    <div class="flex justify-between py-2 text-sm">
                        <span class="text-gray-500">
                            Remise
                        </span>

                        <span class="font-medium text-gray-900">
                            {{ number_format($sale->discount, 0, ',', ' ') }} FCFA
                        </span>
                    </div>

                    <div class="pt-4 mt-2 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-base font-semibold text-gray-900">
                                Total
                            </span>

                            <span class="text-xl font-bold text-indigo-600">
                                {{ number_format($sale->total, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Notes --}}
            @if($sale->notes)
                <div class="p-5 mt-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <h3 class="text-sm font-semibold text-gray-900">
                        Notes
                    </h3>

                    <p class="mt-2 text-sm text-gray-600 whitespace-pre-line">
                        {{ $sale->notes }}
                    </p>
                </div>
            @endif

        </div>
    </div>

</x-app-layout>
