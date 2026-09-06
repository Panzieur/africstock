<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Gestion des utilisateurs
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="px-4 py-3 mb-6 text-green-800 bg-green-100 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 mb-6 border border-red-200 rounded-lg bg-red-50">
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">
                                        Utilisateur
                                    </th>

                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">
                                        Email
                                    </th>

                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">
                                        Rôle
                                    </th>

                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">
                                        État
                                    </th>

                                    <th class="px-6 py-3 text-xs font-medium text-right text-gray-500 uppercase">
                                        Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">

                                @forelse($users as $user)

                                    <tr>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">
                                                {{ $user->name }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                            {{ $user->email }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @if($user->roles->count())
                                                <span class="px-3 py-1 text-sm text-blue-800 bg-blue-100 rounded-full">
                                                    {{ $user->roles->first()->name }}
                                                </span>
                                            @else
                                                <span class="px-3 py-1 text-sm text-gray-600 bg-gray-100 rounded-full">
                                                    Aucun rôle
                                                </span>
                                            @endif

                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->is_active)
                                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                                    Actif
                                                </span>
                                            @else
                                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                                                    Inactif
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-right whitespace-nowrap">

                                            @if(
                                                auth()->user()->hasRole('super-admin') ||
                                                (
                                                    auth()->user()->hasRole('admin') &&
                                                    !$user->hasAnyRole(['admin', 'super-admin']) &&
                                                    auth()->id() !== $user->id
                                                )
                                            )
                                                <a
                                                    href="{{ route('admin.users.edit', $user) }}"
                                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-700 rounded-lg bg-indigo-50 hover:bg-indigo-100"
                                                >
                                                    Modifier
                                                </a>
                                            @endif

                                            @if(
                                                auth()->user()->hasRole('super-admin') &&
                                                auth()->id() !== $user->id &&
                                                !$user->hasRole('super-admin')
                                            )
                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.users.destroy', $user) }}"
                                                    class="inline"
                                                    onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ? Cette action est irréversible.');"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center px-3 py-2 ml-2 text-sm font-medium text-red-700 rounded-lg bg-red-50 hover:bg-red-100"
                                                    >
                                                        Supprimer
                                                    </button>
                                                </form>
                                            @endif


                                            @if(
                                                auth()->user()->hasRole('super-admin') &&
                                                auth()->id() !== $user->id &&
                                                !$user->hasRole('super-admin')
                                            )

                                                @if($user->is_active)

                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.users.deactivate', $user) }}"
                                                        class="inline"
                                                        onsubmit="return confirm('Voulez-vous vraiment désactiver ce compte ? L’historique de l’utilisateur sera conservé.');"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center px-3 py-2 ml-2 text-sm font-medium text-orange-700 rounded-lg bg-orange-50 hover:bg-orange-100"
                                                        >
                                                            Désactiver
                                                        </button>
                                                    </form>

                                                @else

                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.users.activate', $user) }}"
                                                        class="inline"
                                                        onsubmit="return confirm('Voulez-vous vraiment réactiver ce compte ?');"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center px-3 py-2 ml-2 text-sm font-medium text-green-700 rounded-lg bg-green-50 hover:bg-green-100"
                                                        >
                                                            Réactiver
                                                        </button>
                                                    </form>

                                                @endif

                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                            Aucun utilisateur trouvé.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>
