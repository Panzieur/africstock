<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Nouvel inventaire
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Créez une session d'inventaire pour compter physiquement vos produits.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl px-4 mx-auto sm:px-6 lg:px-8">

            {{-- Erreurs --}}
            @if($errors->any())
                <div class="p-4 mb-6 border border-red-200 rounded-lg bg-red-50">
                    <div class="font-semibold text-red-800">
                        Vérifiez les informations saisies.
                    </div>

                    <ul class="pl-5 mt-2 space-y-1 text-sm text-red-700 list-disc">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white border border-gray-200 shadow-sm rounded-xl">

                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">
                        Informations de l'inventaire
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Renseignez les informations générales de cette session.
                    </p>
                </div>

                <form
                    method="POST"
                    action="{{ route('inventories.store') }}"
                    class="p-6 space-y-6"
                >
                    @csrf

                    {{-- Nom --}}
                    <div>
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Nom de l'inventaire
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            placeholder="Ex. Inventaire magasin septembre 2026"
                            required
                            class="block w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        <p class="mt-1 text-xs text-gray-500">
                            Donnez un nom permettant d'identifier facilement cet inventaire.
                        </p>
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
                            placeholder="Informations ou observations concernant cet inventaire..."
                            class="block w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ old('notes') }}</textarea>

                        <p class="mt-1 text-xs text-gray-500">
                            Facultatif.
                        </p>
                    </div>

                    {{-- Information --}}
                    <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                        <div class="flex gap-3">
                            <div class="text-blue-600">
                                ℹ️
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-blue-800">
                                    Comment fonctionne l'inventaire ?
                                </p>

                                <p class="mt-1 text-sm text-blue-700">
                                    La création de cet inventaire ne modifiera pas immédiatement
                                    les stocks. Vous pourrez ensuite sélectionner les produits
                                    et saisir les quantités réellement comptées.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">

                        <a
                            href="{{ route('inventories.index') }}"
                            class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                        >
                            Annuler
                        </a>

                        <button
                            type="submit"
                            class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                        >
                            Créer l'inventaire
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>
