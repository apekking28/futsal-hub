<!DOCTYPE html>
<html lang="id" class="h-full bg-surface-page">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'FutsalHub - Booking Lapangan Online' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="min-h-full flex flex-col text-text-default font-sans antialiased">

    <!-- Header / Navbar Publik (User Anonymous) -->
    <header class="bg-surface-card border-b border-border-default sticky top-0 z-40 py-3">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <!-- Logo Horizontal Publik -->
            <a href="{{ route('booking.index') }}" class="group">
                <x-logo variant="horizontal" iconSize="h-12 sm:h-14" textSize="h-8 sm:h-9" />
            </a>

            <div class="flex items-center gap-2 sm:gap-4">
                <a href="{{ route('booking.check') }}"
                    class="text-xs sm:text-sm font-semibold text-text-muted hover:text-action-primary transition-colors">
                    Cek Booking
                </a>
                <a href="{{ route('login') }}"
                    class="px-3.5 py-2 text-xs font-bold rounded-xl border border-border-default hover:bg-surface-page transition-colors">
                    Login Staff
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8">
        @if (session('toast_success'))
            <x-toast type="success" :message="session('toast_success')" />
        @endif
        @if (session('toast_error'))
            <x-toast type="danger" :message="session('toast_error')" />
        @endif

        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-surface-card border-t border-border-default py-6 text-center text-xs text-text-muted">
        &copy; {{ date('Y') }} FutsalHub. Platform Booking Lapangan Futsal Realtime.
    </footer>

</body>

</html>
