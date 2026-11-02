<x-layouts.app title="Booking Berhasil - FutsalHub">
    <div class="max-w-md mx-auto bg-surface-card border border-border-default rounded-2xl p-6 sm:p-8 shadow-xs text-center space-y-6">
        <!-- Success Icon -->
        <div class="w-16 h-16 bg-green-100 text-action-primary rounded-full flex items-center justify-center mx-auto">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>

        <div class="space-y-1">
            <h2 class="text-xl font-bold text-text-default">Booking Berhasil Dibuat!</h2>
            <p class="text-xs text-text-muted">Tunjukkan Kode Booking ini kepada Kasir GOR saat verifikasi kedatangan.</p>
        </div>

        <!-- Kode Booking Display Box -->
        <div class="bg-surface-page p-5 rounded-2xl border border-border-default space-y-2">
            <span class="text-[10px] uppercase font-bold tracking-wider text-text-muted">Kode Booking Anda</span>
            <p class="text-3xl font-mono font-black text-action-primary tracking-widest">{{ $booking->booking_code }}</p>
            <button type="button" onclick="navigator.clipboard.writeText('{{ $booking->booking_code }}'); alert('Kode booking berhasil disalin!');" class="inline-flex items-center gap-1 text-xs text-action-primary font-bold hover:underline pt-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Salin Kode
            </button>
        </div>

        <!-- Detail Ringkas Transaksi -->
        <div class="text-left text-xs space-y-2 border-t border-border-default pt-4 text-text-muted">
            <div class="flex justify-between"><span class="text-text-muted">Nama:</span> <span class="font-bold text-text-default">{{ $booking->customer_name }}</span></div>
            <div class="flex justify-between"><span class="text-text-muted">Lapangan:</span> <span class="font-bold text-text-default">{{ $booking->field->gor->name }} - {{ $booking->field->name }}</span></div>
            <div class="flex justify-between"><span class="text-text-muted">Tanggal:</span> <span class="font-bold text-text-default">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span></div>
            <div class="flex justify-between"><span class="text-text-muted">Total Bayar:</span> <span class="font-bold text-action-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span class="text-text-muted">Metode Pembayaran:</span> <span class="font-bold uppercase text-text-default">{{ $booking->payment_method }}</span></div>
        </div>

        <!-- Action Links -->
        <div class="space-y-2 pt-2">
            @if($booking->payment_method === 'transfer')
                <a href="{{ route('booking.check', ['code' => $booking->booking_code]) }}" class="block w-full py-3 bg-action-primary hover:bg-action-primary-hover text-white font-bold text-xs rounded-xl transition-colors shadow-xs">
                    Upload Bukti Transfer
                </a>
            @endif
            <a href="{{ route('booking.index') }}" class="block w-full py-3 bg-surface-page text-text-default font-bold text-xs rounded-xl hover:bg-border-default/50 transition-colors border border-border-default">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</x-layouts.app>
