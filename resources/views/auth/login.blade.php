<!DOCTYPE html>
<html lang="id" class="h-full bg-surface-page">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon / Icon Tab Browser -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <title>Login Staff - FutsalHub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-surface-card border border-border-default rounded-2xl p-8 shadow-sm space-y-6">
        <!-- Logo Display di Login Staff -->
        <div class="text-center flex flex-col items-center justify-center space-y-3">
            <x-logo variant="stacked" iconSize="h-32 sm:h-36" textSize="h-12 sm:h-14" />
            <p class="text-xs font-medium text-text-muted pt-1">Portal Super Admin, Admin GOR & Kasir</p>
        </div>

        @if ($errors->any())
            <div class="p-3.5 bg-status-danger-bg text-status-danger-text text-xs font-bold rounded-xl">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase text-text-muted mb-1">Email Staff</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-2.5 rounded-xl border border-border-default focus:ring-2 focus:ring-action-primary outline-none text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-text-muted mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2.5 rounded-xl border border-border-default focus:ring-2 focus:ring-action-primary outline-none text-sm">
            </div>

            <button type="submit"
                class="w-full py-3 bg-action-primary hover:bg-action-primary-hover text-white font-bold text-sm rounded-xl transition-all shadow-sm cursor-pointer">
                Masuk ke System
            </button>
        </form>

        <!-- Tombol Kembali ke Halaman Utama dengan Style Button & Hover Keren -->
        <div class="pt-2 border-t border-border-default">
            <a href="{{ url('/') }}"
                class="w-full py-2.5 px-4 bg-surface-page hover:bg-border-default border border-border-default text-text-default hover:text-action-primary font-bold text-xs rounded-xl transition-all flex items-center justify-center gap-2 shadow-xs cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Halaman Utama
            </a>
        </div>
    </div>

</body>

</html>
