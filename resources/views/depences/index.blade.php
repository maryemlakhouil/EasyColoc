<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl">{{ $colocation->name }}</h2>
                <p class="text-sm text-gray-500">Dépenses & balances</p>
            </div>

            <button
                type="button"
                id="openExpenseModal"
                class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700"
            >
                + Nouvelle dépense
            </button>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-6 space-y-6">

        {{-- Messages --}}
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- COLONNE GAUCHE --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Carte Dépenses récentes --}}
                <div class="bg-white rounded-2xl shadow border">
                    <div class="p-4 border-b flex flex-wrap items-center justify-between gap-3">
                        <h3 class="font-semibold">Dépenses récentes</h3>

                        {{-- Filtre mois/année --}}
                        <form method="GET" action="{{ route('depences.index', $colocation) }}" class="flex flex-wrap gap-2 items-center">
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-600">Filtrer :</span>
                                <input name="month" value="{{ $month }}" class="border rounded-lg p-2 text-sm w-20" placeholder="02">
                                <input name="year" value="{{ $year }}" class="border rounded-lg p-2 text-sm w-24" placeholder="2026">
                            </div>

                            <button class="px-3 py-2 bg-indigo-600 text-white rounded-lg text-sm">
                                OK
                            </button>

                            <a class="px-3 py-2 border rounded-lg text-sm" href="{{ route('depences.index', $colocation) }}">
                                Tous
                            </a>
                        </form>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="text-xs text-gray-500 bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 text-left">TITRE / CATÉGORIE</th>
                                    <th class="py-3 px-4 text-left">PAYEUR</th>
                                    <th class="py-3 px-4 text-left">MONTANT</th>
                                    <th class="py-3 px-4 text-right">ACTION</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y">
                                @forelse($depences as $e)
                                    <tr>
                                        <td class="py-3 px-4">
                                            <div class="font-medium">{{ $e->title }}</div>
                                            <div class="text-xs text-gray-500">{{ $e->category->name }}</div>
                                        </td>

                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-semibold">
                                                    {{ strtoupper(substr($e->payer->name, 0, 1)) }}
                                                </div>
                                                <div class="text-sm">{{ $e->payer->name }}</div>
                                            </div>
                                        </td>

                                        <td class="py-3 px-4 font-semibold">
                                            {{ number_format($e->amount, 2) }} €
                                        </td>

                                        <td class="py-3 px-4 text-right">
                                            @if(auth()->id() === $e->payer_id || auth()->id() === $colocation->owner_id)
                                                <form method="POST" action="{{ route('depences.destroy', $e) }}"
                                                      onsubmit="return confirm('Supprimer cette dépense ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="text-red-600 hover:underline text-sm">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-6 px-4 text-gray-500" colspan="4">Aucune dépense.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- (Optionnel) Synthèses + balances en bas --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-2xl shadow border">
                        <h3 class="font-semibold mb-3">Synthèse par membre (total payé)</h3>
                        @if($byMember->isEmpty())
                            <p class="text-gray-500">Aucune donnée.</p>
                        @else
                            <ul class="space-y-1 text-sm">
                                @foreach($byMember as $payerId => $sum)
                                    <li>User {{ $payerId }} : <strong>{{ number_format($sum, 2) }}</strong></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow border">
                        <h3 class="font-semibold mb-3">Synthèse par catégorie</h3>
                        @if($byCategory->isEmpty())
                            <p class="text-gray-500">Aucune donnée.</p>
                        @else
                            <ul class="space-y-1 text-sm">
                                @foreach($byCategory as $catId => $sum)
                                    <li>Cat {{ $catId }} : <strong>{{ number_format($sum, 2) }}</strong></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow border">
                    <h3 class="font-semibold mb-3">Balances</h3>
                    @if($balances->isEmpty())
                        <p class="text-gray-500">Aucune balance.</p>
                    @else
                        <ul class="space-y-1 text-sm">
                            @foreach($balances as $userId => $bal)
                                <li>
                                    User {{ $userId }} :
                                    <strong>{{ number_format($bal, 2) }}</strong>
                                    <span class="text-xs text-gray-500">( + reçoit / - paie )</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

            </div>

            {{-- COLONNE DROITE --}}
            <div class="space-y-6">

                {{-- Qui doit à qui --}}
                <div class="bg-white rounded-2xl shadow border p-4">
                    <h3 class="font-semibold mb-3">Qui doit à qui ?</h3>

                    @if(empty($settlements))
                        <p class="text-gray-500 text-sm">Aucun remboursement nécessaire.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($settlements as $s)
                                <div class="rounded-xl border p-3 flex items-center justify-between">
                                    <div>
                                        <div class="text-sm text-gray-600">
                                            User {{ $s['from'] }} → User {{ $s['to'] }}
                                        </div>
                                        <div class="text-lg font-semibold text-emerald-600">
                                            {{ number_format($s['amount'], 2) }} €
                                        </div>
                                    </div>

                                    {{-- bouton demo (paiement plus tard) --}}
                                    <button class="px-3 py-2 rounded-lg border text-sm hover:bg-gray-50">
                                        Marquer payé
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Membres --}}
                <div class="rounded-2xl shadow p-4 text-white"
                     style="background: linear-gradient(180deg, #0f172a, #111827);">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold">Membres de la coloc</h3>
                        <span class="text-xs bg-white/10 px-2 py-1 rounded-full">ACTIFS</span>
                    </div>

                    <div class="space-y-3">
                        @foreach($members as $m)
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

                    <button id="openInviteModal" class="mt-4 w-full rounded-xl bg-white/10 hover:bg-white/15 py-2 text-sm">
                        + Inviter un membre
                    </button>
                </div>

            </div>
        </div>

    </div>

    {{-- MODAL Nouvelle dépense --}}
    <div id="expenseModal" class="fixed inset-0 z-50 hidden">
        <div id="expenseModalOverlay" class="absolute inset-0 bg-black/50"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl">
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="text-xl font-semibold">Nouvelle dépense</h3>
                    <button type="button" id="closeExpenseModal" class="p-2 rounded-lg hover:bg-gray-100">✕</button>
                </div>

                <form method="POST" action="{{ route('depences.store', $colocation) }}" class="p-6 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Titre</label>
                        <input name="title"
                               class="mt-1 w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="Ex: facture internet" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Montant (€)</label>
                            <input name="amount" type="number" step="0.01"
                                   class="mt-1 w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="0.00" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date</label>
                            <input name="date" type="date"
                                   class="mt-1 w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Payé par</label>
                            <select name="payer_id"
                                    class="mt-1 w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
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
                                    class="mt-1 w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required>
                                <option value="">Choisir...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" id="cancelExpenseModal" class="px-4 py-2 rounded-lg border">
                            Annuler
                        </button>
                        <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                            Enregistrer la dépense
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
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
                        @foreach($availableUsers as $u)
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
        const modal = document.getElementById('expenseModal');
        const openBtn = document.getElementById('openExpenseModal');
        const closeBtn = document.getElementById('closeExpenseModal');
        const cancelBtn = document.getElementById('cancelExpenseModal');
        const overlay = document.getElementById('expenseModalOverlay');

        function openModal() {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        openBtn?.addEventListener('click', openModal);
        closeBtn?.addEventListener('click', closeModal);
        cancelBtn?.addEventListener('click', closeModal);
        overlay?.addEventListener('click', closeModal);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });
   
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
</script>

    
</x-app-layout>