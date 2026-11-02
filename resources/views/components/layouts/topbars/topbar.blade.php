<header class="bg-surface-card border-b border-border-default sticky top-0 z-40 shadow-sm" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-6">

        <!-- Sisi Kiri: Logo + Menu Navigasi Desktop -->
        <div class="flex items-center gap-8">
            <!-- Logo Horizontal -->
            <a href="{{ route('booking.index') }}" class="group flex items-center shrink-0">
                <x-logo variant="horizontal" iconSize="h-8 sm:h-9" textSize="h-6 sm:h-7" />
            </a>

            <!-- Navigation Links Desktop (Langsung di Samping Logo) -->
            <nav class="hidden md:flex items-center gap-5">
                <a href="{{ route('booking.index') }}"
                   class="text-sm font-semibold transition-colors {{ request()->routeIs('booking.index') ? 'text-action-primary font-bold' : 'text-text-muted hover:text-action-primary' }}">
                    Beranda
                </a>
                @if(Route::has('fields.index'))
                    <a href="{{ route('fields.index') }}"
                       class="text-sm font-semibold transition-colors {{ request()->routeIs('fields.*') ? 'text-action-primary font-bold' : 'text-text-muted hover:text-action-primary' }}">
                        Lapangan
                    </a>
                @endif
                <a href="{{ route('booking.check') }}"
                   class="text-sm font-semibold transition-colors {{ request()->routeIs('booking.check') ? 'text-action-primary font-bold' : 'text-text-muted hover:text-action-primary' }}">
                    Cek Booking
                </a>
            </nav>
        </div>

        <!-- Sisi Kanan: Action Buttons Desktop -->
        <div class="hidden md:flex items-center gap-2 sm:gap-3">
            <a href="{{ route('booking.index') }}"
               class="px-4 py-2 text-xs sm:text-sm font-bold text-white bg-action-primary hover:bg-action-primary-hover rounded-xl shadow-xs transition-colors">
                Booking
            </a>
            <a href="{{ route('login') }}"
               class="px-4 py-2 text-xs sm:text-sm font-semibold text-text-muted hover:text-text-default bg-surface-page hover:bg-border-default/40 rounded-xl border border-border-default transition-colors">
                Login Staff
            </a>
        </div>

        <!-- Mobile Hamburger Button -->
        <div class="flex items-center md:hidden">
            <button type="button"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="p-2 rounded-xl text-text-muted hover:text-text-default hover:bg-surface-page border border-border-default transition-colors focus:outline-none"
                    aria-label="Toggle Navigation">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div x-show="mobileMenuOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         @click.away="mobileMenuOpen = false"
         class="md:hidden bg-surface-card border-b border-border-default px-4 pt-3 pb-5 space-y-3 shadow-lg">

        <nav class="flex flex-col space-y-1">
            <a href="{{ route('booking.index') }}"
               class="px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('booking.index') ? 'bg-surface-page text-action-primary font-bold' : 'text-text-muted hover:bg-surface-page hover:text-text-default' }}">
                Beranda
            </a>
            @if(Route::has('fields.index'))
                <a href="{{ route('fields.index') }}"
                   class="px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('fields.*') ? 'bg-surface-page text-action-primary font-bold' : 'text-text-muted hover:bg-surface-page hover:text-text-default' }}">
                    Lapangan
                </a>
            @endif
            <a href="{{ route('booking.check') }}"
               class="px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('booking.check') ? 'bg-surface-page text-action-primary font-bold' : 'text-text-muted hover:bg-surface-page hover:text-text-default' }}">
                Cek Booking
            </a>
        </nav>

        <div class="pt-2 border-t border-border-default flex flex-col gap-2">
            <a href="{{ route('booking.index') }}"
               class="w-full text-center py-2.5 text-xs font-bold text-white bg-action-primary hover:bg-action-primary-hover rounded-xl shadow-xs transition-colors">
                Booking
            </a>
            <a href="{{ route('login') }}"
               class="w-full text-center py-2.5 text-xs font-semibold text-text-muted hover:text-text-default bg-surface-page hover:bg-border-default/40 rounded-xl border border-border-default transition-colors">
                Login Staff
            </a>
        </div>
    </div>
</header>
