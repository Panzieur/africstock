<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Modifier la catégorie
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Modifiez les informations de cette catégorie.
            </p>
        </div>
    </x-slot>

    <div class="py-8">

        <div class="max-w-3xl px-4 mx-auto sm:px-6 lg:px-8">

            <div class="p-6 bg-white shadow-sm rounded-xl ring-1 ring-gray-200">

                <form
                    method="POST"
                    action="{{ route('categories.update', $category) }}"
                    class="space-y-6"
                >

                    @csrf
                    @method('PUT')

                    {{-- Nom --}}
                    <div>

                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Nom de la catégorie
                        </label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $category->name) }}"
                            required
                            class="block w-full mt-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('name')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Description --}}
                    <div>

                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="block w-full mt-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ old('description', $category->description) }}</textarea>

                        @error('description')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Statut --}}
                    <div class="flex items-center">

                        <input
                            id="is_active"
                            name="is_active"
                            type="checkbox"
                            value="1"
                            {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                            class="text-indigo-600 border-gray-300 rounded shadow-sm focus:ring-indigo-500"
                        >

                        <label
                            for="is_active"
                            class="ml-3 text-sm text-gray-700"
                        >
                            Catégorie active
                        </label>

                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-6 border-t">

                        <a
                            href="{{ route('categories.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100"
                        >
                            Annuler
                        </a>

                        <button
                            type="submit"
                            class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                        >
                            Enregistrer les modifications
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
