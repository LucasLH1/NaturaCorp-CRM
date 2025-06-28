<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Informations du profil</h2>
        <p class="mt-1 text-sm text-gray-600">Modifie les informations de ton compte.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
            <input id="name" name="name" type="text" class="w-full border rounded px-3 py-2" value="{{ old('name', $user->name) }}" required>
            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" class="w-full border rounded px-3 py-2" value="{{ old('email', $user->email) }}" required>
            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800">Ton adresse email n’est pas vérifiée.</p>
                    <button form="send-verification" class="text-sm text-blue-600 underline">Renvoyer le lien de vérification</button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-600">Un nouveau lien a été envoyé.</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
            @if (session('status') === 'profile-updated')
                <p class="text-sm text-gray-600">Enregistré.</p>
            @endif
        </div>
    </form>
</section>
