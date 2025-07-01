<div class="h-screen w-64 bg-gray-900 text-white flex flex-col shadow-lg fixed">
    <!-- Logo -->
    <div class="px-6 py-4 font-bold text-xl tracking-wide border-b border-gray-800">
        NaturaCorp
    </div>

    <!-- Navigation -->
    <div class="flex-1 overflow-y-auto px-2 py-4 text-sm space-y-2">

        <!-- Tableau de bord -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center space-x-2 px-3 py-2 rounded {{ request()->routeIs('dashboard') ? 'bg-gray-800' : 'hover:bg-gray-800' }}">
            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5"/>
            </svg>
            <span>Tableau de bord</span>
        </a>

        <!-- Pharmacies -->
        @role('commercial|admin')
            <a href="{{ route('pharmacies.index') }}"
               class="flex items-center space-x-2 px-3 py-2 rounded {{ request()->routeIs('pharmacies.*') ? 'bg-gray-800' : 'hover:bg-gray-800' }}">
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.6427 21h14M16.2928 9c.8398.95515 1.3499 2.213 1.3499 3.5912 0 2.9872-2.3962 5.4088-5.3521 5.4088-1.9895 0-3.72545-1.097-4.6479-2.7251m-2-.2749h6m.4369-4.4369L10.6427 12m5.8092-5.76698 2.1554-2.15534M17.5296 3l2.1553 2.15534M10.6427 18v3m4-3v3m.7315-15.84464-4.3107 4.31068 2.1554 2.15536 4.3107-4.3107-2.1554-2.15534Z"/>
                </svg>
                <span>Pharmacies</span>
            </a>
        @endrole

        <!-- Commandes -->
        @role('commercial|admin')
            <a href="{{ route('commandes.index') }}"
               class="flex items-center space-x-2 px-3 py-2 rounded {{ request()->routeIs('commandes.*') ? 'bg-gray-800' : 'hover:bg-gray-800' }}">
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6"/>
                </svg>
                <span>Commandes</span>
            </a>
        @endrole

        @role('logistique|admin')
        <a href="{{ route('produits.index') }}"
           class="flex items-center space-x-2 px-3 py-2 rounded {{ request()->routeIs('produits.*') ? 'bg-gray-800' : 'hover:bg-gray-800' }}">
            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M10 12v1h4v-1m4 7H6a1 1 0 0 1-1-1V9h14v9a1 1 0 0 1-1 1ZM4 5h16a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
            </svg>
            <span>Produits</span>
        </a>
        @endrole

        <!-- Carte (tous utilisateurs connectés) -->
        <a href="{{ route('carte.index') }}"
           class="flex items-center space-x-2 px-3 py-2 rounded {{ request()->routeIs('carte.index') ? 'bg-gray-800' : 'hover:bg-gray-800' }}">
            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.8 13.938h-.011a7 7 0 1 0-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155Z"/>
            </svg>
            <span>Carte</span>
        </a>

        <!-- Administration -->
        @role('admin')
        <div class="mt-4 border-t border-gray-700 pt-4 space-y-2 text-gray-400 text-xs uppercase tracking-wide">
            Administration
        </div>

        <a href="{{ route('users.index') }}"
           class="flex items-center space-x-2 px-3 py-2 rounded text-sm {{ request()->routeIs('users.*') ? 'bg-gray-800' : 'hover:bg-gray-800' }}">
            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
            </svg>
            <span>Utilisateurs</span>
        </a>

        <a href="{{ route('rapports.index') }}"
           class="flex items-center space-x-2 px-3 py-2 rounded text-sm {{ request()->routeIs('rapports.*') ? 'bg-gray-800' : 'hover:bg-gray-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 3v18h18M9 17v-6M15 17V9M21 17V3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Rapports</span>
        </a>

        <a href="{{ route('admin.logs') }}"
           class="flex items-center space-x-2 px-3 py-2 rounded text-sm {{ request()->routeIs('admin.logs') ? 'bg-gray-800' : 'hover:bg-gray-800' }}">
            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7h1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h11.5M7 14h6m-6 3h6m0-10h.5m-.5 3h.5M7 7h3v3H7V7Z"/>
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
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2"/>
                </svg>
                <span>Déconnexion</span>
            </button>
        </form>
    </div>
</div>
