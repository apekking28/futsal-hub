<!DOCTYPE html>
<html lang="id" class="h-full bg-surface-page">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                class="w-full py-3 bg-action-primary hover:bg-action-primary-hover text-white font-bold text-sm rounded-xl transition-all shadow-sm">
                Masuk ke System
            </button>
        </form>

        <div class="pt-4 border-t border-border-default text-xs text-text-muted space-y-1">
            <p class="font-bold text-text-default">Akun Testing (dari Seeder):</p>
            <p>• Super Admin: <code class="text-action-primary font-mono font-bold">admin@futsalhub.id</code> / <code
                    class="text-action-primary font-mono font-bold">password123</code></p>
            <p>• Admin GOR: <code class="text-action-primary font-mono font-bold">admin.cfc@futsalhub.id</code> / <code
                    class="text-action-primary font-mono font-bold">password123</code></p>
            <p>• Kasir: <code class="text-action-primary font-mono font-bold">kasir.cfc@futsalhub.id</code> / <code
                    class="text-action-primary font-mono font-bold">password123</code></p>
        </div>
    </div>

</body>

</html>
