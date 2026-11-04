<!DOCTYPE html>
<html lang="id" class="h-full bg-surface-page">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon / Icon Tab Browser -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <title>{{ $title ?? 'FutsalHub - Booking Lapangan Futsal Online' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="min-h-full flex flex-col text-text-default font-sans antialiased selection:bg-action-primary selection:text-white">

    <!-- Topbar Component -->
    <x-layouts.topbars.topbar />

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        @if (session('toast_success'))
            <x-toast type="success" :message="session('toast_success')" />
        @endif
        @if (session('toast_error'))
            <x-toast type="danger" :message="session('toast_error')" />
        @endif

        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-surface-card border-t border-border-default py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-text-muted">
            <div class="flex items-center gap-2">
                <span class="font-bold text-text-default">FutsalHub</span>
                <span>&bull;</span>
                <span>Platform Booking Lapangan Futsal Cengkareng & Sekitarnya</span>
            </div>
            <p>&copy; {{ date('Y') }} FutsalHub. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>
