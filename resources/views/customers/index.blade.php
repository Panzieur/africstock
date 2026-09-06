<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Clients
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Gérez vos clients et consultez leur historique de ventes.
                </p>
            </div>

            @can('customers.create')
                <a
                    href="{{ route('customers.create') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700"
                >
                    + Nouveau client
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

            {{-- Filtres --}}
            <div class="p-5 mb-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <form
                    method="GET"
                    action="{{ route('customers.index') }}"
                    class="grid grid-cols-1 gap-4 md:grid-cols-3"
                >

                    <div class="md:col-span-2">
                        <label
                            for="search"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Rechercher
                        </label>

                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Nom, téléphone ou email..."
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

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
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Tous</option>
                            <option value="active" @selected(request('status') === 'active')>
                                Actifs
                            </option>
                            <option value="inactive" @selected(request('status') === 'inactive')>
                                Inactifs
                            </option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2 md:col-span-3">

                        <button
                            type="submit"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
                        >
                            Rechercher
                        </button>

                        <a
                            href="{{ route('customers.index') }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                        >
                            Réinitialiser
                        </a>

                    </div>

                </form>
            </div>

            {{-- Tableau --}}
            <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Client
                                </th>

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Contact
                                </th>

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-gray-500 uppercase">
                                    Ventes
                                </th>

                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-gray-500 uppercase">
                                    Crédit
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

                            @forelse($customers as $customer)

                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">
                                            {{ $customer->name }}
                                        </div>

                                        @if($customer->email)
                                            <div class="text-sm text-gray-500">
                                                {{ $customer->email }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $customer->phone ?? '—' }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-center text-gray-700">
                                        {{ $customer->sales_count }}
                                    </td>

                                    <td class="px-6 py-4 text-center">

                                        @if($customer->credit_allowed)

                                            <span class="inline-flex px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                                Autorisé
                                            </span>

                                            <div class="mt-2 space-y-1 text-xs text-gray-500">

                                                <div>
                                                    Utilisé :
                                                    <span class="font-medium text-gray-700">
                                                        {{ number_format($customer->credit_used, 0, ',', ' ') }} FCFA
                                                    </span>
                                                </div>

                                                <div>
                                                    Limite :
                                                    <span class="font-medium text-gray-700">
                                                        {{ number_format($customer->credit_limit, 0, ',', ' ') }} FCFA
                                                    </span>
                                                </div>

                                                <div>
                                                    Disponible :
                                                    <span class="font-semibold text-green-600">
                                                        {{ number_format($customer->credit_available, 0, ',', ' ') }} FCFA
                                                    </span>
                                                </div>

                                            </div>

                                        @else

                                            <span class="inline-flex px-3 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">
                                                Non autorisé
                                            </span>

                                        @endif

                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if($customer->is_active)
                                            <span class="inline-flex px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                                Actif
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">
                                                Inactif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right whitespace-nowrap">

                                        @can('customers.view')
                                            <a
                                                href="{{ route('customers.show', $customer) }}"
                                                class="mr-2 text-sm font-medium text-indigo-600 hover:text-indigo-800"
                                            >
                                                Voir
                                            </a>
                                        @endcan

                                        @can('customers.edit')

                                            <a
                                                href="{{ route('customers.edit', $customer) }}"
                                                class="mr-3 text-sm font-medium text-yellow-600 hover:text-yellow-800"
                                            >
                                                Modifier
                                            </a>

                                            @if($customer->is_active)

                                                <form
                                                    method="POST"
                                                    action="{{ route('customers.deactivate', $customer) }}"
                                                    class="inline"
                                                    onsubmit="return confirm('Voulez-vous vraiment désactiver ce client ?')"
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
                                                    action="{{ route('customers.activate', $customer) }}"
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

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="6"
                                        class="px-6 py-12 text-center"
                                    >
                                        <p class="text-sm font-medium text-gray-900">
                                            Aucun client trouvé
                                        </p>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Commencez par créer votre premier client.
                                        </p>

                                        @can('customers.create')
                                            <a
                                                href="{{ route('customers.create') }}"
                                                class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
                                            >
                                                + Nouveau client
                                            </a>
                                        @endcan
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                @if($customers->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $customers->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>

</x-app-layout>
