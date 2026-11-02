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

    <!-- Header / Navbar Publik -->
    <header class="bg-surface-card border-b border-border-default sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('booking.index') }}" class="text-2xl font-black text-action-primary tracking-tight">
                Futsal<span class="text-text-default">Hub</span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('booking.check') }}" class="text-sm font-semibold text-text-muted hover:text-action-primary transition-colors">
                    Cek Booking / Upload Proof
                </a>
                <a href="{{ route('login') }}" class="px-3.5 py-1.5 text-xs font-bold rounded-lg border border-border-default hover:bg-surface-page transition-colors">
                    Login Staff
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8">
        @if(session('toast_success'))
            <x-toast type="success" :message="session('toast_success')" />
        @endif
        @if(session('toast_error'))
            <x-toast type="danger" :message="session('toast_error')" />
        @endif

        {{ $slot }}
    </main>

    <!-- Simple Footer -->
    <footer class="bg-surface-card border-t border-border-default py-6 text-center text-xs text-text-muted">
        &copy; {{ date('Y') }} FutsalHub. Platform Booking Lapangan Futsal Realtime.
    </footer>

</body>
</html>
