<x-layouts.admin headerTitle="Dashboard Analytics">
    <div class="space-y-6">

        <!-- Header Salam & Informasi Role -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-text-default">
                    Selamat datang, {{ auth()->user()->name }}! 👋
                </h2>
                <p class="text-xs text-text-muted">
                    Ringkasan statistik transaksi dan tren penyewaan lapangan futsal real-time.
                </p>
            </div>

            @if(auth()->user()->gor)
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-surface-card border border-border-default rounded-xl text-xs font-bold text-action-primary shadow-xs">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>{{ auth()->user()->gor->name }}</span>
                </div>
            @endif
        </div>

         <!-- Quick Check-in Kasir -->
        <div class="bg-surface-card p-4 rounded-xl border border-border-default shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-text-default uppercase tracking-wider">Quick Check-In Kasir</h3>
                <p class="text-xs text-text-muted">Masukkan Kode Booking pelanggan untuk verifikasi kehadiran & pelunasan di tempat.</p>
            </div>
            <form method="POST" action="{{ route('admin.bookings.checkin') }}" class="flex w-full md:w-auto gap-2">
                @csrf
                <input type="text" name="booking_code" placeholder="MASUKKAN KODE BOOKING (CTH: FTS-8X9K2)..." required class="px-3.5 py-2 text-xs rounded-lg border border-border-default uppercase font-mono font-bold outline-none focus:ring-2 focus:ring-action-primary w-full md:w-80">
                <button type="submit" class="px-4 py-2 bg-action-primary hover:bg-action-primary-hover text-white text-xs font-bold rounded-lg transition-colors whitespace-nowrap">
                    Verifikasi & Check-In
                </button>
            </form>
        </div>

        <!-- 1. KARTU METRIKS (Sesuai nama file metric-cards.blade.php) -->
        <x-dashboard.metric-cards
            :monthlyRevenue="$monthlyRevenue"
            :monthlyBookingsCount="$monthlyBookingsCount"
            :pendingTransfersCount="$pendingTransfersCount"
        />

        <!-- 2. GRAFIK ANALYTICS (Sesuai nama file charts-container.blade.php) -->
        <x-dashboard.charts-container
            :chartDates="$chartDates"
            :chartRevenues="$chartRevenues"
            :chartHours="$chartHours"
            :chartHourCounts="$chartHourCounts"
        />

    </div>
</x-layouts.admin>
