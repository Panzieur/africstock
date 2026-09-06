<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Nouveau client
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Ajoutez un nouveau client à votre base.
                </p>
            </div>

            <a
                href="{{ route('customers.index') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
            >
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl px-4 mx-auto sm:px-6 lg:px-8">

            {{-- Erreurs de validation --}}
            @if($errors->any())
                <div class="px-4 py-3 mb-6 border border-red-200 rounded-lg bg-red-50">
                    <p class="text-sm font-semibold text-red-800">
                        Veuillez corriger les erreurs suivantes :
                    </p>

                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
                <div class="p-6 sm:p-8">

                    <form
                        method="POST"
                        action="{{ route('customers.store') }}"
                        class="space-y-6"
                    >
                        @csrf

                        {{-- Nom --}}
                        <div>
                            <label
                                for="name"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Nom du client <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Ex. Boutique Wend-Kouni"
                            >
                        </div>

                        {{-- Téléphone + Email --}}
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                            <div>
                                <label
                                    for="phone"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Téléphone
                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    id="phone"
                                    value="{{ old('phone') }}"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Ex. 70 00 00 00"
                                >
                            </div>

                            <div>
                                <label
                                    for="email"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    value="{{ old('email') }}"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Ex. client@example.com"
                                >
                            </div>

                        </div>

                        {{-- Adresse --}}
                        <div>
                            <label
                                for="address"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Adresse
                            </label>

                            <textarea
                                name="address"
                                id="address"
                                rows="3"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Adresse ou localisation du client"
                            >{{ old('address') }}</textarea>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label
                                for="notes"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Notes
                            </label>

                            <textarea
                                name="notes"
                                id="notes"
                                rows="4"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Informations complémentaires sur le client"
                            >{{ old('notes') }}</textarea>
                        </div>

                        {{-- Statut --}}
                        <div class="flex items-center">
                            <input
                                type="hidden"
                                name="is_active"
                                value="0"
                            >

                            <input
                                type="checkbox"
                                name="is_active"
                                id="is_active"
                                value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="text-indigo-600 border-gray-300 rounded shadow-sm focus:ring-indigo-500"
                            >

                            <label
                                for="is_active"
                                class="ml-2 text-sm font-medium text-gray-700"
                            >
                                Client actif
                            </label>
                        </div>

                        {{-- Crédit --}}
                        <div class="md:col-span-2 pt-4 mt-2 border-t border-gray-200">

                            <h3 class="mb-4 text-sm font-semibold text-gray-900">
                                Conditions de crédit
                            </h3>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                {{-- Crédit autorisé --}}
                                <div>
                                    <label
                                        for="credit_allowed"
                                        class="block mb-1 text-sm font-medium text-gray-700"
                                    >
                                        Crédit autorisé
                                    </label>

                                    <select
                                        id="credit_allowed"
                                        name="credit_allowed"
                                        class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        <option value="0" {{ old('credit_allowed', '0') == '0' ? 'selected' : '' }}>
                                            Non
                                        </option>

                                        <option value="1" {{ old('credit_allowed') == '1' ? 'selected' : '' }}>
                                            Oui
                                        </option>
                                    </select>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Autorise ce client à effectuer des achats à crédit.
                                    </p>
                                </div>

                                {{-- Limite de crédit --}}
                                <div>
                                    <label
                                        for="credit_limit"
                                        class="block mb-1 text-sm font-medium text-gray-700"
                                    >
                                        Limite de crédit
                                    </label>

                                    <input
                                        type="number"
                                        id="credit_limit"
                                        name="credit_limit"
                                        min="0"
                                        step="0.01"
                                        value="{{ old('credit_limit', 0) }}"
                                        class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >

                                    <p class="mt-1 text-xs text-gray-500">
                                        Montant maximum que ce client peut avoir en crédit.
                                    </p>
                                </div>

                            </div>

                        </div>

                        {{-- Boutons --}}
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">

                            <a
                                href="{{ route('customers.index') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                            >
                                Annuler
                            </a>

                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Créer le client
                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
