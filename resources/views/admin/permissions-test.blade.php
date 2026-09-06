<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Test des permissions
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Informations utilisateur --}}
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h3 class="mb-4 text-lg font-semibold text-gray-900">
                        Utilisateur connecté
                    </h3>

                    <div class="space-y-2">
                        <p>
                            <strong>Nom :</strong>
                            {{ $user->name }}
                        </p>

                        <p>
                            <strong>Email :</strong>
                            {{ $user->email }}
                        </p>

                        <p>
                            <strong>Rôle(s) :</strong>

                            @foreach($user->getRoleNames() as $role)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $role }}
                                </span>
                            @endforeach
                        </p>

                    </div>

                </div>
            </div>


            {{-- Permissions --}}
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <h3 class="mb-6 text-lg font-semibold text-gray-900">
                        Permissions disponibles
                    </h3>

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">
                                        Permission
                                    </th>

                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">
                                        Statut
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">

                                @foreach($permissions as $permission)

                                    <tr>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $permission }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @can($permission)

                                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                    ✓ Autorisé
                                                </span>

                                            @else

                                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                                    ✕ Refusé
                                                </span>

                                            @endcan

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>
