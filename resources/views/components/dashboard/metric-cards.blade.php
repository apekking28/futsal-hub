@props([
    'monthlyRevenue' => 0,
    'monthlyBookingsCount' => 0,
    'pendingTransfersCount' => 0,
])

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- Card 1: Pendapatan -->
    <div class="bg-surface-card p-5 rounded-xl border border-border-default shadow-sm space-y-1">
        <p class="text-[11px] font-bold text-text-muted uppercase tracking-wider">PENDAPATAN BULAN INI</p>
        <p class="text-2xl font-black text-action-primary">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
    </div>

    <!-- Card 2: Total Booking -->
    <div class="bg-surface-card p-5 rounded-xl border border-border-default shadow-sm space-y-1">
        <p class="text-[11px] font-bold text-text-muted uppercase tracking-wider">TOTAL BOOKING BULAN INI</p>
        <p class="text-2xl font-black text-text-default">{{ $monthlyBookingsCount }} <span class="text-sm font-bold text-text-muted">Transaksi</span></p>
    </div>

    <!-- Card 3: Menunggu Verifikasi -->
    <div class="bg-surface-card p-5 rounded-xl border border-border-default shadow-sm space-y-1">
        <p class="text-[11px] font-bold text-text-muted uppercase tracking-wider">MENUNGGU VERIFIKASI TRANSFER</p>
        <p class="text-2xl font-black text-[#B45309]">{{ $pendingTransfersCount }} <span class="text-sm font-bold text-text-muted">Transfer</span></p>
    </div>
</div>
