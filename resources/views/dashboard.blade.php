<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#3B82F6">

    <title>Tableau de Bord - {{ config('app.name', 'EasyColoc') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #3B82F6;
            --primary2: #06B6D4;
            --accent: #10B981;
            --danger: #EF4444;

            --bg1: #0B1220;
            --bg2: #0F172A;
            --panel: rgba(15, 23, 42, .55);
            --border: rgba(148, 163, 184, .15);
            --muted: rgba(148, 163, 184, .75);
            --text: rgba(255, 255, 255, .92);
        }

        html { scroll-behavior: smooth; }
        body {
            font-family: 'Figtree', sans-serif;
            background:
                radial-gradient(900px 500px at 15% 10%, rgba(59,130,246,.18), transparent 60%),
                radial-gradient(900px 500px at 90% 5%, rgba(6,182,212,.14), transparent 60%),
                radial-gradient(900px 500px at 50% 95%, rgba(16,185,129,.10), transparent 60%),
                linear-gradient(135deg, var(--bg1), var(--bg2));
            color: var(--text);
        }

        .panel {
            background: var(--panel);
            border: 1px solid var(--border);
            backdrop-filter: blur(10px);
        }

        .soft-shadow { box-shadow: 0 18px 40px rgba(0,0,0,.35); }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .75rem 1rem;
            border-radius: .75rem;
            color: rgba(255,255,255,.70);
            transition: .2s ease;
        }
        .sidebar-link:hover { background: rgba(255,255,255,.06); color: rgba(255,255,255,.92); }
        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(59,130,246,.95), rgba(6,182,212,.95));
            color: white;
        }

        .chip {
            font-size: 11px;
            padding: .2rem .55rem;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,.06);
            color: rgba(255,255,255,.8);
        }

        .btn-primary {
            background: linear-gradient(90deg, rgba(59,130,246,.95), rgba(6,182,212,.95));
            color: white;
            border-radius: .8rem;
            padding: .55rem 1rem;
            font-weight: 600;
            transition: .2s ease;
        }
        .btn-primary:hover { filter: brightness(1.08); transform: translateY(-1px); }

        .btn-ghost {
            border: 1px solid var(--border);
            background: rgba(255,255,255,.04);
            color: rgba(255,255,255,.85);
            border-radius: .8rem;
            padding: .55rem 1rem;
            font-weight: 600;
            transition: .2s ease;
        }
        .btn-ghost:hover { background: rgba(255,255,255,.07); }

        .btn-danger {
            border: 1px solid rgba(239,68,68,.35);
            background: rgba(239,68,68,.10);
            color: rgba(255,255,255,.90);
            border-radius: .8rem;
            padding: .55rem 1rem;
            font-weight: 600;
            transition: .2s ease;
        }
        .btn-danger:hover { background: rgba(239,68,68,.16); }

        .muted { color: var(--muted); }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            font-size: 11px;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: rgba(148,163,184,.85);
            background: rgba(255,255,255,.03);
            border-bottom: 1px solid var(--border);
            padding: .9rem 1rem;
            text-align: left;
        }
        tbody td {
            border-bottom: 1px solid var(--border);
            padding: .95rem 1rem;
            color: rgba(255,255,255,.85);
        }

        .avatar {
            width: 40px; height: 40px;
            border-radius: 999px;
            background: linear-gradient(135deg, rgba(59,130,246,.9), rgba(6,182,212,.9));
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; color: white;
        }

        .mini-avatar {
            width: 34px; height: 34px;
            border-radius: 999px;
            background: rgba(255,255,255,.08);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            color: rgba(255,255,255,.9);
        }

        .card-title {
            font-weight: 700;
            color: rgba(255,255,255,.92);
        }

        .progress {
            height: 10px;
            border-radius: 999px;
            background: rgba(255,255,255,.08);
            overflow: hidden;
        }
        .progress > div {
            height: 100%;
            background: linear-gradient(90deg, rgba(59,130,246,.95), rgba(6,182,212,.95));
        }
    </style>
</head>

