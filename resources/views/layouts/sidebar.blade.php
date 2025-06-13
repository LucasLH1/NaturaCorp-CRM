<div class="h-screen bg-white w-64 border-r px-4 py-6 space-y-6 fixed">
    <h1 class="text-2xl font-bold text-gray-800">NaturaCorp CRM</h1>

    <nav class="space-y-2 text-sm">
        <a href="{{ route('pharmacies.index') }}" class="block text-gray-700 hover:text-blue-600">Pharmacies</a>
        <a href="{{ route('commandes.index') }}" class="block text-gray-700 hover:text-blue-600">Commandes</a>
        <a href="{{ route('documents.index') }}" class="block text-gray-700 hover:text-blue-600">Documents</a>
        <a href="{{ route('notifications.index') }}" class="block text-gray-700 hover:text-blue-600">Notifications</a>
        <a href="{{ route('rapports.index') }}" class="block text-gray-700 hover:text-blue-600">Rapports</a>
        <a href="{{ route('journal.index') }}" class="block text-gray-700 hover:text-blue-600">Journal</a>
        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button class="text-red-600 hover:text-red-800 text-sm">Déconnexion</button>
        </form>
    </nav>
</div>
