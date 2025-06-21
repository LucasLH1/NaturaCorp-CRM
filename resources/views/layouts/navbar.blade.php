<div class="h-16 bg-white border-b border-gray-200 px-6 flex items-center justify-end shadow-sm">
    <div class="flex items-center space-x-4">
        <x-notification-bell />

        <!-- Profil -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center space-x-2 text-sm focus:outline-none">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=32"
                     class="w-8 h-8 rounded-full" alt="Avatar">
                <span class="text-gray-800">{{ auth()->user()->name }}</span>
            </button>
            <div x-show="open" @click.away="open = false"
                 class="absolute right-0 mt-2 w-48 bg-white border rounded shadow text-sm z-50"
                 style="display: none;">
                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2 hover:bg-gray-50 text-gray-700">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-4 py-2 hover:bg-gray-50 text-gray-700">
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
