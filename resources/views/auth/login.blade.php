<!DOCTYPE html>
<html lang="id" class="h-full bg-surface-page">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Staff - FutsalHub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-surface-card border border-border-default rounded-xl p-6 shadow-sm">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-black text-action-primary tracking-tight">Futsal<span class="text-text-default">Hub</span></h1>
            <p class="text-xs text-text-muted mt-1">Portal Super Admin, Admin GOR & Kasir</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-status-danger-bg text-status-danger-text text-sm rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold uppercase text-text-muted mb-1">Email Staff</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-2.5 rounded-lg border border-border-default focus:ring-2 focus:ring-action-primary outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase text-text-muted mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2.5 rounded-lg border border-border-default focus:ring-2 focus:ring-action-primary outline-none">
            </div>

            <button type="submit" class="w-full py-3 bg-action-primary hover:bg-action-primary-hover text-white font-bold rounded-lg transition-colors">
                Masuk ke System
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-border-default text-xs text-text-muted space-y-1">
            <p class="font-bold">Akun Testing (dari Seeder):</p>
            <p>• Super Admin: <code class="text-action-primary">admin@futsalhub.id</code> / <code class="text-action-primary">password123</code></p>
            <p>• Admin GOR: <code class="text-action-primary">admin.cfc@futsalhub.id</code> / <code class="text-action-primary">password123</code></p>
            <p>• Kasir: <code class="text-action-primary">kasir.cfc@futsalhub.id</code> / <code class="text-action-primary">password123</code></p>
        </div>
    </div>

</body>
</html>
