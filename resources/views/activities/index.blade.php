<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Journal des activités
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Historique des actions effectuées sur AfricStock
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Filtres --}}
            <div class="mb-6 rounded-xl bg-white p-5 shadow-sm">
                <form method="GET" action="{{ route('activities.index') }}">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                        <div>
                            <label for="start_date"
                                   class="mb-1 block text-sm font-medium text-gray-700">
                                Date de début
                            </label>

                            <input
                                type="date"
                                name="start_date"
                                id="start_date"
                                value="{{ $startDate }}"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div>
                            <label for="end_date"
                                   class="mb-1 block text-sm font-medium text-gray-700">
                                Date de fin
                            </label>

                            <input
                                type="date"
                                name="end_date"
                                id="end_date"
                                value="{{ $endDate }}"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        @canany(['activities.all.view', 'activities.stock.view', 'activities.payments.view'])
                            <div>
                                <label for="user_id"
                                    class="mb-1 block text-sm font-medium text-gray-700">
                                    Utilisateur
                                </label>

                                <select
                                    name="user_id"
                                    id="user_id"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="">Tous les utilisateurs</option>

                                    @foreach(\App\Models\User::orderBy('name')->get() as $user)
                                        <option
                                            value="{{ $user->id }}"
                                            @selected(request('user_id') == $user->id)
                                        >
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endcanany

                        <div class="flex items-end">
                            <button
                                type="submit"
                                class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700"
                            >
                                Filtrer
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            {{-- Journal --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm">

                <div class="border-b border-gray-200 px-5 py-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Activités
                    </h3>
                </div>

                @if($activities->count())

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Date
                                    </th>

                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Utilisateur
                                    </th>

                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Action
                                    </th>

                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Description
                                    </th>

                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Adresse IP
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 bg-white">

                                @foreach($activities as $activity)

                                    <tr class="hover:bg-gray-50">

                                        <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">
                                            {{ $activity->created_at?->format('d/m/Y H:i') }}
                                        </td>

                                        <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-gray-800">
                                            {{ $activity->user?->name ?? 'Système' }}
                                        </td>

                                        <td class="whitespace-nowrap px-5 py-4">
                                            <span class="inline-flex rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-medium text-indigo-700">
                                                {{ $activity->action }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-4 text-sm text-gray-700">
                                            {{ $activity->description }}
                                        </td>

                                        <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500">
                                            {{ $activity->ip_address ?? '-' }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>
                    </div>

                    <div class="border-t border-gray-200 px-5 py-4">
                        {{ $activities->links() }}
                    </div>

                @else

                    <div class="px-5 py-12 text-center">
                        <p class="text-sm text-gray-500">
                            Aucune activité enregistrée pour cette période.
                        </p>
                    </div>

                @endif

            </div>

        </div>
    </div>
</x-app-layout>
