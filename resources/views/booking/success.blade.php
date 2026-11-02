<x-layouts.app title="Booking Berhasil - FutsalHub">
    <div class="max-w-md mx-auto bg-surface-card border border-border-default rounded-2xl p-6 shadow-sm text-center space-y-6">
        <div class="w-16 h-16 bg-[#DCFCE7] text-[#166534] rounded-full flex items-center justify-center mx-auto">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>

        <div>
            <h2 class="text-xl font-bold text-text-default">Booking Berhasil Dibuat!</h2>
            <p class="text-xs text-text-muted mt-1">Simpan Kode Booking berikut untuk ditunjukkan kepada Kasir GOR atau cek status bayar.</p>
        </div>

        <div class="bg-surface-page p-4 rounded-xl border border-border-default space-y-2">
            <p class="text-xs uppercase font-semibold text-text-muted">Kode Booking Anda</p>
            <p class="text-3xl font-mono font-black text-action-primary tracking-wider">{{ $booking->booking_code }}</p>
            <button type="button" onclick="navigator.clipboard.writeText('{{ $booking->booking_code }}'); alert('Kode booking berhasil disalin!');" class="text-xs text-action-primary font-bold hover:underline">
                Salin Kode
            </button>
        </div>

        <div class="text-left text-xs space-y-2 border-t border-border-default pt-4">
            <div class="flex justify-between"><span class="text-text-muted">Nama:</span> <span class="font-bold">{{ $booking->customer_name }}</span></div>
            <div class="flex justify-between"><span class="text-text-muted">Lapangan:</span> <span class="font-bold">{{ $booking->field->gor->name }} - {{ $booking->field->name }}</span></div>
            <div class="flex justify-between"><span class="text-text-muted">Tanggal:</span> <span class="font-bold">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span></div>
            <div class="flex justify-between"><span class="text-text-muted">Total Bayar:</span> <span class="font-bold text-action-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span class="text-text-muted">Metode:</span> <span class="font-bold uppercase">{{ $booking->payment_method }}</span></div>
        </div>

        <div class="space-y-2 pt-2">
            @if($booking->payment_method === 'transfer')
                <a href="{{ route('booking.check', ['code' => $booking->booking_code]) }}" class="block w-full py-3 bg-action-primary hover:bg-action-primary-hover text-white font-bold text-xs rounded-xl transition-colors shadow-sm">
                    Upload Bukti Transfer
                </a>
            @endif
            <a href="{{ route('booking.index') }}" class="block w-full py-3 bg-surface-page text-text-default font-bold text-xs rounded-xl hover:bg-border-default transition-colors">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</x-layouts.app>
