<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Mot de passe</h2>
        <p class="mt-1 text-sm text-gray-600">Change ton mot de passe ici.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700">Mot de passe actuel</label>
            <input id="current_password" name="current_password" type="password" class="w-full border rounded px-3 py-2" autocomplete="current-password">
            @error('current_password', 'updatePassword') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
            <input id="password" name="password" type="password" class="w-full border rounded px-3 py-2" autocomplete="new-password">
            @error('password', 'updatePassword') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmation</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="w-full border rounded px-3 py-2" autocomplete="new-password">
            @error('password_confirmation', 'updatePassword') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-4">
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
            @if (session('status') === 'password-updated')
                <p class="text-sm text-gray-600">Mot de passe mis à jour.</p>
            @endif
        </div>
    </form>
</section>
