@props([
    'headerTitle' => 'Dashboard Admin',
])

<header class="h-16 bg-surface-card border-b border-border-default flex items-center justify-between px-4 sm:px-6 shrink-0 sticky top-0 z-30 w-full">
    <!-- Left Section: Mobile Hamburger + Page Title -->
    <div class="flex items-center gap-3 min-w-0">
        <!-- Hamburger Toggle (Mobile Only) -->
        <button @click="sidebarOpen = !sidebarOpen"
                type="button"
                aria-label="Toggle Navigation Sidebar"
                class="p-2 text-text-default md:hidden rounded-lg hover:bg-surface-page border border-border-default shrink-0 focus:outline-none focus:ring-2 focus:ring-action-primary">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <h1 class="text-base sm:text-lg font-bold text-text-default truncate">{{ $headerTitle }}</h1>
    </div>

    <!-- Right Section: User Role Indicator Badge -->
    <div class="flex items-center gap-3 shrink-0">
        <span class="text-xs font-semibold text-text-muted bg-surface-page px-2.5 py-1 rounded-lg border border-border-default">
            @if (auth()->user()?->hasRole('super-admin'))
                Super Admin
            @elseif(auth()->user()?->hasRole('admin-gor'))
                Admin GOR
            @else
                Kasir
            @endif
        </span>
    </div>
</header>
