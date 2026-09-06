<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Rapports
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Consultez les différents rapports de votre activité.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">

                {{-- Rapport des ventes --}}
                @can('reports.sales.view')
                    <a
                        href="{{ route('reports.sales') }}"
                        class="p-6 transition bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md hover:border-indigo-300"
                    >
                        <div class="flex items-center justify-center w-12 h-12 mb-4 bg-indigo-100 rounded-lg">
                            <svg
                                class="w-6 h-6 text-indigo-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2z"
                                />
                            </svg>
                        </div>

                        <h3 class="text-lg font-semibold text-gray-900">
                            Rapport des ventes
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Analysez les ventes, le chiffre d'affaires,
                            les encaissements et les crédits.
                        </p>

                        <span class="inline-flex mt-4 text-sm font-medium text-indigo-600">
                            Consulter →
                        </span>
                    </a>
                @endcan

                {{-- Rapport du stock --}}
                @can('reports.stock.view')
                    <a
                        href="{{ route('reports.stock') }}"
                        class="block p-6 transition bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md hover:border-green-300"
                    >
                        <div class="flex items-center justify-center w-12 h-12 mb-4 bg-green-100 rounded-lg">
                            <svg
                                class="w-6 h-6 text-green-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                />
                            </svg>
                        </div>

                        <h3 class="text-lg font-semibold text-gray-900">
                            Rapport du stock
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Consultez les entrées, sorties,
                            ajustements et niveaux de stock.
                        </p>

                        <span class="inline-flex mt-4 text-sm font-medium text-green-600">
                            Consulter →
                        </span>
                    </a>
                @endcan

                {{-- Rapport financier --}}
                @can('reports.financial.view')
                    <a
                        href="{{ route('reports.financial') }}"
                        class="block p-6 transition bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md hover:border-orange-300"
                    >
                        <div class="flex items-center justify-center w-12 h-12 mb-4 bg-orange-100 rounded-lg">
                            <svg
                                class="w-6 h-6 text-orange-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m0-10a6 6 0 100 12 6 6 0 000-12z"
                                />
                            </svg>
                        </div>

                        <h3 class="text-lg font-semibold text-gray-900">
                            Rapport financier
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Suivez les paiements, remboursements,
                            crédits et résultats financiers.
                        </p>

                        <span class="inline-flex mt-4 text-sm font-medium text-orange-600">
                            Consulter →
                        </span>
                    </a>
                @endcan

            </div>

        </div>
    </div>

</x-app-layout>
