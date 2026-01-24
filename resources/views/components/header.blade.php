<header
    class="sticky top-0 z-30 h-20 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-6 md:px-10"
    x-data="{ open: false, notifOpen: false, notifications: [], count: 0 }" x-init="fetch('/notifications/get')
        .then(r => r.json())
        .then(data => {
            notifications = data.notifications;
            count = data.count;
        })
        .catch(e => console.log('No notifications'));">
    <div class="flex items-center gap-4">
        <div>
            <h1 class="text-sm font-bold text-slate-800">@yield('page-title', 'Dashboard')</h1>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">@yield('page-description')</p>
        </div>
    </div>

    <div class="flex items-center gap-4">
        {{-- Notification Bell --}}
        <div class="relative">
            <button @click="notifOpen = !notifOpen" class="relative p-2 rounded-full hover:bg-gray-100 transition-colors">
                <i class="fas fa-bell text-gray-500 text-lg"></i>
                <span x-show="count > 0" x-text="count > 9 ? '9+' : count"
                    class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                </span>
            </button>

            {{-- Dropdown --}}
            <div x-show="notifOpen" @click.away="notifOpen = false" x-transition
                class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-50">
                <div class="p-4 border-b bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900">Notifikasi</h3>
                        <span class="text-xs text-gray-500" x-text="count + ' alert'"></span>
                    </div>
                </div>

                <div class="max-h-64 overflow-y-auto">
                    <template x-if="notifications.length === 0">
                        <div class="p-6 text-center text-gray-500">
                            <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                            <p class="text-sm">Tidak ada notifikasi</p>
                        </div>
                    </template>

                    <template x-for="notif in notifications" :key="notif.message">
                        <a :href="notif.link"
                            class="flex items-start gap-3 p-3 hover:bg-gray-50 border-b border-gray-50">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                                :class="notif.color === 'red' ? 'bg-red-100' : 'bg-orange-100'">
                                <i :class="'fas ' + notif.icon + (notif.color === 'red' ? ' text-red-600' :
                                    ' text-orange-600')"
                                    class="text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900" x-text="notif.title"></p>
                                <p class="text-xs text-gray-500 truncate" x-text="notif.message"></p>
                            </div>
                        </a>
                    </template>
                </div>

                <div class="p-3 border-t bg-gray-50">
                    <a href="{{ route('notifications.index') }}"
                        class="block text-center text-sm text-amber-600 hover:text-amber-700 font-medium">
                        Lihat Semua Notifikasi
                    </a>
                </div>
            </div>
        </div>

        {{-- Profile --}}
        <a href="{{ route('profil.index') }}" class="flex items-center gap-2 py-2 px-4 rounded-full transition-colors hover:bg-amber-50">
            <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-white text-sm"></i>
            </div>
            <div class="text-center hidden md:block">
                <p class="text-sm font-bold group-hover:text-amber-600 transition-colors">{{ auth()->user()->name }}</p>
                <span class="text-xs text-gray-500">
                    @if(auth()->user()->isSuperAdmin())
                        Super Admin
                    @elseif(auth()->user()->isOwner())
                        Owner
                    @else
                        Kasir
                    @endif
                </span>
            </div>
        </a>
    </div>

</header>
