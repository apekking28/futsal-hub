<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $headerTitle ?? 'Dashboard Admin' }} - FutsalHub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-surface-page text-text-default font-sans antialiased min-h-screen flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Overlay Backdrop -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-black/50 md:hidden" x-cloak></div>

    <!-- Sidebar Navigation Drawer Mobile & Fixed Desktop -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        class="fixed md:sticky top-0 inset-y-0 left-0 z-50 h-screen w-64 bg-surface-card border-r border-border-default flex flex-col justify-between transition-transform duration-200 ease-in-out shrink-0 p-5 overflow-hidden">

        <!-- Brand Logo Header -->
        <div class="flex flex-col items-center text-center space-y-2 pb-4 mb-3 border-b border-border-default shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="group">
                <x-logo variant="stacked" iconSize="h-14" textSize="h-7" />
            </a>
            <span class="text-[10px] font-bold uppercase bg-surface-page border border-border-default px-2.5 py-0.5 rounded-full text-text-muted">Admin Portal</span>
        </div>

        <!-- Single Sidebar Component (Nav Links & Sticky User Profile) -->
        <x-sidebar />
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen">
        <!-- Header Topbar -->
        <header class="h-16 bg-surface-card border-b border-border-default flex items-center justify-between px-4 sm:px-6 shrink-0 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <!-- Hamburger Button (Mobile Only) -->
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-text-default md:hidden rounded-lg hover:bg-surface-page border border-border-default">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-base sm:text-lg font-bold text-text-default truncate">{{ $headerTitle ?? 'Dashboard Admin' }}</h1>
            </div>

            <div class="flex items-center gap-3">
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

        <!-- Main Body Content -->
        <main class="p-4 sm:p-6 flex-1 overflow-y-auto">
            @if (session('toast_success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    class="mb-4 p-4 bg-[#DCFCE7] border border-[#166534]/20 text-[#166534] text-xs font-bold rounded-xl flex items-center justify-between shadow-sm">
                    <span>{{ session('toast_success') }}</span>
                    <button @click="show = false" class="text-base font-bold">&times;</button>
                </div>
            @endif

            @if (session('toast_error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    class="mb-4 p-4 bg-[#FEE2E2] border border-[#991B1B]/20 text-[#991B1B] text-xs font-bold rounded-xl flex items-center justify-between shadow-sm">
                    <span>{{ session('toast_error') }}</span>
                    <button @click="show = false" class="text-base font-bold">&times;</button>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

</body>

</html>
