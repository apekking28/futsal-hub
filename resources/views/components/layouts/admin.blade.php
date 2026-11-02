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
<body class="bg-surface-page text-text-default font-sans antialiased min-h-screen flex">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-surface-card border-r border-border-default flex flex-col justify-between hidden md:flex shrink-0 min-h-screen">
        <div class="p-6 space-y-8">
            <!-- Brand Logo -->
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="text-2xl font-black text-action-primary tracking-tight flex items-center gap-2">
                    Futsal<span class="text-text-default">Hub</span>
                    <span class="text-[10px] font-bold uppercase bg-surface-page border border-border-default px-1.5 py-0.5 rounded text-text-muted">Admin</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1.5">
                <!-- Dashboard -->
                @hasanyrole('super-admin|admin-gor')
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-action-primary text-white shadow-sm' : 'text-text-muted hover:bg-surface-page hover:text-text-default' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                @endhasanyrole

                <!-- Kelola Booking (Semua Staff: Super Admin, Admin GOR, Kasir) -->
                <a href="{{ route('admin.bookings.index') }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.bookings.*') ? 'bg-action-primary text-white shadow-sm' : 'text-text-muted hover:bg-surface-page hover:text-text-default' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Kelola Booking
                </a>

                <!-- Kelola Lapangan (Super Admin & Admin GOR) -->
                @hasanyrole('super-admin|admin-gor')
                <a href="{{ route('admin.fields.index') }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.fields.*') ? 'bg-action-primary text-white shadow-sm' : 'text-text-muted hover:bg-surface-page hover:text-text-default' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Kelola Lapangan
                </a>
                @endhasanyrole

                <!-- Kelola GOR (Khusus Super Admin) -->
                @role('super-admin')
                <a href="{{ route('admin.gors.index') }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.gors.*') ? 'bg-action-primary text-white shadow-sm' : 'text-text-muted hover:bg-surface-page hover:text-text-default' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Kelola GOR
                </a>
                @endrole
            </nav>
        </div>

        <!-- User Info & Logout Button -->
        <div class="p-6 border-t border-border-default space-y-4">
            <div class="space-y-0.5">
                <p class="text-xs font-bold text-text-default truncate">{{ auth()->user()->name ?? 'User Staff' }}</p>
                <p class="text-[11px] text-text-muted truncate">{{ auth()->user()->email ?? '' }}</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left text-xs font-bold text-status-danger-text hover:underline transition-all">
                    Keluar Aplikasi
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Header Topbar -->
        <header class="h-16 bg-surface-card border-b border-border-default flex items-center justify-between px-6 shrink-0">
            <h1 class="text-lg font-bold text-text-default">{{ $headerTitle ?? 'Dashboard Admin' }}</h1>

            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-text-muted">
                    @if(auth()->user()?->hasRole('super-admin'))
                        Super Admin Mode
                    @elseif(auth()->user()?->hasRole('admin-gor'))
                        Admin GOR Mode
                    @else
                        Kasir Mode
                    @endif
                </span>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="p-6 flex-1 overflow-y-auto">
            <!-- Toast Flash Notification -->
            @if(session('toast_success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-4 p-4 bg-[#DCFCE7] border border-[#166534]/20 text-[#166534] text-xs font-bold rounded-xl flex items-center justify-between shadow-sm">
                    <span>{{ session('toast_success') }}</span>
                    <button @click="show = false" class="text-base font-bold">&times;</button>
                </div>
            @endif

            @if(session('toast_error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-4 p-4 bg-[#FEE2E2] border border-[#991B1B]/20 text-[#991B1B] text-xs font-bold rounded-xl flex items-center justify-between shadow-sm">
                    <span>{{ session('toast_error') }}</span>
                    <button @click="show = false" class="text-base font-bold">&times;</button>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

</body>
</html>
