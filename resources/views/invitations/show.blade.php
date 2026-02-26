<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Invitation</h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto space-y-4">
        <div class="bg-white p-6 rounded shadow">
            <p>Colocation : <strong>{{ $invitation->colocation->name }}</strong></p>
            <p>Owner : <strong>{{ $invitation->colocation->owner->name }}</strong></p>
            <p>Invité : <strong>{{ $invitation->invited_email }}</strong></p>
        </div>

        <div class="flex gap-3">
            <form method="POST" action="{{ route('invitations.accept', $invitation->token) }}">
                @csrf
                <button class="px-4 py-2 bg-green-600 text-white rounded">Accepter</button>
            </form>

            <form method="POST" action="{{ route('invitations.refuse', $invitation->token) }}">
                @csrf
                <button class="px-4 py-2 bg-red-600 text-white rounded">Refuser</button>
            </form>
        </div>
    </div>
</x-app-layout>