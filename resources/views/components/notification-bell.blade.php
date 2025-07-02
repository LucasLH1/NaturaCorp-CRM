<div x-data="initNotifications({{$unreadCount ?? 0}})" class="relative">
    <button @click="toggle" class="relative focus:outline-none">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5.365V3m0 2.365a5.338 5.338 0 0 1 5.133 5.368v1.8c0 2.386 1.867 2.982 1.867 4.175 0 .593 0 1.292-.538 1.292H5.538C5 18 5 17.301 5 16.708c0-1.193 1.867-1.789 1.867-4.175v-1.8A5.338 5.338 0 0 1 12 5.365ZM8.733 18c.094.852.306 1.54.944 2.112a3.48 3.48 0 0 0 4.646 0c.638-.572 1.236-1.26 1.33-2.112h-6.92Z"/>
        </svg>
        <template x-if="unreadCount > 0">
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full"
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
