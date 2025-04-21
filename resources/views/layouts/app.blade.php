<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="vapid-public-key" content="{{ config('webpush.vapid.public_key') }}">
    <title>{{ config('app.name', 'Gestion Élevage') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['"Be Vietnam Pro"', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
    *{
        font-family: 'Be Vietnam Pro', sans-serif;
    }
    </style>
    <style>
    @media (max-width: 640px) {
        /* Réduction de la taille des boutons sur mobile */
        .inline-flex, 
        button[type="submit"],
        button[type="button"],
        .btn,
        a.inline-flex {
            font-size: 0.5rem !important;
            padding: 0.20rem 0.55rem !important;
        }
        
        /* Réduction de la taille des titres d'en-tête */
        h2.font-semibold.text-xl {
            font-size: 1rem !important;
            line-height: 1 !important;
        }
        
        /* Ajustement des marges et paddings pour les conteneurs */
        .py-12 {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }
        
        .p-6 {
            padding: 1rem !important;
        }
        
        /* Amélioration de l'affichage des formulaires */
        .space-y-6 > * {
            margin-top: 0.75rem !important;
            margin-bottom: 0.75rem !important;
        }
        
        /* Ajustement des grilles */
        .grid {
            gap: 0.75rem !important;
        }
    }
    
    /* Correction pour le menu mobile */
    @media (max-width: 768px) {
        /* S'assurer que tous les éléments du menu sont visibles */
        #navigation-menu {
            display: flex !important;
            flex-direction: column !important;
        }
        
        /* Cacher le menu par défaut et l'afficher uniquement quand le bouton hamburger est cliqué */
        .hidden\:sm {
            display: none !important;
        }
        
        /* Quand le menu est ouvert */
        .sm\:block {
            display: block !important;
        }
        
        /* Style pour le menu déroulant sur mobile */
        .sm\:hidden.sm\:block {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            z-index: 50;
        }
    }
    
    /* Styles pour les sous-menus */
    .submenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-in-out;
    }
    
    .submenu.open {
        max-height: 500px; /* Hauteur maximale pour l'animation */
    }
    
    .menu-arrow {
        transition: transform 0.3s ease;
    }
    
    .menu-arrow.rotate {
        transform: rotate(90deg);
    }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar for desktop -->
        <div class="hidden md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col">
            <div class="flex min-h-0 flex-1 flex-col bg-gray-800">
                <div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
                    <div class="flex flex-shrink-0 items-center px-4">
                        <span class="text-xl font-bold text-white">{{ config('app.name', 'Gestion Élevage') }}</span>
                    </div>
                    <div class="mt-5 flex flex-1 flex-col">
                        <nav class="flex-1 space-y-1 px-2">
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Tableau de bord
                            </a>

                            <!-- Menu Lapins avec sous-menu -->
                            <div x-data="{ open: {{ request()->routeIs('rabbits.*') || request()->routeIs('cages.*') ? 'true' : 'false' }} }">
                                <button @click="open = !open" class="w-full text-left text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span>{{ session('animal_type', 'Lapins') }}</span>
                                    </div>
                                    <svg class="menu-arrow h-5 w-5 text-gray-400" :class="{'rotate': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div class="submenu pl-4" :class="{'open': open}">
                                    <a href="{{ route('rabbits.index') }}" class="{{ request()->routeIs('rabbits.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('rabbits.index') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        Tous les {{ session('animal_type', 'Lapins') }}
                                    </a>
                                    <a href="{{ route('rabbits.create') }}" class="{{ request()->routeIs('rabbits.create') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('rabbits.create') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Ajouter un {{ session('animal_type_singular', 'Lapin') }}
                                    </a>
                                    <a href="{{ route('cages.index') }}" class="{{ request()->routeIs('cages.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('cages.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                        </svg>
                                        Cages
                                    </a>
                                </div>
                            </div>

                            <!-- Menu Reproduction avec sous-menu -->
                            <div x-data="{ open: {{ request()->routeIs('breedings.*') ? 'true' : 'false' }} }">
                                <button @click="open = !open" class="w-full text-left text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Reproduction</span>
                                    </div>
                                    <svg class="menu-arrow h-5 w-5 text-gray-400" :class="{'rotate': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div class="submenu pl-4" :class="{'open': open}">
                                    <a href="{{ route('breedings.index') }}" class="{{ request()->routeIs('breedings.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('breedings.index') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Toutes les portées
                                    </a>
                                    <a href="{{ route('breedings.create') }}" class="{{ request()->routeIs('breedings.create') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('breedings.create') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Nouvelle portée
                                    </a>
                                    <a href="{{ route('breedings.calendar') }}" class="{{ request()->routeIs('breedings.calendar') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('breedings.calendar') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Calendrier
                                    </a>
                                </div>
                            </div>

                            <!-- Menu Santé avec sous-menu -->
                            <div x-data="{ open: {{ request()->routeIs('medications.*') || request()->routeIs('treatments.*') || request()->routeIs('protocols.*') ? 'true' : 'false' }} }">
                                <button @click="open = !open" class="w-full text-left text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                        <span>Santé</span>
                                    </div>
                                    <svg class="menu-arrow h-5 w-5 text-gray-400" :class="{'rotate': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div class="submenu pl-4" :class="{'open': open}">
                                    <a href="{{ route('medications.index') }}" class="{{ request()->routeIs('medications.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('medications.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                        Médicaments
                                    </a>
                                    <a href="{{ route('treatments.index') }}" class="{{ request()->routeIs('treatments.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('treatments.index') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Traitements
                                    </a>
                                    <a href="{{ route('treatments.calendar') }}" class="{{ request()->routeIs('treatments.calendar') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('treatments.calendar') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Calendrier de santé
                                    </a>
                                    <a href="{{ route('diagnostics.index') }}" class="{{ request()->routeIs('diagnostics.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('diagnostics.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Diagnostics IA
                                    </a>
                                    <a href="{{ route('protocols.index') }}" class="{{ request()->routeIs('protocols.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('protocols.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Protocoles
                                    </a>
                                </div>
                            </div>

                            <!-- Menu Alimentation avec sous-menu -->
                            <div x-data="{ open: {{ request()->routeIs('foods.*') || request()->routeIs('food-schedules.*') ? 'true' : 'false' }} }">
                                <button @click="open = !open" class="w-full text-left text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span>Alimentation</span>
                                    </div>
                                    <svg class="menu-arrow h-5 w-5 text-gray-400" :class="{'rotate': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div class="submenu pl-4" :class="{'open': open}">
                                    <a href="{{ route('foods.index') }}" class="{{ request()->routeIs('foods.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('foods.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Nourritures
                                    </a>
                                    <a href="{{ route('food-schedules.index') }}" class="{{ request()->routeIs('food-schedules.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('food-schedules.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Emploi du temps
                                    </a>
                                </div>
                            </div>

                            <!-- Menu Gestion avec sous-menu -->
                            <div x-data="{ open: {{ request()->routeIs('reminders.*') ? 'true' : 'false' }} }">
                                <button @click="open = !open" class="w-full text-left text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Gestion</span>
                                    </div>
                                    <svg class="menu-arrow h-5 w-5 text-gray-400" :class="{'rotate': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div class="submenu pl-4" :class="{'open': open}">
                                    <a href="{{ route('reminders.index') }}" class="{{ request()->routeIs('reminders.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('reminders.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Rappels
                                    </a>
                                    <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('reports.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Rapports
                                    </a>
                                    
                                    <button class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Paramètres
                                    </button>


                                </div>
                            </div>

                            <div x-data="{ open: {{ request()->routeIs('expenses.*') ? 'true' : 'false' }} }">
                                <button @click="open = !open" class="w-full text-left text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Dépenses</span>
                                    </div>
                                    <svg class="menu-arrow h-5 w-5 text-gray-400" :class="{'rotate': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div class="submenu pl-4" :class="{'open': open}">
                                    <a href="{{ route('expenses.index') }}" class="{{ request()->routeIs('expenses.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('expenses.index') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Toutes les dépenses
                                    </a>
                                    <a href="{{ route('expenses.create') }}" class="{{ request()->routeIs('expenses.create') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('expenses.create') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Ajouter une dépense
                                    </a>
                                </div>
                            </div>


                            <div x-data="{ open: {{ request()->routeIs('health.*') ? 'true' : 'false' }} }">
                                <button @click="open = !open" class="w-full text-left text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        <span>État de santé</span>
                                    </div>
                                    <svg class="menu-arrow h-5 w-5 text-gray-400" :class="{'rotate': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div class="submenu pl-4" :class="{'open': open}">
                                    <a href="{{ route('health.dashboard') }}" class="{{ request()->routeIs('health.dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('health.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        Tableau de bord
                                    </a>
                                    <a href="{{ route('health.illness.index') }}" class="{{ request()->routeIs('health.illness.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('health.illness.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Maladies
                                    </a>
                                    <a href="{{ route('health.mortality.index') }}" class="{{ request()->routeIs('health.mortality.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('health.mortality.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Mortalité
                                    </a>
                                </div>
                            </div>

                            <div x-data="{ open: {{ request()->routeIs('sales.*') ? 'true' : 'false' }} }">
                                <button @click="open = !open" class="w-full text-left text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span>Ventes</span>
                                    </div>
                                    <svg class="menu-arrow h-5 w-5 text-gray-400" :class="{'rotate': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div class="submenu pl-4" :class="{'open': open}">
                                    <a href="{{ route('sales.index') }}" class="{{ request()->routeIs('sales.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('sales.index') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Toutes les ventes
                                    </a>
                                    <a href="{{ route('sales.create') }}" class="{{ request()->routeIs('sales.create') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('sales.create') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Nouvelle vente
                                    </a>
                                    <a href="{{ route('sales.report') }}" class="{{ request()->routeIs('sales.report') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('sales.report') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Rapport des ventes
                                    </a>

                                    <a href="{{ route('optimization.index') }}" class="{{ request()->routeIs('optimization.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('optimization.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        Optimisation
                                    </a>
                                </div>
                            </div>
                            
                            <a href="{{ route('offline.sync') }}" class="{{ request()->routeIs('offline.sync') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->routeIs('offline.sync') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        {{ __('Synchronisation hors ligne') }}
                            </a>

                        </nav>
                    </div>
                </div>
                
                <div class="flex flex-shrink-0 bg-gray-700 p-4">
                    <div class="group block w-full flex-shrink-0">
                        <div class="flex items-center">
                            <div>
                                <div class="h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center">
                                    <span class="text-white font-bold">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-white">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
                                <div class="flex space-x-2 text-xs text-gray-300">
                                    <a href="{{ route('profile.edit') }}" class="hover:text-white">Profil</a>
                                    <span>|</span>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="hover:text-white">
                                            Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden" x-data="{ open: false }">
            <div class="fixed inset-0 flex z-40" x-show="open" x-cloak>
                <div class="fixed inset-0" @click="open = false">
                    <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
                </div>
                <div class="relative flex-1 flex flex-col max-w-xs w-full bg-gray-800">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button @click="open = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <span class="sr-only">Fermer le menu</span>
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <div class="flex-shrink-0 flex items-center px-4">
                            <span class="text-xl font-bold text-white">{{ config('app.name', 'Gestion Élevage') }}</span>
                        </div>
                        <nav class="mt-5 px-2 space-y-1">
                            <!-- Répéter les mêmes menus que pour desktop mais avec x-data pour mobile -->
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-base font-medium rounded-md">
                                <svg class="mr-4 h-6 w-6 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Tableau de bord
                            </a>

                            <!-- Menus mobiles avec sous-menus -->
                            <!-- Répéter les mêmes structures que pour desktop -->
                        </nav>
                    </div>
                    <div class="flex-shrink-0 flex bg-gray-700 p-4">
                        <div class="flex items-center">
                            <div>
                                <div class="h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center">
                                    <span class="text-white font-bold">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-white">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
                                <div class="flex space-x-2 text-xs text-gray-300">
                                    <a href="{{ route('profile.edit') }}" class="hover:text-white">Profil</a>
                                    <span>|</span>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="hover:text-white">
                                            Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 w-14">
                    <!-- Force sidebar to shrink to fit close icon -->
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center justify-between px-4 py-2 bg-gray-800 sm:px-6 md:hidden">
                <div>
                    <span class="text-xl font-bold text-white">{{ config('app.name', 'Gestion Élevage') }}</span>
                </div>
                <button @click="open = true" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">Ouvrir le menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Page Content -->
        <div class="md:pl-64 flex flex-col flex-1">
            <main class="flex-1">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        @if (isset($header))
                            <header class="bg-white shadow-sm rounded-lg mb-6">
                                <div class="py-4 px-4 sm:px-6 lg:px-8">
                                    {{ $header }}
                                </div>
                            </header>
                        @endif

                        <!-- Flash Messages -->
                        @if (session('success'))
                            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md" role="alert">
                                <p class="font-bold">Succès</p>
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md" role="alert">
                                <p class="font-bold">Erreur</p>
                                <p>{{ session('error') }}</p>
                            </div>
                        @endif

                        @if (session('warning'))
                            <div class="mb-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-md" role="alert">
                                <p class="font-bold">Attention</p>
                                <p>{{ session('warning') }}</p>
                            </div>
                        @endif

                        @if (session('info'))
                            <div class="mb-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded shadow-md" role="alert">
                                <p class="font-bold">Information</p>
                                <p>{{ session('info') }}</p>
                            </div>
                        @endif

                        <!-- Page Content -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
	
	<!-- Modal pour choisir le type d'animal -->
    @if(!session('animal_type'))
    <div class="fixed z-50 inset-0 overflow-y-auto" id="animal-type-modal">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Bienvenue dans votre application de gestion d'élevage
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Pour personnaliser votre expérience, veuillez sélectionner le type d'animal que vous élevez.
                            </p>
                        </div>
                    </div>
                </div>
                <form action="{{ route('set.animal.type') }}" method="POST" class="mt-5 sm:mt-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <button type="submit" name="animal_type" value="rabbit" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-4 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm border-gray-300">
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="mt-2 block">Lapins</span>
                            </div>
                        </button>
                        <button type="submit" name="animal_type" value="chicken" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-4 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm border-gray-300">
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="mt-2 block">Volailles</span>
                            </div>
                        </button>
                        <button type="submit" name="animal_type" value="goat" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-4 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm border-gray-300">
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="mt-2 block">Chèvres</span>
                            </div>
                        </button>
                        <button type="submit" name="animal_type" value="other" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-4 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm border-gray-300">
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span class="mt-2 block">Autre</span>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Correction pour le menu mobile
        const hamburgerButton = document.querySelector('[aria-controls="navigation-menu"]');
        const navigationMenu = document.getElementById('navigation-menu');
        
        if (hamburgerButton && navigationMenu) {
            hamburgerButton.addEventListener('click', function() {
                // Toggle la classe hidden sur le menu
                if (navigationMenu.classList.contains('hidden')) {
                    navigationMenu.classList.remove('hidden');
                } else {
                    navigationMenu.classList.add('hidden');
                }
            });
        }
        
        // Ajuster dynamiquement la taille des titres longs sur mobile
        const adjustHeaderTitles = function() {
            const headerTitles = document.querySelectorAll('h2.font-semibold.text-xl');
            if (window.innerWidth <= 640) {
                headerTitles.forEach(title => {
                    if (title.textContent.length > 30) {
                        title.style.fontSize = '0.875rem';
                    }
                });
            } else {
                headerTitles.forEach(title => {
                    title.style.fontSize = '';
                });
            }
        };
        
        // Exécuter au chargement et lors du redimensionnement
        adjustHeaderTitles();
        window.addEventListener('resize', adjustHeaderTitles);
    });
</script>
    <!-- Alpine.js -->
    <script src="{{ asset('js/push-notifications.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>
<script>
  // Configuration Firebase
  const firebaseConfig = {
    apiKey: "AIzaSyDe3mBx2I983oIaTjSV83moKLrqYR-zMuA",
    authDomain: "elevage-projet.firebaseapp.com",
    projectId: "elevage-projet",
    storageBucket: "elevage-projet.firebasestorage.app",
    messagingSenderId: "1061048497751",
    appId: "1:1061048497751:web:5d7118fd7d9c834aa8d512"
  };
  
  // Initialiser Firebase
  firebase.initializeApp(firebaseConfig);
  
  // Initialiser Firebase Cloud Messaging
  const messaging = firebase.messaging();
</script>
</body>
</html>
