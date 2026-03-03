<x-app-layout>
    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-extrabold text-white tracking-wide">
                    {{ $colocation->name }}
                </h2>
                <p class="text-sm text-white/60">Dépenses & balances</p>
            </div>

            <button
                type="button"
                id="openExpenseModal"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                       bg-gradient-to-r from-indigo-600 to-cyan-600 text-white font-semibold
                       shadow-lg shadow-black/30 hover:brightness-110 transition"
            >
                <span class="text-lg">+</span>
                <span>Nouvelle dépense</span>
            </button>
        </div>
    </x-slot>

    {{-- PAGE BG --}}
    <div class="min-h-screen"
         style="background:
            radial-gradient(900px 500px at 15% 10%, rgba(59,130,246,.18), transparent 60%),
            radial-gradient(900px 500px at 90% 5%, rgba(6,182,212,.14), transparent 60%),
            radial-gradient(900px 500px at 50% 95%, rgba(16,185,129,.10), transparent 60%),
            linear-gradient(135deg, #0B1220, #0F172A);">

        <div class="py-6 max-w-7xl mx-auto px-6 space-y-6">

            {{-- FLASH MESSAGES --}}
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-emerald-200">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-2xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- COLONNE GAUCHE --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Dépenses récentes --}}
                    <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl shadow-black/30 overflow-hidden">
                        <div class="p-5 border-b border-white/10 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="font-semibold text-white">Dépenses récentes</h3>
                                <p class="text-xs text-white/50">Filtre mois/année disponible</p>
                            </div>

                            {{-- Filtre mois/année --}}
                            <form method="GET" action="{{ route('depences.index', $colocation) }}"
                                  class="flex flex-wrap gap-2 items-center">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-white/60">Filtrer :</span>
                                    <input name="month" value="{{ $month }}"
                                           class="w-20 rounded-xl border border-white/10 bg-white/5 text-white placeholder-white/30 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/60"
                                           placeholder="02">
                                    <input name="year" value="{{ $year }}"
                                           class="w-24 rounded-xl border border-white/10 bg-white/5 text-white placeholder-white/30 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500/60"
                                           placeholder="2026">
                                </div>

                                <button class="px-3 py-2 rounded-xl text-sm font-semibold text-white
                                               bg-gradient-to-r from-indigo-600 to-cyan-600 hover:brightness-110 transition">
                                    OK
                                </button>

                                <a class="px-3 py-2 rounded-xl text-sm font-semibold text-white/80
                                          border border-white/10 bg-white/5 hover:bg-white/10 transition"
                                   href="{{ route('depences.index', $colocation) }}">
                                    Tous
                                </a>
                            </form>
                        </div>

                        {{-- Table --}}
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="text-xs uppercase tracking-wider text-white/50 bg-white/5">
                                <tr class="border-b border-white/10">
                                    <th class="py-3 px-4 text-left">Titre / Catégorie</th>
                                    <th class="py-3 px-4 text-left">Payeur</th>
                                    <th class="py-3 px-4 text-left">Montant</th>
                                    <th class="py-3 px-4 text-right">Action</th>
                                </tr>
                                </thead>

                                <tbody class="divide-y divide-white/10">
                                @forelse($depences as $e)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="py-3 px-4">
                                            <div class="font-medium text-white">{{ $e->title }}</div>
                                            <div class="text-xs text-white/50">{{ $e->category->name }}</div>
                                        </td>

                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-cyan-500 text-white flex items-center justify-center font-bold">
                                                    {{ strtoupper(substr($e->payer->name, 0, 1)) }}
                                                </div>
                                                <div class="text-sm text-white/90">{{ $e->payer->name }}</div>
                                            </div>
                                        </td>

                                        <td class="py-3 px-4 font-semibold text-white">
                                            {{ number_format($e->amount, 2) }} €
                                        </td>

                                        <td class="py-3 px-4 text-right">
                                            @if(auth()->id() === $e->payer_id || auth()->id() === $colocation->owner_id)
                                                <form method="POST" action="{{ route('depences.destroy', $e) }}"
                                                      onsubmit="return confirm('Supprimer cette dépense ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="text-red-300 hover:text-red-200 hover:underline text-sm">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-8 px-4 text-white/50 text-center" colspan="4">
                                            Aucune dépense.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Synthèses --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl shadow-black/30 p-6">
                            <h3 class="font-semibold text-white mb-3">Synthèse par membre (total payé)</h3>
                            @if($byMember->isEmpty())
                                <p class="text-white/50">Aucune donnée.</p>
                            @else
                                <ul class="space-y-1 text-sm">
                                    @foreach($byMember as $payerId => $sum)
                                        <li class="flex items-center justify-between text-white/80">
                                            <span>User {{ $payerId }}</span>
                                            <strong class="text-white">{{ number_format($sum, 2) }}</strong>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl shadow-black/30 p-6">
                            <h3 class="font-semibold text-white mb-3">Synthèse par catégorie</h3>
                            @if($byCategory->isEmpty())
                                <p class="text-white/50">Aucune donnée.</p>
                            @else
                                <ul class="space-y-1 text-sm">
                                    @foreach($byCategory as $catId => $sum)
                                        <li class="flex items-center justify-between text-white/80">
                                            <span>Cat {{ $catId }}</span>
                                            <strong class="text-white">{{ number_format($sum, 2) }}</strong>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    {{-- Balances --}}
                    <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl shadow-black/30 p-6">
                        <h3 class="font-semibold text-white mb-3">Balances</h3>

                        @if($balances->isEmpty())
                            <p class="text-white/50">Aucune balance.</p>
                        @else
                            <ul class="space-y-2 text-sm">
                                @foreach($balances as $userId => $bal)
                                    <li class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                                        <div class="text-white/85">
                                            User {{ $userId }}
                                            <span class="text-xs text-white/45">( + reçoit / - paie )</span>
                                        </div>
                                        <div class="font-semibold {{ $bal >= 0 ? 'text-emerald-200' : 'text-red-200' }}">
                                            {{ number_format($bal, 2) }}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                </div>

                {{-- COLONNE DROITE --}}
                <div class="space-y-6">

                    {{-- Résumé --}}
                    <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl shadow-black/30 p-6">
                        <h3 class="font-semibold text-white mb-3">Résumé</h3>
                        <div class="space-y-1 text-sm text-white/80">
                            <p class="flex items-center justify-between">
                                <span>Total dépenses</span>
                                <strong class="text-white">{{ number_format($total, 2) }}</strong>
                            </p>
                            <p class="flex items-center justify-between">
                                <span>Nombre de membres</span>
                                <strong class="text-white">{{ $members->count() }}</strong>
                            </p>
                            <p class="flex items-center justify-between">
                                <span>Part individuelle</span>
                                <strong class="text-white">{{ number_format($share, 2) }}</strong>
                            </p>
                        </div>
                    </div>

                    {{-- Qui doit à qui --}}
                    <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl shadow-black/30 p-6">
                        <h3 class="font-semibold text-white mb-3">Qui doit à qui ?</h3>

                        @if($openSettlements->isEmpty())
                            <p class="text-white/50 text-sm">Aucun remboursement nécessaire.</p>
                        @else
                            <ul class="space-y-2">
                                @foreach($openSettlements as $s)
                                    <li class="rounded-2xl border border-white/10 bg-white/5 p-4 flex items-center justify-between gap-3">
                                        <div class="text-sm text-white/85">
                                            <strong class="text-white">{{ $s->fromUser->name }}</strong>
                                            doit
                                            <strong class="text-white">{{ number_format($s->montant, 2) }} €</strong>
                                            à
                                            <strong class="text-white">{{ $s->toUser->name }}</strong>
                                        </div>

                                        <form method="POST" action="{{ route('settlements.pay', $s) }}">
                                            @csrf
                                            <button class="px-3 py-2 rounded-xl text-sm font-semibold
                                                           border border-white/10 bg-white/5 text-white/90
                                                           hover:bg-white/10 transition">
                                                Marquer payé
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    {{-- Membres --}}
                    <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl shadow-black/30 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-white">Membres de la coloc</h3>
                            <span class="text-xs px-2 py-1 rounded-full border border-white/10 bg-white/5 text-white/70">
                                ACTIFS
                            </span>
                        </div>

                        <div class="space-y-3">
                            @foreach($members as $m)
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-3 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-cyan-500 text-white flex items-center justify-center font-bold">
                                            {{ strtoupper(substr($m->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-white">{{ $m->name }}</div>
                                            <div class="text-xs text-white/50">
                                                @if($m->id === $colocation->owner_id) OWNER @else MEMBER @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-sm text-white/70 font-semibold">
                                        {{ $m->reputation_score ?? 0 }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button id="openInviteModal"
                                class="mt-4 w-full rounded-2xl px-4 py-2 text-sm font-semibold
                                       border border-white/10 bg-white/5 text-white/90
                                       hover:bg-white/10 transition">
                            + Inviter un membre
                        </button>
                    </div>

                </div>
            </div>

        </div>

        {{-- MODAL Nouvelle dépense --}}
        <div id="expenseModal" class="fixed inset-0 z-50 hidden">
            <div id="expenseModalOverlay" class="absolute inset-0 bg-black/60"></div>

            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="w-full max-w-3xl rounded-3xl border border-white/10 bg-white shadow-2xl">
                    <div class="flex items-center justify-between px-6 py-4 border-b">
                        <h3 class="text-xl font-semibold">Nouvelle dépense</h3>
                        <button type="button" id="closeExpenseModal" class="p-2 rounded-xl hover:bg-gray-100">✕</button>
                    </div>

                    <form method="POST" action="{{ route('depences.store', $colocation) }}" class="p-6 space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Titre</label>
                            <input name="title"
                                   class="mt-1 w-full border rounded-xl p-2 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                   placeholder="Ex: facture internet" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Montant (€)</label>
                                <input name="amount" type="number" step="0.01"
                                       class="mt-1 w-full border rounded-xl p-2 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                       placeholder="0.00" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date</label>
                                <input name="date" type="date"
                                       class="mt-1 w-full border rounded-xl p-2 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                       required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payé par</label>
                                <select name="payer_id"
                                        class="mt-1 w-full border rounded-xl p-2 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                        required>
                                    @foreach($members as $m)
                                        <option value="{{ $m->id }}" @selected(auth()->id() === $m->id)>
                                            {{ $m->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Catégorie</label>
                                <select name="category_id"
                                        class="mt-1 w-full border rounded-xl p-2 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                        required>
                                    <option value="">Choisir...</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" id="cancelExpenseModal" class="px-4 py-2 rounded-xl border">
                                Annuler
                            </button>
                            <button class="px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-cyan-600 text-white font-semibold hover:brightness-110 transition">
                                Enregistrer
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{-- MODAL Invitation --}}
        <div id="inviteModal" class="fixed inset-0 z-50 hidden">
            <div id="inviteModalOverlay" class="absolute inset-0 bg-black/60"></div>

            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="w-full max-w-lg rounded-3xl border border-white/10 bg-white shadow-2xl">
                    <div class="flex items-center justify-between px-6 py-4 border-b">
                        <h3 class="text-xl font-semibold">Inviter un membre</h3>
                        <button type="button" id="closeInviteModal" class="p-2 rounded-xl hover:bg-gray-100">✕</button>
                    </div>

                    <form method="POST" action="{{ route('invitations.store', $colocation) }}" class="p-6 space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <select name="email" class="mt-1 w-full border rounded-xl p-2" required>
                                <option value="">Choisir un email...</option>
                                @foreach($availableUsers as $u)
                                    <option value="{{ $u->email }}">{{ $u->email }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" id="cancelInviteModal" class="px-4 py-2 rounded-xl border">
                                Annuler
                            </button>
                            <button class="px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-cyan-600 text-white font-semibold hover:brightness-110 transition">
                                Envoyer invitation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Expense modal
            const expenseModal = document.getElementById('expenseModal');
            const openExpenseBtn = document.getElementById('openExpenseModal');
            const closeExpenseBtn = document.getElementById('closeExpenseModal');
            const cancelExpenseBtn = document.getElementById('cancelExpenseModal');
            const expenseOverlay = document.getElementById('expenseModalOverlay');

            function openExpenseModal() {
                expenseModal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            function closeExpenseModal() {
                expenseModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            openExpenseBtn?.addEventListener('click', openExpenseModal);
            closeExpenseBtn?.addEventListener('click', closeExpenseModal);
            cancelExpenseBtn?.addEventListener('click', closeExpenseModal);
            expenseOverlay?.addEventListener('click', closeExpenseModal);

            // Invite modal
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

            // ESC to close any modal
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeExpenseModal();
                    closeInviteModal();
                }
            });
        </script>
    </div>
</x-app-layout>