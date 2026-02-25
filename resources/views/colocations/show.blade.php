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
</x-app-layout>