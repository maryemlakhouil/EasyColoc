<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#3B82F6">

    <title>{{ config('app.name', 'EasyColoc') }} - Créer une colocation</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #3B82F6;
            --primary-light: #60A5FA;
            --secondary: #06B6D4;
            --accent: #10B981;
            --background: #0F172A;
            --card-bg: #1E293B;
            --text-primary: #F1F5F9;
            --text-secondary: #CBD5E1;
            --border: #334155;
            --shadow: rgba(96, 165, 250, 0.1);
        }

        body {
            @apply bg-gradient-to-br from-slate-900 to-slate-800;
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>
<body class="font-sans text-white antialiased">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-slate-900 border-r border-slate-700 flex flex-col fixed h-screen">
            <!-- Logo -->
            <div class="p-6 border-b border-slate-700">
                <a href="/" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 via-cyan-500 to-green-500 flex items-center justify-center text-white font-bold text-lg">
                        E
                    </div>
                    <span class="text-lg font-bold bg-gradient-to-r from-blue-400 via-cyan-400 to-green-400 bg-clip-text text-transparent">
                        EasyColoc
                    </span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-slate-800 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 9l-3-3m0 0l-3 3m3-3v5" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('colocations.my') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-400 bg-slate-800 transition-colors font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                    </svg>
                    <span>Colocations</span>
                </a>

                <div class="border-t border-slate-700 my-4"></div>

                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-slate-800 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Profil</span>
                </a>
            </nav>

            <!-- Reputation Section -->
            <div class="px-4 py-6 border-t border-slate-700 space-y-3">
                <div class="text-xs font-semibold text-gray-400 uppercase">Votre Réputation</div>
                <div class="bg-gradient-to-br from-blue-900/30 to-cyan-900/30 rounded-lg p-4 border border-blue-800/30">
                    <div class="text-2xl font-bold text-white">+0 points</div>
                    <div class="w-full bg-slate-700 rounded-full h-2 mt-3">
                        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Logout -->
            <div class="px-4 py-4 border-t border-slate-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 text-red-400 hover:text-red-300 transition-colors px-4 py-3 rounded-lg hover:bg-red-900/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64 flex flex-col">
            <!-- Header -->
            <header class="bg-slate-800/50 border-b border-slate-700 px-8 py-4 backdrop-blur sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-400 via-cyan-400 to-green-400 bg-clip-text text-transparent">
                            Créer une colocation
                        </h2>
                        <p class="text-sm text-gray-400 mt-1">Donne un nom à ta colocation pour commencer</p>
                    </div>
                    <a href="{{ route('colocations.my') }}" class="px-4 py-2 rounded-lg border border-slate-600 hover:bg-slate-700 text-sm text-gray-300 transition-colors">
                        ← Retour
                    </a>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
                <div class="p-8 max-w-6xl mx-auto">
                    <!-- Messages -->
                    @if(session('error'))
                        <div class="mb-6 bg-red-900/20 text-red-300 border border-red-800/50 p-4 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 bg-red-900/20 text-red-300 border border-red-800/50 p-4 rounded-lg">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Form Card -->
                        <div class="lg:col-span-2 bg-slate-800 rounded-2xl border border-slate-700 shadow-xl overflow-hidden">
                            <div class="p-6 border-b border-slate-700">
                                <h3 class="text-xl font-bold text-white">Nouvelle colocation</h3>
                                <p class="text-sm text-gray-400 mt-2">Ex: "COLOC 1", "Appart centre", "Maison étudiants"</p>
                            </div>

                            <form method="POST" action="{{ route('colocations.store') }}" class="p-6 space-y-6">
                                @csrf

                                <!-- Name Input -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Nom de la colocation</label>
                                    <input
                                        name="name"
                                        value="{{ old('name') }}"
                                        required
                                        placeholder="COLOC 1"
                                        class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    >
                                    @error('name')
                                        <div class="text-red-400 text-sm mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-700">
                                    <a href="{{ route('colocations.my') }}"
                                       class="px-5 py-2.5 rounded-lg border border-slate-600 hover:bg-slate-700 text-gray-300 font-medium transition-colors">
                                        Annuler
                                    </a>

                                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-medium transition-all shadow-lg hover:shadow-blue-500/50">
                                        Créer la colocation
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Info Card -->
                        <div class="bg-slate-800 rounded-2xl border border-slate-700 shadow-xl p-6">
                            <h3 class="text-lg font-bold text-white mb-4">Infos</h3>

                            <div class="space-y-3">
                                <div class="rounded-lg border border-slate-700 bg-slate-700/30 p-4">
                                    <div class="font-semibold text-blue-400">Owner</div>
                                    <div class="text-sm text-gray-400 mt-1">Le créateur devient owner automatiquement.</div>
                                </div>

                                <div class="rounded-lg border border-slate-700 bg-slate-700/30 p-4">
                                    <div class="font-semibold text-cyan-400">Invitation</div>
                                    <div class="text-sm text-gray-400 mt-1">Tu pourras inviter des membres via email/token.</div>
                                </div>

                                <div class="rounded-lg border border-slate-700 bg-slate-700/30 p-4">
                                    <div class="font-semibold text-green-400">Règle</div>
                                    <div class="text-sm text-gray-400 mt-1">Un utilisateur ne peut avoir qu'une colocation active.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
