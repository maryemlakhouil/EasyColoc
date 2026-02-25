<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Créer une colocation</h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto">
        @if(session('error'))
            <div class="mb-4 text-red-600">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('colocations.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block font-medium">Nom</label>
                <input name="name" value="{{ old('name') }}" class="w-full border rounded p-2" required>
                @error('name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            <button class="px-4 py-2 bg-indigo-600 text-white rounded">
                Créer
            </button>
        </form>
    </div>
</x-app-layout>