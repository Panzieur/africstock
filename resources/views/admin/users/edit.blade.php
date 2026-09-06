<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Modifier le rôle
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $user->name }}
                        </h3>

                        <p class="text-sm text-gray-500">
                            {{ $user->email }}
                        </p>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('admin.users.update', $user) }}"
                    >

                        @csrf
                        @method('PUT')

                        <div>

                            <label
                                for="role"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Rôle
                            </label>

                            <select
                                name="role"
                                id="role"
                                class="block w-full mt-2 border-gray-300 rounded-lg"
                            >

                                @foreach($roles as $role)

                                    <option
                                        value="{{ $role->name }}"
                                        @selected($user->hasRole($role->name))
                                    >
                                        {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                    </option>

                                @endforeach

                            </select>

                            @error('role')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        <div class="flex justify-end gap-3 mt-6">

                            <a
                                href="{{ route('admin.users.index') }}"
                                class="px-4 py-2 text-gray-700"
                            >
                                Annuler
                            </a>

                            <button
                                type="submit"
                                class="px-5 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                            >
                                Enregistrer
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>
