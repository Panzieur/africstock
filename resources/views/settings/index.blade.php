<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                    Paramètres
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Configurez le fonctionnement général d'AfricStock.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Message de succès --}}
            @if(session('success'))
                <div
                    class="p-4 mb-5 text-sm text-green-700 bg-green-100 border border-green-200 rounded-lg dark:bg-green-900/30 dark:text-green-400 dark:border-green-800"
                >
                    {{ session('success') }}
                </div>
            @endif

            {{-- Erreurs de validation --}}
            @if($errors->any())
                <div
                    class="p-4 mb-5 text-sm text-red-700 bg-red-100 border border-red-200 rounded-lg dark:bg-red-900/30 dark:text-red-400 dark:border-red-800"
                >
                    <p class="font-semibold mb-2">
                        Certaines informations doivent être corrigées :
                    </p>

                    <ul class="space-y-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('settings.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-5">

                    {{-- ===================================================== --}}
                    {{-- ENTREPRISE --}}
                    {{-- ===================================================== --}}

                    <div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">

                        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-9 h-9 text-blue-600 bg-blue-100 rounded-lg dark:bg-blue-900/30 dark:text-blue-400">
                                    🏢
                                </div>

                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        Informations de l'entreprise
                                    </h3>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Informations affichées dans AfricStock.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                            <div>
                                <label for="company_name"
                                       class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nom de l'entreprise
                                </label>

                                <input
                                    type="text"
                                    id="company_name"
                                    name="company_name"
                                    value="{{ old('company_name', $settings->company_name) }}"
                                    required
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                            </div>

                            <div>
                                <label for="company_phone"
                                       class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Téléphone
                                </label>

                                <input
                                    type="text"
                                    id="company_phone"
                                    name="company_phone"
                                    value="{{ old('company_phone', $settings->company_phone) }}"
                                    placeholder="+226 ..."
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                            </div>

                            <div>
                                <label for="company_email"
                                       class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    id="company_email"
                                    name="company_email"
                                    value="{{ old('company_email', $settings->company_email) }}"
                                    placeholder="contact@entreprise.com"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                            </div>

                            <div>
                                <label for="company_address"
                                       class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Adresse
                                </label>

                                <input
                                    type="text"
                                    id="company_address"
                                    name="company_address"
                                    value="{{ old('company_address', $settings->company_address) }}"
                                    placeholder="Ouagadougou, Burkina Faso"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                            </div>

                        </div>
                    </div>


                    {{-- ===================================================== --}}
                    {{-- GENERAL --}}
                    {{-- ===================================================== --}}

                    <div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">

                        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-9 h-9 text-gray-600 bg-gray-100 rounded-lg dark:bg-gray-700 dark:text-gray-300">
                                    ⚙️
                                </div>

                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        Configuration générale
                                    </h3>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Paramètres régionaux de l'application.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2 lg:grid-cols-4">

                            <div>
                                <label for="currency"
                                       class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Devise
                                </label>

                                <select
                                    id="currency"
                                    name="currency"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                                    <option value="FCFA" @selected(old('currency', $settings->currency) === 'FCFA')>
                                        FCFA
                                    </option>

                                    <option value="EUR" @selected(old('currency', $settings->currency) === 'EUR')>
                                        EUR
                                    </option>

                                    <option value="USD" @selected(old('currency', $settings->currency) === 'USD')>
                                        USD
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label for="timezone"
                                       class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Fuseau horaire
                                </label>

                                <select
                                    id="timezone"
                                    name="timezone"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                                    <option value="Africa/Ouagadougou" @selected(old('timezone', $settings->timezone) === 'Africa/Ouagadougou')>
                                        Ouagadougou
                                    </option>

                                    <option value="Africa/Abidjan" @selected(old('timezone', $settings->timezone) === 'Africa/Abidjan')>
                                        Abidjan
                                    </option>

                                    <option value="Africa/Lagos" @selected(old('timezone', $settings->timezone) === 'Africa/Lagos')>
                                        Lagos
                                    </option>

                                    <option value="Africa/Accra" @selected(old('timezone', $settings->timezone) === 'Africa/Accra')>
                                        Accra
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label for="date_format"
                                       class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Format de date
                                </label>

                                <select
                                    id="date_format"
                                    name="date_format"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                                    <option value="d/m/Y" @selected(old('date_format', $settings->date_format) === 'd/m/Y')>
                                        31/12/2026
                                    </option>

                                    <option value="Y-m-d" @selected(old('date_format', $settings->date_format) === 'Y-m-d')>
                                        2026-12-31
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label for="language"
                                       class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Langue
                                </label>

                                <select
                                    id="language"
                                    name="language"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                                    <option value="fr" @selected(old('language', $settings->language) === 'fr')>
                                        Français
                                    </option>

                                    <option value="en" @selected(old('language', $settings->language) === 'en')>
                                        English
                                    </option>
                                </select>
                            </div>

                        </div>
                    </div>


                    {{-- ===================================================== --}}
                    {{-- VENTES --}}
                    {{-- ===================================================== --}}

                    <div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">

                        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-9 h-9 text-green-600 bg-green-100 rounded-lg dark:bg-green-900/30 dark:text-green-400">
                                    🧾
                                </div>

                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        Ventes
                                    </h3>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Configurez les règles de vente.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                            <label class="flex items-center justify-between gap-4 p-4 border border-gray-200 rounded-lg dark:border-gray-700">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        Autoriser les ventes à crédit
                                    </p>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Permettre aux vendeurs d'enregistrer des ventes à crédit.
                                    </p>
                                </div>

                                <input
                                    type="checkbox"
                                    name="allow_credit_sales"
                                    value="1"
                                    @checked(old('allow_credit_sales', $settings->allow_credit_sales))
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                >
                            </label>


                            <label class="flex items-center justify-between gap-4 p-4 border border-gray-200 rounded-lg dark:border-gray-700">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        Autoriser le stock négatif
                                    </p>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Permettre une vente lorsque le stock est insuffisant.
                                    </p>
                                </div>

                                <input
                                    type="checkbox"
                                    name="allow_negative_stock"
                                    value="1"
                                    @checked(old('allow_negative_stock', $settings->allow_negative_stock))
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                >
                            </label>


                            <label class="flex items-center justify-between gap-4 p-4 border border-gray-200 rounded-lg dark:border-gray-700">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        Autoriser les paiements partiels
                                    </p>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Permettre plusieurs paiements pour une même vente.
                                    </p>
                                </div>

                                <input
                                    type="checkbox"
                                    name="allow_partial_payments"
                                    value="1"
                                    @checked(old('allow_partial_payments', $settings->allow_partial_payments))
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                >
                            </label>


                            <div>
                                <label for="credit_due_days"
                                       class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Délai de paiement (jours)
                                </label>

                                <input
                                    type="number"
                                    id="credit_due_days"
                                    name="credit_due_days"
                                    min="1"
                                    max="365"
                                    value="{{ old('credit_due_days', $settings->credit_due_days) }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                            </div>


                            <div>
                                <label for="sale_prefix"
                                       class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Préfixe des ventes
                                </label>

                                <input
                                    type="text"
                                    id="sale_prefix"
                                    name="sale_prefix"
                                    maxlength="20"
                                    value="{{ old('sale_prefix', $settings->sale_prefix) }}"
                                    placeholder="VTE"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                            </div>

                        </div>
                    </div>


                    {{-- ===================================================== --}}
                    {{-- STOCK --}}
                    {{-- ===================================================== --}}

                    <div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">

                        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-9 h-9 text-orange-600 bg-orange-100 rounded-lg dark:bg-orange-900/30 dark:text-orange-400">
                                    📦
                                </div>

                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        Stock
                                    </h3>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Configuration de la gestion du stock.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                            <label class="flex items-center justify-between gap-4 p-4 border border-gray-200 rounded-lg dark:border-gray-700">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        Autoriser les ajustements
                                    </p>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Autoriser les corrections manuelles du stock.
                                    </p>
                                </div>

                                <input
                                    type="checkbox"
                                    name="allow_stock_adjustments"
                                    value="1"
                                    @checked(old('allow_stock_adjustments', $settings->allow_stock_adjustments))
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                >
                            </label>


                            <div>
                                <label for="default_minimum_stock"
                                       class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Seuil de stock par défaut
                                </label>

                                <input
                                    type="number"
                                    id="default_minimum_stock"
                                    name="default_minimum_stock"
                                    min="0"
                                    value="{{ old('default_minimum_stock', $settings->default_minimum_stock) }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                            </div>

                        </div>
                    </div>


                    {{-- ===================================================== --}}
                    {{-- NOTIFICATIONS --}}
                    {{-- ===================================================== --}}

                    <div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">

                        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-9 h-9 text-purple-600 bg-purple-100 rounded-lg dark:bg-purple-900/30 dark:text-purple-400">
                                    🔔
                                </div>

                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        Notifications
                                    </h3>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Activez les alertes importantes.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                            <label class="flex items-center justify-between gap-4 p-4 border border-gray-200 rounded-lg dark:border-gray-700">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        Stock faible
                                    </p>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Activer les alertes concernant les produits en stock faible.
                                    </p>
                                </div>

                                <input
                                    type="checkbox"
                                    name="low_stock_notifications"
                                    value="1"
                                    @checked(old('low_stock_notifications', $settings->low_stock_notifications))
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                >
                            </label>


                            <label class="flex items-center justify-between gap-4 p-4 border border-gray-200 rounded-lg dark:border-gray-700">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        Crédits clients
                                    </p>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Activer les alertes concernant les crédits clients.
                                    </p>
                                </div>

                                <input
                                    type="checkbox"
                                    name="credit_notifications"
                                    value="1"
                                    @checked(old('credit_notifications', $settings->credit_notifications))
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                >
                            </label>

                        </div>
                    </div>


                    {{-- ===================================================== --}}
                    {{-- SYSTEME --}}
                    {{-- ===================================================== --}}

                    <div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">

                        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-9 h-9 text-red-600 bg-red-100 rounded-lg dark:bg-red-900/30 dark:text-red-400">
                                    🛠️
                                </div>

                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        Système
                                    </h3>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Paramètres techniques de l'application.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-5">

                            <label class="flex items-center justify-between gap-4 p-4 border border-red-200 rounded-lg dark:border-red-900/50">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        Mode maintenance
                                    </p>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Mettre temporairement AfricStock en maintenance.
                                    </p>
                                </div>

                                <input
                                    type="checkbox"
                                    name="maintenance_mode"
                                    value="1"
                                    @checked(old('maintenance_mode', $settings->maintenance_mode))
                                    class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                >
                            </label>

                        </div>
                    </div>


                    {{-- ===================================================== --}}
                    {{-- BOUTON --}}
                    {{-- ===================================================== --}}

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Enregistrer les paramètres
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</x-app-layout>