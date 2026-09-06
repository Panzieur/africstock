<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Catégories
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Gérez les catégories de vos produits.
                </p>
            </div>

            @can('categories.create')
                <a
                    href="{{ route('categories.create') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
                >
                    + Nouvelle catégorie
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Messages --}}
            @if(session('success'))
                <div class="px-4 py-3 mb-6 text-sm text-green-700 border border-green-200 rounded-lg bg-green-50">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="px-4 py-3 mb-6 text-sm text-red-700 border border-red-200 rounded-lg bg-red-50">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Recherche et filtres -->
            <div class="p-4 mb-6 bg-white rounded-lg shadow-sm">
                <form method="GET" action="{{ route('categories.index') }}"
                    class="flex flex-col gap-4 md:flex-row md:items-end">

                    <!-- Recherche -->
                    <div class="flex-1">
                        <label for="search"
                            class="block text-sm font-medium text-gray-700">
                            Rechercher
                        </label>

                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Rechercher une catégorie..."
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                    <!-- Statut -->
                    <div class="w-full md:w-48">
                        <label for="status"
                            class="block text-sm font-medium text-gray-700">
                            Statut
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Tous</option>
                            <option value="active" @selected(request('status') === 'active')}>
                                Actives
                            </option>
                            <option value="inactive" @selected(request('status') === 'inactive')}>
                                Inactives
                            </option>
                        </select>
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-2">
                        <button
                            type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
                        >
                            Rechercher
                        </button>

                        <a
                            href="{{ route('categories.index') }}"
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                        >
                            Réinitialiser
                        </a>
                    </div>

                </form>
            </div>

            {{-- Tableau --}}
            <div class="overflow-hidden bg-white shadow-sm rounded-xl ring-1 ring-gray-200">

                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900">
                        Liste des catégories
                    </h3>
                </div>

                @if($categories->count())

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Catégorie
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Produits
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                        Statut
                                    </th>

                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">

                                @foreach($categories as $category)

                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-4">

                                            <div class="font-medium text-gray-900">
                                                {{ $category->name }}
                                            </div>

                                            @if($category->description)
                                                <div class="mt-1 text-sm text-gray-500">
                                                    {{ $category->description }}
                                                </div>
                                            @endif

                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $category->products_count }}
                                        </td>

                                        <td class="px-6 py-4">

                                            @if($category->is_active)

                                                <span class="inline-flex px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                                    Active
                                                </span>

                                            @else

                                                <span class="inline-flex px-3 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded-full">
                                                    Inactive
                                                </span>

                                            @endif

                                        </td>

                                        <td class="px-6 py-4">

                                            <div class="flex justify-end gap-2">

                                                @can('categories.edit')
                                                    <a
                                                        href="{{ route('categories.edit', $category) }}"
                                                        class="px-3 py-2 text-sm font-medium text-indigo-600 rounded-lg hover:bg-indigo-50"
                                                    >
                                                        Modifier
                                                    </a>
                                                @endcan

                                                @can('categories.delete')

                                                    @if($category->products_count === 0)

                                                        <form
                                                            method="POST"
                                                            action="{{ route('categories.destroy', $category) }}"
                                                            onsubmit="return confirm('Voulez-vous vraiment supprimer cette catégorie ?')"
                                                        >
                                                            @csrf
                                                            @method('DELETE')

                                                            <button
                                                                type="submit"
                                                                class="px-3 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50"
                                                            >
                                                                Supprimer
                                                            </button>
                                                        </form>

                                                    @endif

                                                @endcan

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    {{-- Pagination --}}
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $categories->links() }}
                    </div>

                @else

                    <div class="px-6 py-12 text-center">

                        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-gray-100 rounded-full">
                            📦
                        </div>

                        <h3 class="text-lg font-semibold text-gray-900">
                            Aucune catégorie
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Commencez par créer votre première catégorie.
                        </p>

                        @can('categories.create')
                            <a
                                href="{{ route('categories.create') }}"
                                class="inline-flex px-4 py-2 mt-5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
                            >
                                Créer une catégorie
                            </a>
                        @endcan

                    </div>

                @endif

            </div>

        </div>
    </div>

</x-app-layout>
