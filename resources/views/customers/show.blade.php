<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Détail du client
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Consultez les informations et l’historique de {{ $customer->name }}.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('customers.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    ← Retour
                </a>

                @can('customers.edit')
                    <a
                        href="{{ route('customers.edit', $customer) }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
                    >
                        Modifier
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Messages --}}
            @if(session('success'))
                <div class="px-4 py-3 mb-6 border border-green-200 rounded-lg bg-green-50">
                    <p class="text-sm font-medium text-green-700">
                        {{ session('success') }}
                    </p>
                </div>
            @endif

            @if(session('error'))
                <div class="px-4 py-3 mb-6 border border-red-200 rounded-lg bg-red-50">
                    <p class="text-sm font-medium text-red-700">
                        {{ session('error') }}
                    </p>
                </div>
            @endif

            {{-- Informations principales --}}
            <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">

                {{-- Client --}}
                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Client
                    </p>

                    <h3 class="mt-2 text-xl font-semibold text-gray-900">
                        {{ $customer->name }}
                    </h3>

                    <div class="mt-3">
                        @if($customer->is_active)
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                Actif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">
                                Inactif
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Nombre de ventes --}}
                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Nombre de ventes
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $customer->sales_count }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        vente(s) enregistrée(s)
                    </p>
                </div>

                {{-- Total acheté --}}
                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Montant total acheté
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ number_format($customer->sales_sum_total ?? 0, 0, ',', ' ') }} FCFA
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        ventes terminées
                    </p>
                </div>

            </div>

            {{-- Crédit client --}}
            @if($customer->credit_allowed)
                <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">

                    {{-- Limite --}}
                    <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <p class="text-sm font-medium text-gray-500">
                            Limite de crédit
                        </p>

                        <p class="mt-2 text-xl font-bold text-gray-900">
                            {{ number_format($customer->credit_limit, 0, ',', ' ') }} FCFA
                        </p>
                    </div>

                    {{-- Crédit utilisé --}}
                    <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <p class="text-sm font-medium text-gray-500">
                            Crédit utilisé
                        </p>

                        <p class="mt-2 text-xl font-bold text-orange-600">
                            {{ number_format($customer->credit_used, 0, ',', ' ') }} FCFA
                        </p>
                    </div>

                    {{-- Crédit disponible --}}
                    <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <p class="text-sm font-medium text-gray-500">
                            Crédit disponible
                        </p>

                        <p class="mt-2 text-xl font-bold text-green-600">
                            {{ number_format($customer->credit_available, 0, ',', ' ') }} FCFA
                        </p>
                    </div>

                </div>
            @endif

            {{-- Informations du client --}}
            <div class="grid grid-cols-1 gap-4 mb-6 lg:grid-cols-3">

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Téléphone
                    </p>

                    <p class="mt-2 text-gray-900">
                        {{ $customer->phone ?: 'Non renseigné' }}
                    </p>
                </div>

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Email
                    </p>

                    <p class="mt-2 text-gray-900">
                        {{ $customer->email ?: 'Non renseigné' }}
                    </p>
                </div>

                <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <p class="text-sm font-medium text-gray-500">
                        Adresse
                    </p>

                    <p class="mt-2 text-gray-900">
                        {{ $customer->address ?: 'Non renseignée' }}
                    </p>
                </div>

            </div>

            {{-- Notes --}}
            @if($customer->notes)
                <div class="p-5 mb-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <h3 class="text-sm font-semibold text-gray-900">
                        Notes
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        {{ $customer->notes }}
                    </p>
                </div>
            @endif

            {{-- Historique des ventes --}}
            <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Historique des ventes
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Les dernières ventes associées à ce client.
                    </p>
                </div>

                @if($customer->sales->count())

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Référence
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Date
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Total
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

                                @foreach($customer->sales as $sale)
                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            {{ $sale->reference }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                            {{ $sale->sold_at ? $sale->sold_at->format('d/m/Y H:i') : $sale->created_at->format('d/m/Y H:i') }}
                                        </td>

                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 whitespace-nowrap">
                                            {{ number_format($sale->total, 0, ',', ' ') }} FCFA
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($sale->status === 'completed')
                                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                                    Terminée
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">
                                                    Annulée
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <a
                                                href="{{ route('sales.show', $sale) }}"
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

                @else

                    <div class="px-6 py-12 text-center">
                        <p class="text-sm text-gray-500">
                            Aucune vente n'est encore associée à ce client.
                        </p>

                        @can('sales.create')
                            <a
                                href="{{ route('sales.create') }}"
                                class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
                            >
                                Créer une vente
                            </a>
                        @endcan
                    </div>

                @endif

            </div>

        </div>
    </div>
</x-app-layout>
