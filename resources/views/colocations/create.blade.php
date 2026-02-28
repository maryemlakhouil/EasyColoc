<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl">Créer une colocation</h2>
                <p class="text-sm text-gray-500">Donne un nom à ta colocation pour commencer.</p>
            </div>

            <a href="{{ route('colocations.my') }}"
               class="px-4 py-2 rounded-xl border hover:bg-gray-50 text-sm">
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto px-6">
        {{-- Messages --}}
        @if(session('error'))
            <div class="mb-5 bg-red-50 text-red-700 border border-red-200 p-3 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 bg-red-50 text-red-700 border border-red-200 p-3 rounded-xl">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Form card --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow border overflow-hidden">
                <div class="p-5 border-b">
                    <h3 class="font-semibold">Nouvelle colocation</h3>
                    <p class="text-sm text-gray-500 mt-1">Ex: “COLOC 1”, “Appart centre”, “Maison étudiants”</p>
                </div>

                <form method="POST" action="{{ route('colocations.store') }}" class="p-6 space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom</label>
                        <input
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="COLOC 1"
                            class="mt-1 w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                        @error('name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('colocations.my') }}"
                           class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                            Annuler
                        </a>

                        <button class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                            Créer la colocation
                        </button>
                    </div>
                </form>
            </div>

            {{-- Side info card --}}
            <div class="bg-white rounded-2xl shadow border p-5">
                <h3 class="font-semibold mb-3">Infos</h3>

                <div class="space-y-3 text-sm text-gray-700">
                    <div class="rounded-xl border p-3">
                        <div class="font-medium">Owner</div>
                        <div class="text-gray-500">Le créateur devient owner automatiquement.</div>
                    </div>

                    <div class="rounded-xl border p-3">
                        <div class="font-medium">Invitation</div>
                        <div class="text-gray-500">Tu pourras inviter des membres via email/token.</div>
                    </div>

                    <div class="rounded-xl border p-3">
                        <div class="font-medium">Règle</div>
                        <div class="text-gray-500">Un utilisateur ne peut avoir qu’une colocation active.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>