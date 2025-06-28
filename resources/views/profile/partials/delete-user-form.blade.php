<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Supprimer le compte</h2>
        <p class="mt-1 text-sm text-gray-600">Cette action est irréversible.</p>
    </header>

    <button onclick="document.getElementById('confirm-delete-modal').showModal()"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
        Supprimer le compte
    </button>

    <dialog id="confirm-delete-modal" class="rounded shadow-md w-full max-w-md p-6">
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 mb-2">Confirmation requise</h2>
            <p class="text-sm text-gray-600 mb-4">
                Tu es sur le point de supprimer ton compte. Entre ton mot de passe pour confirmer.
            </p>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input id="password" name="password" type="password"
                       class="w-full border rounded px-3 py-2 mt-1" placeholder="••••••••••">
                @error('password', 'userDeletion') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('confirm-delete-modal').close()"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Annuler</button>
                <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Supprimer</button>
            </div>
        </form>
    </dialog>
</section>
