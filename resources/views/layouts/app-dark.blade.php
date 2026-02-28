@extends('layouts.app-dark')

@section('title', 'Colocation - '.$colocation->name)

@section('page_title')
    {{ $colocation->name }}
@endsection

@section('page_subtitle')
    Détails & membres
@endsection

@section('header_actions')
    <a href="{{ route('dashboard') }}" class="btn-ghost text-sm">Retour</a>

    <a href="{{ route('depences.index', $colocation) }}" class="btn-primary text-sm">
        Voir dépenses
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Infos --}}
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4">Informations</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-xl border border-slate-700/60 p-4">
                        <div class="text-sm text-slate-400">Statut</div>
                        <div class="text-lg font-semibold">{{ $colocation->status }}</div>
                    </div>

                    <div class="rounded-xl border border-slate-700/60 p-4">
                        <div class="text-sm text-slate-400">Owner</div>
                        <div class="text-lg font-semibold">{{ $colocation->owner->name }}</div>
                        <div class="text-sm text-slate-400">{{ $colocation->owner->email }}</div>
                    </div>
                </div>

                {{-- Quitter si member --}}
                @if(auth()->id() !== $colocation->owner_id)
                    <form method="POST" action="{{ route('colocations.leave', $colocation) }}"
                          class="mt-5"
                          onsubmit="return confirm('Voulez-vous quitter cette colocation ?')">
                        @csrf
                        <button class="w-full px-4 py-2 rounded-lg border border-red-700/40 bg-red-950/30 text-red-200 hover:bg-red-950/50">
                            Quitter la colocation
                        </button>
                    </form>
                @endif
            </div>

            {{-- Membres --}}
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Membres</h3>
                    <span class="badge">{{ $colocation->members->count() }} membre(s)</span>
                </div>

                @if($colocation->members->isEmpty())
                    <p class="text-slate-400">Aucun membre pour le moment.</p>
                @else
                    <div class="space-y-3">
                        @foreach($colocation->members as $member)
                            <div class="rounded-xl border border-slate-700/60 p-4 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center font-bold">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <div class="font-medium">
                                            {{ $member->name }}
                                            <span class="text-slate-400">({{ $member->email }})</span>
                                        </div>

                                        <div class="text-sm text-slate-400">
                                            Rôle :
                                            @if($member->id === $colocation->owner_id)
                                                <span class="text-cyan-300 font-semibold">Owner</span>
                                            @else
                                                <span class="text-indigo-300 font-semibold">Member</span>
                                            @endif
                                            — Réputation :
                                            <span class="text-white font-semibold">{{ $member->reputation_score ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="badge">{{ $member->pivot->status }}</span>

                                    {{-- Retirer (owner seulement) --}}
                                    @if(auth()->id() === $colocation->owner_id && $member->id !== $colocation->owner_id)
                                        <form method="POST" action="{{ route('colocations.members.remove', [$colocation, $member]) }}"
                                              onsubmit="return confirm('Retirer ce membre ?')">
                                            @csrf
                                            <button class="px-3 py-2 rounded-lg border border-red-700/40 bg-red-950/30 text-red-200 hover:bg-red-950/50 text-sm">
                                                Retirer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="space-y-6">

            {{-- Invitation --}}
            @if(auth()->id() === $colocation->owner_id)
                <div class="card p-6">
                    <h3 class="text-lg font-semibold mb-2">Inviter un membre</h3>
                    <p class="text-sm text-slate-400 mb-4">Envoyer une invitation par email.</p>

                    <form method="POST" action="{{ route('invitations.store', $colocation) }}" class="space-y-3">
                        @csrf

                        <select name="email" class="w-full rounded-lg bg-slate-950/40 border border-slate-700/60 p-2 text-slate-100" required>
                            <option value="">Choisir un email...</option>
                            @foreach($usersEmails as $u)
                                <option value="{{ $u->email }}">{{ $u->email }}</option>
                            @endforeach
                        </select>

                        <button class="btn-primary w-full">Envoyer invitation</button>
                    </form>
                </div>
            @endif

            {{-- Carte actions rapides --}}
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4">Actions rapides</h3>

                <a href="{{ route('depences.index', $colocation) }}"
                   class="btn-ghost w-full inline-flex justify-center">
                    Ouvrir les dépenses
                </a>
            </div>

        </div>
    </div>
@endsection