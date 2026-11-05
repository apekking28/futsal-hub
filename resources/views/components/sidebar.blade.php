<div class="flex flex-col justify-between h-full flex-1 min-h-0">
    <!-- Top Part: Navigation Links (Scrollable jika layar pendek) -->
    <nav class="space-y-4 w-full overflow-y-auto pr-1 flex-1 min-h-0">

        <!-- KATEGORI 1: MENU UTAMA -->
        <div class="space-y-1">
            <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-text-muted/80 block mb-1.5">
                Menu Utama
            </span>

            <!-- Dashboard (Super Admin & Admin GOR) -->
            @hasanyrole('super-admin|admin-gor')
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-action-primary text-white shadow-xs font-bold' : 'text-text-muted hover:bg-surface-page hover:text-text-default' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
            @endhasanyrole

            <!-- Kelola Booking (Semua Role Staff) -->
            <a href="{{ route('admin.bookings.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.bookings.*') ? 'bg-action-primary text-white shadow-xs font-bold' : 'text-text-muted hover:bg-surface-page hover:text-text-default' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Kelola Booking
            </a>
        </div>

        <!-- KATEGORI 2: DATA MASTER -->
        <div class="space-y-1">
            <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-text-muted/80 block mb-1.5">
                Data Master
            </span>

            <!-- Kelola District (Super Admin Only) -->
            @role('super-admin')
                <a href="{{ route('admin.districts.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.districts.*') ? 'bg-action-primary text-white shadow-xs font-bold' : 'text-text-muted hover:bg-surface-page hover:text-text-default' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Kelola District
                </a>

                <!-- Kelola GOR (Super Admin Only) -->
                <a href="{{ route('admin.gors.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.gors.*') ? 'bg-action-primary text-white shadow-xs font-bold' : 'text-text-muted hover:bg-surface-page hover:text-text-default' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Kelola GOR
                </a>
            @endrole

            <!-- Kelola Lapangan (Super Admin & Admin GOR) -->
            @hasanyrole('super-admin|admin-gor')
                <a href="{{ route('admin.fields.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.fields.*') ? 'bg-action-primary text-white shadow-xs font-bold' : 'text-text-muted hover:bg-surface-page hover:text-text-default' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Kelola Lapangan
                </a>
            @endhasanyrole
        </div>

        <!-- KATEGORI 3: SISTEM & KEAMANAN (Super Admin Only) -->
        @role('super-admin')
            <div class="space-y-1">
                <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-text-muted/80 block mb-1.5">
                    Sistem & Keamanan
                </span>

                <!-- Manajemen User -->
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-action-primary text-white shadow-xs font-bold' : 'text-text-muted hover:bg-surface-page hover:text-text-default' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Manajemen User
                </a>

                <!-- Manajemen Role -->
                <a href="{{ route('admin.roles.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.roles.*') ? 'bg-action-primary text-white shadow-xs font-bold' : 'text-text-muted hover:bg-surface-page hover:text-text-default' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Manajemen Role
                </a>

                <!-- Manajemen Permission -->
                <a href="{{ route('admin.permissions.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.permissions.*') ? 'bg-action-primary text-white shadow-xs font-bold' : 'text-text-muted hover:bg-surface-page hover:text-text-default' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    Manajemen Permission
                </a>
            </div>
        @endrole

    </nav>

    <!-- Bottom Part: Sticky User Profile & Logout (Selalu Terlihat di Bawah) -->
    <div class="pt-3 mt-auto border-t border-border-default space-y-2 shrink-0">
        <div class="flex items-center gap-2.5 px-1">
            <div class="w-8 h-8 rounded-xl bg-action-primary/10 border border-border-default flex items-center justify-center font-bold text-action-primary text-xs shrink-0 uppercase">
                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-text-default truncate leading-tight">{{ auth()->user()->name ?? 'User Staff' }}</p>
                <p class="text-[10px] text-text-muted truncate leading-tight">{{ auth()->user()->email ?? '' }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full py-2 px-3 bg-surface-page hover:bg-status-danger-bg text-status-danger-text text-xs font-bold rounded-xl border border-border-default hover:border-status-danger-text/20 transition-all flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar Aplikasi
            </button>
        </form>
    </div>
</div>
