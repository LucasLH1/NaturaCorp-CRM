<x-guest-layout>
    <div class="max-w-md w-full mx-auto bg-white p-8 rounded-2xl shadow-md border border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Connexion à votre compte</h1>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    autocomplete="username"
                    required
                    autofocus
                    value="{{ old('email') }}"
                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                >
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    required
                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                >
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember me + Forgot password -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center text-sm text-gray-600">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ml-2">Se souvenir de moi</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>

            <!-- Submit -->
            <div>
                <button
                    type="submit"
                    class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Se connecter
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
