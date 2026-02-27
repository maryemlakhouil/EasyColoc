<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Dashboard</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto space-y-6">

        {{-- Si aucune colocation --}}
        @if(!$colocation)
            <div class="bg-white p-6 rounded shadow">
                <p class="text-gray-700">Vous n’avez pas de colocation active.</p>
                <a href="{{ route('colocations.create') }}" class="text-indigo-600 underline">
                    Créer une colocation
                </a>
            </div>
        @else

            {{-- Bouton quitter (seulement si pas owner) --}}
            @if(auth()->id() !== $colocation->owner_id)
                <form method="POST" action="{{ route('colocations.leave', $colocation) }}">
                    @csrf
                    <button class="px-4 py-2 bg-red-600 text-white rounded">
                        Quitter la colocation
                    </button>
                </form>
            @endif
            <a class="text-indigo-600 underline" href="{{ route('depences.index', $colocation) }}">Voir dépenses</a>

            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold text-lg">{{ $colocation->name }}</h3>
                <p class="text-sm text-gray-600">
                    Status: <strong>{{ $colocation->status }}</strong> —
                    Owner: <strong>{{ $colocation->owner->name }}</strong>
                </p>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold text-lg mb-4">Membres</h3>

                @if($colocation->members->isEmpty())
                    <p class="text-gray-500">Aucun membre pour le moment.</p>
                @else
                    <ul class="space-y-3">
                        @foreach($colocation->members as $member)
                            <li class="flex items-center justify-between border-b pb-2">
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

                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-500">{{ $member->pivot->status }}</span>

                                    {{-- Bouton retirer (seulement owner, et pas sur owner) --}}
                                    @if(auth()->id() === $colocation->owner_id && $member->id !== $colocation->owner_id)
                                        <form method="POST" action="{{ route('colocations.members.remove', [$colocation, $member]) }}">
                                            @csrf
                                            <button class="px-3 py-1 bg-red-600 text-white rounded text-sm">
                                                Retirer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        @endif

    </div>
</x-app-layout>