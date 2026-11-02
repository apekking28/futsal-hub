<x-layouts.admin headerTitle="Dashboard Admin">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-surface-card p-5 rounded-xl border border-border-default shadow-sm space-y-1">
                <p class="text-[11px] font-bold text-text-muted uppercase tracking-wider">PENDAPATAN BULAN INI</p>
                <p class="text-2xl font-black text-action-primary">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
            </div>

            <div class="bg-surface-card p-5 rounded-xl border border-border-default shadow-sm space-y-1">
                <p class="text-[11px] font-bold text-text-muted uppercase tracking-wider">TOTAL BOOKING BULAN INI</p>
                <p class="text-2xl font-black text-text-default">{{ $monthlyBookingsCount }} <span class="text-sm font-bold text-text-muted">Transaksi</span></p>
            </div>

            <div class="bg-surface-card p-5 rounded-xl border border-border-default shadow-sm space-y-1">
                <p class="text-[11px] font-bold text-text-muted uppercase tracking-wider">MENUNGGU VERIFIKASI TRANSFER</p>
                <p class="text-2xl font-black text-[#B45309]">{{ $pendingTransfersCount }} <span class="text-sm font-bold text-text-muted">Transfer</span></p>
            </div>
        </div>

        <!-- Charts Container -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Chart 1: Tren Pendapatan -->
            <div class="bg-surface-card p-5 rounded-xl border border-border-default shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-text-default">Tren Pendapatan (30 Hari Terakhir)</h3>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Jam Sibuk -->
            <div class="bg-surface-card p-5 rounded-xl border border-border-default shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-text-default">Jam Sibuk Booking (Peak Hours)</h3>
                <div class="h-64">
                    <canvas id="peakHoursChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- Script Chart.js Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart 1: Revenue Trend Line Chart
            const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctxRevenue, {
                type: 'line',
                data: {
                    labels: @json($chartDates),
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: @json($chartRevenues),
                        borderColor: '#16A34A',
                        backgroundColor: 'rgba(22, 163, 74, 0.1)',
                        fill: true,
                        tension: 0.3,
                        borderWidth: 2,
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                },
                                font: { size: 10 }
                            }
                        },
                        x: { ticks: { font: { size: 10 } } }
                    }
                }
            });

            // Chart 2: Peak Hours Bar Chart
            const ctxPeak = document.getElementById('peakHoursChart').getContext('2d');
            new Chart(ctxPeak, {
                type: 'bar',
                data: {
                    labels: @json($chartHours),
                    datasets: [{
                        label: 'Jumlah Booking',
                        data: @json($chartHourCounts),
                        backgroundColor: '#2563EB',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } } },
                        x: { ticks: { font: { size: 10 } } }
                    }
                }
            });
        });
    </script>
</x-layouts.admin>
