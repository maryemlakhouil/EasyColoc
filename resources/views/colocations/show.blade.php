<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ $colocation->name }}</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        @if(session('success'))
            <div class="text-green-600 mb-2">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="text-red-600 mb-2">{{ session('error') }}</div>
        @endif

        <div class="bg-white p-6 rounded shadow space-y-2">
            <p><strong>Status:</strong> {{ $colocation->status }}</p>
            <p><strong>Owner:</strong> {{ $colocation->owner->name }} ({{ $colocation->owner->email }})</p>
        </div>
    </div>
    @if(auth()->id() === $colocation->owner_id)
    @if(session('error')) <div class="text-red-600 mb-2">{{ session('error') }}</div> @endif
    @if(session('success')) <div class="text-green-600 mb-2">{{ session('success') }}</div> @endif
     <form method="POST" action="{{ route('invitations.store', $colocation) }}" class="mt-4 flex gap-2">
        @csrf

        <select name="email" class="border rounded p-2 flex-1" required>
            <option value="">Choisir un email...</option>
            @foreach($usersEmails as $u)
                <option value="{{ $u->email }}">{{ $u->email }}</option>
            @endforeach
        </select>

        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Inviter</button>
    </form>
  
@endif

<div class="mt-6 bg-white p-6 rounded shadow">
    <h3 class="font-semibold text-lg mb-4">Membres</h3>

    <ul class="space-y-3">
        @foreach($colocation->members as $member)
            <li class="flex items-center justify-between border-b pb-2">
                <div>
                    <div class="font-medium">
                        {{ $member->name }} <span class="text-gray-500">({{ $member->email }})</span>
                    </div>

                    <div class="text-sm text-gray-600">
                        Rôle :
                        @if($member->id === $colocation->owner_id)
                            <span class="font-semibold">Owner</span>
                        @else
                            <span class="font-semibold">Member</span>
                        @endif
                        — Réputation : <span class="font-semibold">{{ $member->reputation_score ?? 0 }}</span>
                    </div>
                </div>

                <div class="text-sm text-gray-500">
                    Statut : {{ $member->pivot->status }}
                </div>
            </li>
        @endforeach
    </ul>

    @if($colocation->members->isEmpty())
        <p class="text-gray-500">Aucun membre pour le moment.</p>
    @endif
</div>
</x-app-layout>

