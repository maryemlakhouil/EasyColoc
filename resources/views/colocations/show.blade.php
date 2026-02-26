<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ $colocation->name }}</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        @if(session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
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
</x-app-layout>

