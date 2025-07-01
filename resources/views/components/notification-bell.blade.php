<div x-data="initNotifications({{$unreadCount ?? 0}})" class="relative">
    <button @click="toggle" class="relative focus:outline-none">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V11a6.002 6.002 0 00-4-5.659V4a2 2 0 00-4 0v1.341C7.67 6.165 6 8.388 6 12v2l-1 1v1h10z"/>
        </svg>
        <template x-if="unreadCount > 0">
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full"
                  x-text="unreadCount"></span>
        </template>
    </button>

    <div x-show="open" @click.away="open = false"
         class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
         style="display: none;">
        <div class="flex justify-between items-center p-4 border-b text-sm font-semibold text-gray-700">
            <span>Notifications</span>
            <button @click="markAllAsRead"
                    class="text-xs text-blue-600 hover:underline font-normal">
                Tout marquer comme lu
            </button>
        </div>

        <ul class="max-h-72 overflow-auto divide-y text-sm">
            <template x-for="notification in notifications" :key="notification.id">
                <li class="p-3 hover:bg-gray-50 flex justify-between items-start cursor-pointer"
                    @click="markAsRead(notification.id)">
                    <div>
                        <p class="font-medium text-gray-800" x-text="notification.titre"></p>
                        <p class="text-gray-600" x-text="notification.contenu"></p>
                    </div>
                    <template x-if="!notification.est_lu">
                        <span class="mt-1 ml-2 w-2 h-2 rounded-full bg-blue-500"></span>
                    </template>
                </li>
            </template>

            <template x-if="notifications.length === 0">
                <li class="p-3 text-gray-500">Aucune notification</li>
            </template>
        </ul>
    </div>
</div>
