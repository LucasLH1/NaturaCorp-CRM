<div class="h-screen w-64 bg-gray-900 text-white flex flex-col shadow-lg fixed">
    <!-- Logo / Titre -->
    <div class="px-6 py-4 font-bold text-xl tracking-wide border-b border-gray-800">
        NaturaCorp
    </div>

    <!-- Navigation principale -->
    <div class="flex-1 overflow-y-auto px-2 py-4 text-sm space-y-2">

        <!-- Tableau de bord -->
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-800 rounded">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m4 0a9 9 0 11-18 0 9 9 0 0118 0z"
                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Tableau de bord</span>
        </a>

        <!-- Section PHARMACIES -->
        <div x-data="{ open: false }" class="space-y-1">
            <button @click="open = !open"
                    class="flex items-center justify-between w-full px-3 py-2 hover:bg-gray-800 rounded">
                <span class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M3 7h5l2 3h11v11H3z"
                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Pharmacies</span>
                </span>
                <svg :class="{ 'rotate-90': open }"
                     class="w-4 h-4 transform transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7"
                          stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div x-show="open" class="pl-8 mt-1 space-y-1 text-gray-300" x-cloak>
                <a href="{{ route('pharmacies.index') }}" class="block hover:text-white">Liste</a>
                <a href="{{ route('pharmacies.create') }}" class="block hover:text-white">Créer</a>
            </div>
        </div>

        <!-- Commandes -->
        <a href="{{ route('commandes.index') }}" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-800 rounded">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M9 2h6a2 2 0 012 2v2H7V4a2 2 0 012-2zM7 6h10v14H7z"
                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Commandes</span>
        </a>

        <!-- Documents -->
        <a href="{{ route('documents.index') }}" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-800 rounded">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M4 4h16v16H4V4z M8 4v16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>Documents</span>
        </a>

        <!-- Carte -->
        <a href="{{ route('carte.index') }}" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-800 rounded">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M9 19V6l6-2v13l-6 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Carte</span>
        </a>

        <!-- ADMIN ONLY -->
        @role('admin')
        <div class="mt-4 border-t border-gray-700 pt-4 space-y-2 text-gray-400 text-xs uppercase tracking-wide">
            Administration
        </div>

        <a href="{{ route('users.index') }}" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-800 rounded text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 4a4 4 0 110 8 4 4 0 010-8z"
                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Utilisateurs</span>
        </a>

        <a href="{{ route('rapports.index') }}" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-800 rounded text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 3v18h18M9 17v-6M15 17V9M21 17V3"
                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>Rapports</span>
        </a>

        <a href="{{ route('journal.index') }}" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-800 rounded text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 4v16m8-16H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2z"
                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>Journal</span>
        </a>
        @endrole
    </div>

    <!-- Déconnexion -->
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
