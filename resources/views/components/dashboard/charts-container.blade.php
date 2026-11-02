@props([
    'chartDates' => [],
    'chartRevenues' => [],
    'chartHours' => [],
    'chartHourCounts' => [],
])

<div class="space-y-6">
    <!-- Chart Grid Container -->
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

    <!-- Chart.js Script Initialization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart 1: Revenue Line Chart
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
</div>
