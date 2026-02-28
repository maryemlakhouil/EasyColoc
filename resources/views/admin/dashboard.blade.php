<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#3B82F6">

        <title>Admin Dashboard - {{ config('app.name', 'EasyColoc') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --primary: #3B82F6;
                --primary-light: #60A5FA;
                --secondary: #06B6D4;
                --accent: #10B981;
            }

            @media (prefers-color-scheme: dark) {
                :root {
                    --primary: #60A5FA;
                    --secondary: #22D3EE;
                    --accent: #34D399;
                }
            }

            html {
                scroll-behavior: smooth;
            }

            body {
                @apply bg-gradient-to-br from-slate-900 to-slate-800;
                font-family: 'Figtree', sans-serif;
                transition: background-color 0.3s ease;
            }

            .sidebar-link.active {
                @apply bg-gradient-to-r from-blue-600 to-cyan-600 text-white;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 dark:text-white antialiased">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <aside class="w-64 bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700 overflow-y-auto">
                <!-- Logo -->
                <div class="p-6 border-b border-gray-200 dark:border-slate-700">
                    <a href="/" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 via-cyan-500 to-green-500 flex items-center justify-center text-white font-bold text-lg">
                            E
                        </div>
                        <span class="font-bold bg-gradient-to-r from-blue-600 via-cyan-500 to-green-500 bg-clip-text text-transparent">
                            EasyColoc
                        </span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="p-4 space-y-2">
                    
                    <a href="{{ route('colocations.create') }}"
                       class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all
                       {{ request()->routeIs('colocations.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Colocations
                    </a>

                    <div class="pt-4 mt-4 border-t border-gray-200 dark:border-slate-700">
                        <a href="{{ route('profile.edit') }}"
                           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all
                           {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profil
                        </a>
                    </div>
                </nav>

                <!-- User Section -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 w-64">
                    <!-- Reputation -->
                    <div class="mb-4 p-4 rounded-xl bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 border border-blue-200 dark:border-blue-800">
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">
                            Votre réputation
                        </p>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">+0 points</div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-3">
                            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Header -->
                <header class="bg-slate-800/50 border-b border-slate-700 px-8 py-4 backdrop-blur">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                Gestion Administrateur
                            </h1>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <span class="inline-block px-2 py-1 rounded bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-semibold">
                                        ADMIN
                                    </span>
                                </p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Scrollable Content -->
                <div class="flex-1 overflow-y-auto bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
                    <div class="p-8">

                        {{-- Flash messages --}}
                        @if(session('success'))
                            <div class="bg-green-50 dark:bg-green-900/20 text-green-800 dark:text-green-200 border border-green-200 dark:border-green-800 p-4 rounded-xl flex items-start gap-3 mb-6">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-200 border border-red-200 dark:border-red-800 p-4 rounded-xl flex items-start gap-3 mb-6">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Stats --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            {{-- Total Users --}}
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-shadow">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Utilisateurs</div>
                                    <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0H9m6 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-4xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Comptes actifs</p>
                            </div>

                            {{-- Total Colocations --}}
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-shadow">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Colocations</div>
                                    <div class="w-12 h-12 rounded-lg bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-4xl font-bold text-gray-900 dark:text-white">{{ $totalColocations }}</div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Groupes actifs</p>
                            </div>

                            {{-- Total Expenses --}}
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-shadow">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Dépenses totales</div>
                                    <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-4xl font-bold text-gray-900 dark:text-white">{{ number_format($totalDepences, 2) }}€</div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Volume total</p>
                            </div>

                            {{-- Banned Users --}}
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-shadow">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Utilisateurs bannis</div>
                                    <div class="w-12 h-12 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 5v-1m7-4a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-4xl font-bold text-gray-900 dark:text-white">{{ $bannedUsers->count() }}</div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Comptes suspendus</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                            {{-- Users Table --}}
                            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Tous les utilisateurs</h3>
                                    <span class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">{{ $users->total() }} total</span>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead class="text-xs font-semibold text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                                            <tr>
                                                <th class="py-4 px-6 text-left">ID</th>
                                                <th class="py-4 px-6 text-left">Nom</th>
                                                <th class="py-4 px-6 text-left">Email</th>
                                                <th class="py-4 px-6 text-left">Rôle</th>
                                                <th class="py-4 px-6 text-left">Statut</th>
                                                <th class="py-4 px-6 text-right">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($users as $u)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                    <td class="py-4 px-6 text-gray-900 dark:text-gray-100">#{{ $u->id }}</td>
                                                    <td class="py-4 px-6 text-gray-900 dark:text-gray-100 font-medium">{{ $u->name }}</td>
                                                    <td class="py-4 px-6 text-gray-600 dark:text-gray-400 text-xs">{{ $u->email }}</td>
                                                    <td class="py-4 px-6">
                                                        <span class="inline-block px-3 py-1 rounded-lg text-xs font-medium 
                                                            @if($u->role === 'admin')
                                                                bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300
                                                            @else
                                                                bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300
                                                            @endif">
                                                            {{ ucfirst($u->role) }}
                                                        </span>
                                                    </td>
                                                    <td class="py-4 px-6">
                                                        @if($u->is_banned)
                                                            <span class="inline-block px-3 py-1 rounded-lg text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">
                                                                Banni
                                                            </span>
                                                        @else
                                                            <span class="inline-block px-3 py-1 rounded-lg text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">
                                                                Actif
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td class="py-4 px-6 text-right">
                                                        @if(auth()->id() === $u->id)
                                                            <span class="text-xs text-gray-400">—</span>
                                                        @else
                                                            @if($u->is_banned)
                                                                <form method="POST" action="{{ route('admin.users.unban', $u) }}" class="inline">
                                                                    @csrf
                                                                    <button type="submit" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                                        Débannir
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <form method="POST" action="{{ route('admin.users.ban', $u) }}" class="inline">
                                                                    @csrf
                                                                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-colors">
                                                                        Bannir
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                                    {{ $users->links() }}
                                </div>
                            </div>

                            {{-- Banned Users Sidebar --}}
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col">
                                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Utilisateurs bannis</h3>
                                    <span class="text-sm text-white bg-red-600 px-2.5 py-1 rounded-full">{{ $bannedUsers->count() }}</span>
                                </div>

                                <div class="p-6 space-y-3 flex-1 overflow-y-auto max-h-96">
                                    @forelse($bannedUsers as $bu)
                                        <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex-1">
                                                    <div class="font-medium text-gray-900 dark:text-white text-sm">{{ $bu->name }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate">{{ $bu->email }}</div>
                                                </div>
                                            </div>

                                            <form method="POST" action="{{ route('admin.users.unban', $bu) }}">
                                                @csrf
                                                <button type="submit" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-xs font-medium hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    Débannir
                                                </button>
                                            </form>
                                        </div>
                                    @empty
                                        <div class="flex flex-col items-center justify-center py-8 text-center">
                                            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Aucun utilisateur banni.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
