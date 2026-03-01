<x-app-layout>
    {{-- Header --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl uppercase tracking-wide">{{ $colocation->name }}</h2>
                <p class="text-sm text-gray-500">Détails colocation & membres</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}"
                   class="px-4 py-2 rounded-lg border bg-slate-900 text-white hover:bg-slate-800">
                    ← Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-6 space-y-6">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="bg-green-50 text-green-700 border border-green-200 p-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 text-red-700 border border-red-200 p-3 rounded-xl">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-50 text-red-700 border border-red-200 p-3 rounded-xl">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- COLONNE GAUCHE --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Carte Infos --}}
                <div class="bg-white rounded-2xl shadow border">
                    <div class="p-5 border-b">
                        <h3 class="font-semibold">Informations</h3>
                    </div>

                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-xl border p-4">
                            <div class="text-sm text-gray-500">Status</div>
                            <div class="text-lg font-semibold">{{ $colocation->status }}</div>
                        </div>

                        <div class="rounded-xl border p-4">
                            <div class="text-sm text-gray-500">Owner</div>
                            <div class="text-lg font-semibold">{{ $colocation->owner->name }}</div>
                            <div class="text-sm text-gray-500">{{ $colocation->owner->email }}</div>
                        </div>
                        @if(auth()->id() === $colocation->owner_id)
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('colocations.cancel', $colocation) }}"
                                onsubmit="return confirm('Annuler la colocation ? Les membres vont quitter automatiquement.')">
                                @csrf
                                <button class="px-4 py-2 rounded bg-orange-600 text-white">Annuler la colocation</button>
                            </form>

                            <form method="POST" action="{{ route('colocations.destroy', $colocation) }}"
                                onsubmit="return confirm('Supprimer définitivement ? Toutes les données seront supprimées.')">
                                @csrf
                                @method('DELETE')
                                <button class="px-4 py-2 rounded bg-red-600 text-white">Supprimer définitivement</button>
                            </form>
                        </div>
                    @endif
                    </div>

                    {{-- Bouton Quitter si Member --}}
                    @if(auth()->id() !== $colocation->owner_id)
                        <div class="px-5 pb-5">
                            <form method="POST" action="{{ route('colocations.leave', $colocation) }}"
                                  onsubmit="return confirm('Voulez-vous quitter cette colocation ?')">
                                @csrf
                                <button class="w-full px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                                    Quitter la colocation
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                {{-- Membres (liste principale) --}}
                <div class="bg-white rounded-2xl shadow border">
                    <div class="p-5 border-b flex items-center justify-between">
                        <h3 class="font-semibold">Membres</h3>
                        <span class="text-sm text-gray-500">{{ $colocation->members->count() }} membre(s)</span>
                    </div>

                    <div class="p-5">
                        @if($colocation->members->isEmpty())
                            <p class="text-gray-500">Aucun membre pour le moment.</p>
                        @else
                            <div class="space-y-3">
                                @foreach($colocation->members as $member)
                                    <div class="rounded-xl border p-4 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-semibold">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>

                                            <div>
                                                <div class="font-medium">
                                                    {{ $member->name }}
                                                    <span class="text-gray-500">({{ $member->email }})</span>
                                                </div>

                                                <div class="text-sm text-gray-600">
                                                    Rôle :
                                                    @if($member->id === $colocation->owner_id)
                                                        <span class="font-semibold">Owner</span>
                                                    @else
                                                        <span class="font-semibold">Member</span>
                                                    @endif
                                                    — Réputation :
                                                    <span class="font-semibold">{{ $member->reputation_score ?? 0 }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3">
                                            <span class="text-xs px-2 py-1 rounded-full border">
                                                {{ $member->pivot->status }}
                                            </span>

                                            {{-- Retirer (owner seulement + pas owner) --}}
                                            @if(auth()->id() === $colocation->owner_id && $member->id !== $colocation->owner_id)
                                                <form method="POST" action="{{ route('colocations.members.remove', [$colocation, $member]) }}"
                                                      onsubmit="return confirm('Retirer ce membre ?')">
                                                    @csrf
                                                    <button class="px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 text-sm">
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

            </div>

            {{-- COLONNE DROITE --}}
            <div class="space-y-6">

                {{-- Bloc "Qui doit à qui ?" (placeholder si tu n'as pas encore settlements ici) --}}
                <div class="bg-white rounded-2xl shadow border p-5">
                    <h3 class="font-semibold mb-3">Qui doit à qui ?</h3>

                    {{-- Option 1 : si tu passes $openSettlements à cette vue --}}
                    @isset($openSettlements)
                        @if($openSettlements->isEmpty())
                            <p class="text-gray-500 text-sm">Aucun remboursement en attente.</p>
                        @else
                            <div class="space-y-3">
                                @foreach($openSettlements as $s)
                                    <div class="rounded-xl border p-4 flex items-center justify-between">
                                        <div class="text-sm text-gray-700">
                                            <strong>{{ $s->fromUser->name }}</strong>
                                            doit
                                            <strong>{{ number_format($s->amount, 2) }} €</strong>
                                            à
                                            <strong>{{ $s->toUser->name }}</strong>
                                        </div>

                                        <form method="POST" action="{{ route('settlements.pay', $s) }}">
                                            @csrf
                                            <button class="px-3 py-2 rounded-lg border text-sm hover:bg-gray-50">
                                                Marquer payé
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        {{-- Option 2 : si tu n'as pas encore la partie paiement ici --}}
                        <p class="text-gray-500 text-sm">Aucun remboursement en attente.</p>
                    @endisset
                </div>

                {{-- Carte sombre membres (comme screenshot) --}}
                <div class="rounded-2xl shadow p-5 text-white"
                     style="background: linear-gradient(180deg, #0f172a, #111827);">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold">Membres de la coloc</h3>
                        <span class="text-xs bg-white/10 px-2 py-1 rounded-full">ACTIFS</span>
                    </div>

                    <div class="space-y-3">
                        @foreach($colocation->members as $m)
                            <div class="bg-white/5 rounded-xl p-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center font-semibold">
                                        {{ strtoupper(substr($m->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $m->name }}</div>
                                        <div class="text-xs text-white/60">
                                            @if($m->id === $colocation->owner_id) OWNER @else MEMBER @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="text-sm text-white/70">
                                    {{ $m->reputation_score ?? 0 }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Bouton inviter seulement pour owner --}}
                    @if(auth()->id() === $colocation->owner_id)
                        <button id="openInviteModal"
                                class="mt-4 w-full rounded-xl bg-white/10 hover:bg-white/15 py-2 text-sm">
                            + Inviter un membre
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL Invitation (owner seulement) --}}
    @if(auth()->id() === $colocation->owner_id)
        <div id="inviteModal" class="fixed inset-0 z-50 hidden">
            <div id="inviteModalOverlay" class="absolute inset-0 bg-black/50"></div>

            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl">
                    <div class="flex items-center justify-between px-6 py-4 border-b">
                        <h3 class="text-xl font-semibold">Inviter un membre</h3>
                        <button type="button" id="closeInviteModal" class="p-2 rounded-lg hover:bg-gray-100">✕</button>
                    </div>

                    <form method="POST" action="{{ route('invitations.store', $colocation) }}" class="p-6 space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <select name="email" class="mt-1 w-full border rounded-lg p-2" required>
                                <option value="">Choisir un email...</option>
                                @foreach($usersEmails as $u)
                                    <option value="{{ $u->email }}">{{ $u->email }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" id="cancelInviteModal" class="px-4 py-2 rounded-lg border">
                                Annuler
                            </button>
                            <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
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

            function openInviteModal() {
                inviteModal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            function closeInviteModal() {
                inviteModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            openInviteBtn?.addEventListener('click', openInviteModal);
            closeInviteBtn?.addEventListener('click', closeInviteModal);
            cancelInviteBtn?.addEventListener('click', closeInviteModal);
            inviteOverlay?.addEventListener('click', closeInviteModal);

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeInviteModal();
            });
        </script>
    @endif
</x-app-layout>