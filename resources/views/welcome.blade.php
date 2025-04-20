<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Système de gestion complet pour l'élevage de lapins - Suivi des reproductions, traitements, alimentation et plus encore">

        <title>{{ config('app.name', 'Gestion Cuniculture') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50 dark:bg-gray-900">
        <!-- Navigation -->
        <header class="fixed w-full bg-white dark:bg-gray-800 shadow-sm z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"></path>
                                <path d="M22 20a7 7 0 0 0-14 0"></path>
                                <path d="M11 14h4"></path>
                                <path d="M11 18h6"></path>
                            </svg>
                            <span class="ml-2 text-xl font-bold text-gray-900 dark:text-white">Lapin Rapide</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        @if (Route::has('login'))
                            <div class="space-x-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Tableau de bord
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                                        Connexion
                                    </a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Inscription
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative pt-24 pb-32 flex content-center items-center justify-center" style="min-height: 95vh;">
            <div class="absolute top-0 w-full h-full bg-center bg-cover" style="background-image: url('https://images.unsplash.com/photo-1591382386627-349b692688ff?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80');">
                <span id="blackOverlay" class="w-full h-full absolute opacity-50 bg-black"></span>
            </div>
            <div class="container relative mx-auto">
                <div class="items-center flex flex-wrap">
                    <div class="w-full lg:w-6/12 px-4 ml-auto mr-auto text-center">
                        <div class="pr-12">
                            <h1 class="text-white font-bold text-5xl">
                                Gestion complète d'élevage cunicole
                            </h1>
                            <p class="mt-4 text-lg text-gray-200">
                                Une solution intégrée pour gérer efficacement votre élevage de lapins, de la reproduction à la vente.
                            </p>
                            <div class="mt-8">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:text-lg">
                                        Accéder au tableau de bord
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:text-lg">
                                        Commencer maintenant
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">Fonctionnalités</h2>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                        Tout ce dont vous avez besoin pour gérer votre élevage
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-300 lg:mx-auto">
                        Notre plateforme offre des outils complets pour optimiser chaque aspect de votre élevage cunicole.
                    </p>
                </div>

                <div class="mt-16">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                        <!-- Feature 1 -->
                        <div class="flex flex-col bg-gray-50 dark:bg-gray-700 overflow-hidden rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl">
                            <div class="p-6">
                                <div class="inline-flex items-center justify-center p-3 bg-indigo-100 dark:bg-indigo-800 rounded-md shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Gestion des reproductions</h3>
                                <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                    Suivez les cycles de reproduction, les portées et la généalogie de vos lapins avec précision.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="flex flex-col bg-gray-50 dark:bg-gray-700 overflow-hidden rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl">
                            <div class="p-6">
                                <div class="inline-flex items-center justify-center p-3 bg-indigo-100 dark:bg-indigo-800 rounded-md shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Suivi sanitaire</h3>
                                <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                    Planifiez et suivez les traitements médicaux, les vaccinations et l'état de santé de votre cheptel.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="flex flex-col bg-gray-50 dark:bg-gray-700 overflow-hidden rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl">
                            <div class="p-6">
                                <div class="inline-flex items-center justify-center p-3 bg-indigo-100 dark:bg-indigo-800 rounded-md shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Gestion des stocks</h3>
                                <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                    Contrôlez vos stocks d'aliments, de médicaments et de fournitures pour éviter les ruptures.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 4 -->
                        <div class="flex flex-col bg-gray-50 dark:bg-gray-700 overflow-hidden rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl">
                            <div class="p-6">
                                <div class="inline-flex items-center justify-center p-3 bg-indigo-100 dark:bg-indigo-800 rounded-md shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Statistiques et rapports</h3>
                                <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                    Analysez les performances de votre élevage grâce à des tableaux de bord et des rapports détaillés.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 5 -->
                        <div class="flex flex-col bg-gray-50 dark:bg-gray-700 overflow-hidden rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl">
                            <div class="p-6">
                                <div class="inline-flex items-center justify-center p-3 bg-indigo-100 dark:bg-indigo-800 rounded-md shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Planification alimentaire</h3>
                                <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                    Créez des plans d'alimentation adaptés à chaque stade de développement de vos lapins.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 6 -->
                        <div class="flex flex-col bg-gray-50 dark:bg-gray-700 overflow-hidden rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl">
                            <div class="p-6">
                                <div class="inline-flex items-center justify-center p-3 bg-indigo-100 dark:bg-indigo-800 rounded-md shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Gestion financière</h3>
                                <p class="mt-2 text-base text-gray-500 dark:text-gray-300">
                                    Suivez vos revenus, dépenses et calculez la rentabilité de votre exploitation.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonial Section -->
        <section class="py-20 bg-gray-50 dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">Témoignages</h2>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                        Ce que disent nos utilisateurs
                    </p>
                </div>
                <div class="mt-16 max-w-3xl mx-auto text-center">
                    <div class="relative">
                        <svg class="absolute top-0 left-0 transform -translate-x-8 -translate-y-8 h-16 w-16 text-indigo-200 dark:text-indigo-700" fill="currentColor" viewBox="0 0 32 32" aria-hidden="true">
                            <path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z" />
                        </svg>
                        <p class="relative text-xl text-gray-500 dark:text-gray-300">
                            Depuis que j'utilise cette application, la gestion de mon élevage est devenue beaucoup plus simple. Je peux suivre facilement mes reproductions, planifier les traitements et analyser mes performances. Un outil indispensable pour tout éleveur sérieux !
                        </p>
                    </div>
                    <div class="mt-8">
                        <div class="md:flex md:items-center md:justify-center">
                            <div class="md:flex-shrink-0">
                                <img class="mx-auto h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                            </div>
                            <div class="mt-3 text-center md:mt-0 md:ml-4 md:flex md:items-center">
                                <div class="text-base font-medium text-gray-900 dark:text-white">Marie Dupont</div>
                                <svg class="hidden md:block mx-1 h-5 w-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M11 0h3L9 20H6l5-20z" />
                                </svg>
                                <div class="text-base font-medium text-gray-500 dark:text-gray-300">Éleveuse professionnelle, Bénin</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-indigo-700">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
                <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    <span class="block">Prêt à optimiser votre élevage ?</span>
                    <span class="block text-indigo-200">Commencez à utiliser notre plateforme dès aujourd'hui.</span>
                </h2>
                <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                    <div class="inline-flex rounded-md shadow">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50">
                                Accéder au tableau de bord
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50">
                                Créer un compte
                            </a>
                        @endauth
                    </div>
                    <div class="ml-3 inline-flex rounded-md shadow">
                        <a href="#" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            En savoir plus
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex justify-center md:justify-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"></path>
                            <path d="M22 20a7 7 0 0 0-14 0"></path>
                            <path d="M11 14h4"></path>
                            <path d="M11 18h6"></path>
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-900 dark:text-white">Lapin Rapide</span>
                    </div>
                    <div class="mt-8 md:mt-0">
                        <p class="text-center text-base text-gray-500 dark:text-gray-400">
                            &copy; {{ date('Y') }} Lapin Rapide. Tous droits réservés.
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>