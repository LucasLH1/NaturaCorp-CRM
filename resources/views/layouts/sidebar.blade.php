<div class="h-screen w-64 bg-gray-900 text-white flex flex-col shadow-lg fixed">
    <!-- Header -->
    <div class="px-6 py-4 font-bold text-xl tracking-wide border-b border-gray-800">
        NaturaCorp
    </div>

    <!-- Navigation principale -->
    <div class="flex-1 overflow-y-auto px-2 py-4 text-sm space-y-2">
        <!-- Section PHARMACIES avec sous-menu -->
        <div x-data="{ open: false }" class="space-y-1">
            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 hover:bg-gray-800 rounded">
                <span class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 7h5l2 3h11v11H3z"/>
                    </svg>
                    <span>Pharmacies</span>
                </span>
                <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div x-show="open" class="pl-8 mt-1 space-y-1 text-gray-300" x-cloak>
                <a href="{{ route('pharmacies.index') }}" class="block hover:text-white">Liste</a>
                <a href="{{ route('pharmacies.create') }}" class="block hover:text-white">Créer</a>
            </div>
        </div>

        <a href="{{ route('commandes.index') }}" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-800 rounded">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 2h6a2 2 0 012 2v2H7V4a2 2 0 012-2zM7 6h10v14H7z"/>
            </svg>
            <span>Commandes</span>
        </a>

        <a href="{{ route('documents.index') }}" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-800 rounded">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 7h5l2 3h11v11H3z"/>
            </svg>
            <span>Documents</span>
        </a>

        <a href="{{ route('notifications.index') }}" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-800 rounded">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V11a6.002 6.002 0 00-4-5.659V4a2 2 0 00-4 0v1.341C7.67 7.165 6 9.388 6 12v2l-1 1v1h10z"
                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Notifications</span>
        </a>

        <a href="{{ route('rapports.index') }}" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-800 rounded">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 3v18h18M9 17v-6M15 17V9M21 17V3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>Rapports</span>
        </a>

        <a href="{{ route('journal.index') }}" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-800 rounded">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 4v16m8-16H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>Journal</span>
        </a>
    </div>

    <!-- Déconnexion fixe en bas -->
    <div class="border-t border-gray-800 px-4 py-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="flex items-center space-x-2 text-red-400 hover:text-red-600 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6-4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7"
                          stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>Déconnexion</span>
            </button>
        </form>
    </div>
</div>
