<x-layouts.admin headerTitle="Dashboard Admin">
    <div class="space-y-6">

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

        <!-- Component: Metric Cards -->
        <x-dashboard.metric-cards
            :monthlyRevenue="$monthlyRevenue"
            :monthlyBookingsCount="$monthlyBookingsCount"
            :pendingTransfersCount="$pendingTransfersCount"
        />

        <!-- Component: Charts Container -->
        <x-dashboard.charts-container
            :chartDates="$chartDates"
            :chartRevenues="$chartRevenues"
            :chartHours="$chartHours"
            :chartHourCounts="$chartHourCounts"
        />

    </div>
</x-layouts.admin>