<body class="antialiased">
<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside class="w-64 panel soft-shadow border-r border-slate-700/30 flex flex-col overflow-y-auto">
        <div class="p-6 border-b border-slate-700/30">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-extrabold"
                     style="background: linear-gradient(135deg, rgba(59,130,246,.95), rgba(6,182,212,.95));">
                    E
                </div>
                <span class="text-xl font-extrabold"
                      style="background: linear-gradient(90deg, rgba(96,165,250,1), rgba(34,211,238,1), rgba(52,211,153,1));
                             -webkit-background-clip: text; background-clip:text; color: transparent;">
                    EasyColoc
                </span>
            </a>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="{{ route('dashboard') }}" class="sidebar-link active">
                <span>Dashboard</span>
            </a>

            {{-- ✅ FIX: tu n'as pas colocations.index => on met colocations.my --}}
            <a href="{{ route('colocations.my') }}" class="sidebar-link">
                <span>Ma colocation</span>
            </a>

            @if(auth()->user()->is_admin ?? false)
                <div class="pt-6 border-t border-slate-700/30 mt-6">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Admin</p>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                        <span>Admin Panel</span>
                    </a>
                </div>
            @endif

            <a href="{{ route('profile.edit') }}" class="sidebar-link">
                <span>Profile</span>
            </a>
        </nav>

        <div class="m-4 p-4 panel rounded-2xl">
            <p class="text-xs uppercase tracking-wider muted mb-2">Votre Réputation</p>
            <p class="text-2xl font-extrabold"
               style="background: linear-gradient(90deg, rgba(96,165,250,1), rgba(34,211,238,1));
                      -webkit-background-clip:text; background-clip:text; color: transparent;">
                +{{ auth()->user()->reputation_score ?? 0 }}
            </p>
            <div class="mt-3 progress">
                <div style="width: {{ min((auth()->user()->reputation_score ?? 0) * 2, 100) }}%"></div>
            </div>
        </div>

        <div class="p-4 border-t border-slate-700/30">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full sidebar-link" type="submit" style="color: rgba(248,113,113,.9);">
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        <header class="panel border-b border-slate-700/30 px-8 py-5">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-extrabold"
                        style="background: linear-gradient(90deg, rgba(96,165,250,1), rgba(34,211,238,1), rgba(52,211,153,1));
                               -webkit-background-clip:text; background-clip:text; color: transparent;">
                        TABLEAU DE BORD
                    </h1>
                    <p class="text-sm muted mt-1">Colocation & membres</p>
                </div>

                <div class="flex items-center gap-3">
                    @if(!isset($colocation) || !$colocation)
                        <a href="{{ route('colocations.create') }}" class="btn-primary">
                            + Nouvelle colocation
                        </a>
                    @else
                        <a href="{{ route('depences.index', $colocation) }}" class="btn-primary">
                            Voir dépenses
                        </a>
                    @endif

                    <div class="avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto">
            <div class="p-8 max-w-7xl mx-auto space-y-6">

                @if(session('success'))
                    <div class="panel rounded-2xl px-4 py-3"
                         style="border-color: rgba(16,185,129,.25); background: rgba(16,185,129,.10);">
                        <span style="color: rgba(167,243,208,.95);">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="panel rounded-2xl px-4 py-3"
                         style="border-color: rgba(239,68,68,.25); background: rgba(239,68,68,.10);">
                        <span style="color: rgba(254,202,202,.95);">{{ session('error') }}</span>
                    </div>
                @endif

                @if(!isset($colocation) || !$colocation)
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 panel rounded-2xl p-6 soft-shadow">
                            <h3 class="text-lg font-bold">Aucune colocation active</h3>
                            <p class="muted mt-1">
                                Crée une colocation pour commencer à gérer les dépenses et les membres.
                            </p>
                            <a href="{{ route('colocations.create') }}" class="btn-primary inline-block mt-5">
                                Créer une colocation
                            </a>
                        </div>

                        <div class="panel rounded-2xl p-6 soft-shadow">
                            <div class="flex items-center justify-between">
                                <h3 class="card-title">Membres de la coloc</h3>
                                <span class="chip">VIDE</span>
                            </div>
                            <p class="muted text-sm mt-3">Aucune colocation active.</p>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                        <div class="lg:col-span-2 space-y-6">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="panel rounded-2xl p-6 soft-shadow">
                                    <p class="muted text-sm">Ma colocation</p>
                                    <p class="text-xl font-bold mt-1">{{ $colocation->name }}</p>
                                    <div class="mt-4 text-sm muted space-y-1">
                                        <p>Status : <span class="text-white font-semibold">{{ $colocation->status }}</span></p>
                                        <p>Owner : <span class="text-white font-semibold">{{ $colocation->owner->name }}</span></p>
                                    </div>
                                </div>

                                <div class="panel rounded-2xl p-6 soft-shadow">
                                    <p class="muted text-sm">Mon score réputation</p>
                                    <p class="text-3xl font-extrabold mt-1">{{ auth()->user()->reputation_score ?? 0 }}</p>

                                    <div class="mt-4">
                                        @if(auth()->id() !== $colocation->owner_id)
                                            <form method="POST" action="{{ route('colocations.leave', $colocation) }}"
                                                  onsubmit="return confirm('Voulez-vous quitter cette colocation ?')">
                                                @csrf
                                                <button class="btn-danger w-full" type="submit">
                                                    Quitter la colocation
                                                </button>
                                            </form>
                                        @else
                                            <span class="muted text-sm">Vous êtes owner.</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="panel rounded-2xl soft-shadow overflow-hidden">
                                <div class="px-6 py-4 border-b" style="border-color: var(--border);">
                                    <div class="flex items-center justify-between">
                                        <h3 class="card-title">Dépenses récentes</h3>
                                        <a href="{{ route('depences.index', $colocation) }}" class="text-sm" style="color: rgba(34,211,238,.95);">
                                            Voir tout →
                                        </a>
                                    </div>
                                </div>

                                <div class="table-wrap">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Titre / Catégorie</th>
                                                <th>Payeur</th>
                                                <th>Montant</th>
                                                <th>Coloc</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="muted" style="padding: 1.3rem;">
                                                    Aucune dépense récente.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="panel rounded-2xl soft-shadow overflow-hidden">
                                <div class="px-6 py-4 border-b" style="border-color: var(--border);">
                                    <div class="flex items-center justify-between">
                                        <h3 class="card-title">Membres</h3>
                                        <span class="muted text-sm">{{ $colocation->members->count() }} membre(s)</span>
                                    </div>
                                </div>

                                <div class="p-6">
                                    @if($colocation->members->isEmpty())
                                        <p class="muted">Aucun membre pour le moment.</p>
                                    @else
                                        <div class="space-y-3">
                                            @foreach($colocation->members as $member)
                                                <div class="panel rounded-2xl p-4"
                                                     style="background: rgba(255,255,255,.03);">
                                                    <div class="flex items-center justify-between gap-4">
                                                        <div class="flex items-center gap-3">
                                                            <div class="avatar" style="width: 42px; height: 42px;">
                                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                                            </div>
                                                            <div>
                                                                <div class="font-semibold">
                                                                    {{ $member->name }}
                                                                    <span class="muted text-sm">({{ $member->email }})</span>
                                                                </div>
                                                                <div class="muted text-sm">
                                                                    Rôle :
                                                                    @if($member->id === $colocation->owner_id)
                                                                        <span style="color: rgba(34,211,238,.95); font-weight: 700;">Owner</span>
                                                                    @else
                                                                        <span style="color: rgba(96,165,250,.95); font-weight: 700;">Member</span>
                                                                    @endif
                                                                    — Réputation :
                                                                    <span class="text-white font-semibold">{{ $member->reputation_score ?? 0 }}</span>
                                                                    — <span class="chip">{{ $member->pivot->status }}</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if(auth()->id() === $colocation->owner_id && $member->id !== $colocation->owner_id)
                                                            <form method="POST"
                                                                  action="{{ route('colocations.members.remove', [$colocation, $member]) }}"
                                                                  onsubmit="return confirm('Retirer ce membre ?')">
                                                                @csrf
                                                                <button class="btn-danger" type="submit">Retirer</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="space-y-6">
                            <div class="panel rounded-2xl p-6 soft-shadow">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="card-title">Membres de la coloc</h3>
                                    <span class="chip">ACTIFS</span>
                                </div>

                                <div class="space-y-3">
                                    @foreach($colocation->members as $m)
                                        <div class="flex items-center justify-between rounded-2xl p-3"
                                             style="background: rgba(255,255,255,.04); border: 1px solid var(--border);">
                                            <div class="flex items-center gap-3">
                                                <div class="mini-avatar">
                                                    {{ strtoupper(substr($m->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold">{{ $m->name }}</div>
                                                    <div class="text-xs muted">
                                                        @if($m->id === $colocation->owner_id) OWNER @else MEMBER @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-sm font-semibold" style="color: rgba(34,211,238,.95);">
                                                {{ $m->reputation_score ?? 0 }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button class="btn-ghost w-full mt-4" type="button">
                                    + Inviter un membre
                                </button>
                            </div>
                        </div>

                    </div>
                @endif

            </div>
        </main>
    </div>
</div>
</body>
</html>