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
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-surface-page text-text-default font-sans antialiased min-h-screen flex overflow-x-hidden"
    x-data="{ sidebarOpen: false }">

    <!-- Mobile Overlay Backdrop -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 md:hidden"
        x-cloak></div>

    <!-- Sidebar Component Container -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        class="fixed md:sticky top-0 inset-y-0 left-0 z-50 h-screen w-64 bg-surface-card border-r border-border-default flex flex-col justify-between transition-transform duration-200 ease-in-out shrink-0 p-5 overflow-hidden">

        <!-- Brand Logo Header di Sidebar Admin -->
        <div class="flex flex-col items-center text-center space-y-2 pb-4 mb-3 border-b border-border-default shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="group">
                <x-logo variant="stacked" iconSize="h-16 sm:h-20" textSize="h-8 sm:h-10" />
            </a>
            <span
                class="text-[10px] font-bold uppercase bg-surface-page border border-border-default px-2.5 py-0.5 rounded-full text-text-muted">Admin
                Portal</span>
        </div>

        <!-- Sidebar Navigation & Profile Component -->
        <x-sidebar />
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
        <!-- Component Topbar Modular -->
        <x-layouts.topbars.admin-header :headerTitle="$headerTitle ?? 'Dashboard Admin'" />

        <!-- Main Body Content Container -->
        <main class="p-4 sm:p-6 flex-1 overflow-y-auto overflow-x-hidden">
            @if (session('toast_success'))
                <x-toast type="success" :message="session('toast_success')" />
            @endif

            @if (session('toast_error'))
                <x-toast type="danger" :message="session('toast_error')" />
            @endif

            {{ $slot }}
        </main>
    </div>

</body>

</html>
