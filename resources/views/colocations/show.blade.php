<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#3B82F6">

    <title>{{ $colocation->name }} - {{ config('app.name', 'EasyColoc') }}</title>

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
            border: none;
            cursor: pointer;
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
            cursor: pointer;
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
            cursor: pointer;
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
            <a href="{{ route('dashboard') }}" class="sidebar-link">
                <span>Dashboard</span>
            </a>

            <a href="{{ route('colocations.my') }}" class="sidebar-link active">
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
                        {{ $colocation->name }}
                    </h1>
                    <p class="text-sm muted mt-1">Gérez vos dépenses & membres</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('depences.index', $colocation) }}" class="btn-primary">
                        + Nouvelle dépense
                    </a>
                    <button onclick="history.back()" class="btn-ghost">
                        ← Retour
                    </button>
                    <div class="avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto">
            <div class="p-8 max-w-7xl mx-auto space-y-6">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <div class="lg:col-span-2 space-y-6">

                        {{-- Dépenses récentes --}}
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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="4" class="muted" style="padding: 1.3rem; text-align: center;">
                                                Aucune dépense pour le moment.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Membres --}}
                        <div class="panel rounded-2xl soft-shadow overflow-hidden">
                            <div class="px-6 py-4 border-b" style="border-color: var(--border);">
                                <div class="flex items-center justify-between">
                                    <h3 class="card-title">Membres</h3>
                                    <span class="chip">{{ $colocation->members->count() }} membres</span>
                                </div>
                            </div>

                            <div class="p-6 space-y-3">
                                @foreach($colocation->members as $member)
                                    <div class="panel rounded-2xl p-4">
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-3">
                                                <div class="avatar" style="width: 42px; height: 42px;">
                                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-semibold">
                                                        {{ $member->name }}
                                                        @if($member->id === $colocation->owner_id)
                                                            <span class="chip">OWNER</span>
                                                        @endif
                                                    </div>
                                                    <div class="muted text-sm">{{ $member->email }}</div>
                                                </div>
                                            </div>

                                            @if(auth()->id() === $colocation->owner_id && $member->id !== $colocation->owner_id)
                                                <form method="POST" action="{{ route('colocations.members.remove', [$colocation, $member]) }}">
                                                    @csrf
                                                    <button class="btn-danger" type="submit">Retirer</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">

                        {{-- Qui doit à qui --}}
                       {{-- Qui doit à qui (dynamique) --}}
<div class="panel rounded-2xl p-6 soft-shadow">
    <div class="flex items-center justify-between mb-4">
        <h3 class="card-title">Qui doit à qui ?</h3>
        <span class="chip">{{ $openSettlements->count() }} en attente</span>
    </div>

    @if($openSettlements->isEmpty())
        <p class="muted text-sm">Aucun remboursement en attente.</p>
    @else
        <div class="space-y-3">
            @foreach($openSettlements as $s)
                @php
                    $isMine = auth()->id() === $s->from_user_id; // celui qui doit
                @endphp

                <div class="panel rounded-xl p-3 flex items-center justify-between gap-3">
                    <div>
                        <div class="text-sm muted mb-1">
                            <strong class="text-white">{{ $s->fromUser->name }}</strong>
                            →
                            <strong class="text-white">{{ $s->toUser->name }}</strong>
                        </div>

                        <div class="text-xl font-bold"
                             style="color: {{ $isMine ? 'rgba(239,68,68,.95)' : 'rgba(16,185,129,.95)' }};">
                            {{ number_format($s->montant, 2) }} €
                        </div>

                        <div class="text-xs muted mt-1">
                            @if($isMine)
                                Vous devez payer
                            @else
                                Vous devez recevoir
                            @endif
                        </div>
                    </div>

                    {{-- Bouton "Marquer payé" seulement si c'est moi qui dois payer --}}
                    @if($isMine)
                        <form method="POST" action="{{ route('settlements.pay', $s) }}">
                            @csrf
                            <button class="btn-ghost" type="submit">
                                Marquer payé
                            </button>
                        </form>
                    @else
                        <span class="chip">En attente</span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

                        {{-- Actions --}}
                        <div class="panel rounded-2xl p-6 soft-shadow space-y-3">
                            @if(auth()->id() === $colocation->owner_id)
                                <button id="openInviteModal" class="w-full btn-primary">
                                    + Inviter un membre
                                </button>
                            @endif

                            @if(auth()->id() === $colocation->owner_id)
                                <form method="POST" action="{{ route('colocations.cancel', $colocation) }}" onsubmit="return confirm('Annuler la colocation ? Les membres vont quitter automatiquement.')">
                                    @csrf
                                    <button class="w-full btn-warning" type="submit" style="background-color: #ea8c55; color: white;">
                                        Annuler la colocation
                                    </button>
                                </form>
                            @endif

                            @if(auth()->id() === $colocation->owner_id)
                                <form method="POST" action="{{ route('colocations.destroy', $colocation) }}" onsubmit="return confirm('Supprimer définitivement ? Toutes les données seront supprimées.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-full btn-danger" type="submit">
                                        Supprimer définitivement
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('colocations.leave', $colocation) }}" onsubmit="return confirm('Voulez-vous quitter cette colocation ?')">
                                    @csrf
                                    <button class="w-full btn-danger" type="submit">
                                        Quitter la colocation
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

</div>
    {{-- MODAL Invitation (owner seulement) --}}
    @if(auth()->id() === $colocation->owner_id)
        <div id="inviteModal" class="fixed inset-0 z-50 hidden">
            <div id="inviteModalOverlay" class="absolute inset-0 bg-black/50"></div>

            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="w-full max-w-lg rounded-2xl shadow-2xl soft-shadow panel">
                    <div class="flex items-center justify-between px-6 py-5 border-b" style="border-color: var(--border);">
                        <h3 class="text-xl font-semibold card-title">Inviter un membre</h3>
                        <button type="button" id="closeInviteModal" class="p-2 rounded-lg hover:bg-white/10 text-white/80 hover:text-white">
                            ✕
                        </button>
                    </div>

                    <form method="POST" action="{{ route('invitations.store', $colocation) }}" class="p-6 space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-white/85 mb-2">Email</label>
                            <select name="email" class="w-full rounded-lg p-3 bg-white/8 border border-white/15 text-white placeholder:text-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500/50" required>
                                <option value="" class="bg-slate-800">Choisir un email...</option>
                                @foreach($usersEmails as $u)
                                    <option value="{{ $u->email }}" class="bg-slate-800">{{ $u->email }}</option>
                                @endforeach
                            </select>
                            @error('email')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <button type="button" id="cancelInviteModal" class="btn-ghost">
                                Annuler
                            </button>
                            <button type="submit" class="btn-primary">
                                Envoyer invitation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            const inviteModal = document.getElementById('inviteModal');
            const openInviteBtn = document.getElementById('openInviteModal');
            const closeInviteBtn = document.getElementById('closeInviteModal');
            const cancelInviteBtn = document.getElementById('cancelInviteModal');
            const inviteOverlay = document.getElementById('inviteModalOverlay');

            function openInviteModalFunc() {
                inviteModal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            function closeInviteModalFunc() {
                inviteModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            openInviteBtn?.addEventListener('click', openInviteModalFunc);
            closeInviteBtn?.addEventListener('click', closeInviteModalFunc);
            cancelInviteBtn?.addEventListener('click', closeInviteModalFunc);
            inviteOverlay?.addEventListener('click', closeInviteModalFunc);

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeInviteModalFunc();
            });
        </script>
    @endif
</body>
</html>
