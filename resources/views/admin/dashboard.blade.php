<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl">Admin — Tableau de bord</h2>
                <p class="text-sm text-gray-500">Statistiques globales & gestion des utilisateurs</p>
            </div>

            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">
                Retour dashboard
            </a>
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

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl shadow border p-5">
                <div class="text-sm text-gray-500">Utilisateurs</div>
                <div class="text-3xl font-semibold">{{ $totalUsers }}</div>
            </div>

            <div class="bg-white rounded-2xl shadow border p-5">
                <div class="text-sm text-gray-500">Colocations</div>
                <div class="text-3xl font-semibold">{{ $totalColocations }}</div>
            </div>

            <div class="bg-white rounded-2xl shadow border p-5">
                <div class="text-sm text-gray-500">Dépenses totales</div>
                <div class="text-3xl font-semibold">{{ number_format($totalDepences, 2) }} €</div>
            </div>

            <div class="bg-white rounded-2xl shadow border p-5">
                <div class="text-sm text-gray-500">Bannis</div>
                <div class="text-3xl font-semibold">{{ $bannedUsers->count() }}</div>
                
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Tous les users --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow border overflow-hidden">
                <div class="p-4 border-b flex items-center justify-between">
                    <h3 class="font-semibold">Utilisateurs</h3>
                    <span class="text-sm text-gray-500">{{ $users->total() }} total</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-xs text-gray-500 bg-gray-50">
                            <tr>
                                <th class="py-3 px-4 text-left">ID</th>
                                <th class="py-3 px-4 text-left">Nom</th>
                                <th class="py-3 px-4 text-left">Email</th>
                                <th class="py-3 px-4 text-left">Role</th>
                                <th class="py-3 px-4 text-left">Statut</th>
                                <th class="py-3 px-4 text-right">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @foreach($users as $u)
                                <tr>
                                    <td class="py-3 px-4">{{ $u->id }}</td>
                                    <td class="py-3 px-4">{{ $u->name }}</td>
                                    <td class="py-3 px-4">{{ $u->email }}</td>
                                    <td class="py-3 px-4">{{ $u->role }}</td>
                                    <td class="py-3 px-4">
                                        @if($u->is_banned)
                                            <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">Banni</span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Actif</span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-4 text-right">
                                        {{-- éviter auto-ban --}}
                                        @if(auth()->id() === $u->id)
                                            <span class="text-xs text-gray-400">—</span>
                                        @else
                                            @if($u->is_banned)
                                                <form method="POST" action="{{ route('admin.users.unban', $u) }}">
                                                    @csrf
                                                    <button class="px-3 py-2 rounded-lg border text-sm hover:bg-gray-50">
                                                        Débannir
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.users.ban', $u) }}">
                                                    @csrf
                                                    <button class="px-3 py-2 rounded-lg bg-red-600 text-white text-sm hover:bg-red-700">
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

                <div class="p-4">
                    {{ $users->links() }}
                </div>
            </div>

            {{-- Liste bannis --}}
            <div class="bg-white rounded-2xl shadow border overflow-hidden">
                <div class="p-4 border-b flex items-center justify-between">
                    <h3 class="font-semibold">Utilisateurs bannis</h3>
                    <span class="text-sm text-gray-500">{{ $bannedUsers->count() }}</span>
                </div>

                <div class="p-4 space-y-3">
                    @forelse($bannedUsers as $bu)
                        <div class="rounded-xl border p-3 flex items-center justify-between">
                            <div>
                                <div class="font-medium">{{ $bu->name }}</div>
                                <div class="text-xs text-gray-500">{{ $bu->email }}</div>
                            </div>

                            <form method="POST" action="{{ route('admin.users.unban', $bu) }}">
                                @csrf
                                <button class="px-3 py-2 rounded-lg border text-sm hover:bg-gray-50">
                                    Débannir
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Aucun utilisateur banni.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>