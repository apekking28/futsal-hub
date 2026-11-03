<x-layouts.app title="Booking Berhasil - FutsalHub">
    <div class="max-w-xl mx-auto space-y-6">
        <div class="bg-surface-card border border-border-default rounded-3xl p-6 sm:p-8 shadow-xs text-center space-y-6">

            <!-- Success Icon -->
            <div class="w-16 h-16 bg-[#DCFCE7] text-[#166534] rounded-2xl flex items-center justify-center mx-auto shadow-xs">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>

            <div class="space-y-1">
                <h2 class="text-xl font-black text-text-default tracking-tight">Booking Berhasil Dibuat!</h2>
                <p class="text-xs text-text-muted">Simpan Kode Booking ini untuk verifikasi atau unggah bukti pembayaran.</p>
            </div>

            <!-- Display Kode Booking -->
            <div class="bg-surface-page p-5 rounded-2xl border border-border-default space-y-2">
                <span class="text-[10px] uppercase font-bold tracking-wider text-text-muted">Kode Booking Anda</span>
                <p class="text-3xl font-mono font-black text-action-primary tracking-widest">{{ $booking->booking_code }}</p>
                <button type="button" onclick="navigator.clipboard.writeText('{{ $booking->booking_code }}'); alert('Kode booking berhasil disalin!');" class="inline-flex items-center gap-1.5 text-xs text-action-primary font-bold hover:underline pt-1 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Salin Kode Booking
                </button>
            </div>

            <!-- INFORMASI PEMBAYARAN REKENING GOR (Hanya Tampil Jika Transfer) -->
            @if($booking->payment_method === 'transfer')
                <div class="bg-action-primary/5 border border-action-primary/20 p-4 rounded-2xl text-left space-y-3">
                    <div class="flex items-center justify-between border-b border-action-primary/10 pb-2">
                        <span class="text-xs font-bold text-action-primary uppercase tracking-wider">Transfer Ke Rekening GOR</span>
                        <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-action-primary text-white">Transfer Bank</span>
                    </div>

                    @if($booking->field->gor->bank_name &&$booking->field->gor->bank_account_number)
                        <div class="space-y-1.5 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-text-muted font-medium">Bank Tujuan:</span>
                                <strong class="text-text-default font-bold uppercase">{{ $booking->field->gor->bank_name }}</strong>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-text-muted font-medium">No. Rekening:</span>
                                <span class="font-mono font-bold text-base text-action-primary select-all">{{ $booking->field->gor->bank_account_number }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-text-muted font-medium">Atas Nama:</span>
                                <strong class="text-text-default font-semibold">{{ $booking->field->gor->bank_account_holder ?? '-' }}</strong>
                            </div>
                        </div>
                    @else
                        <p class="text-xs text-text-muted italic">Silakan hubungi kasir/pihak GOR untuk rincian transfer lebih lanjut.</p>
                    @endif
                </div>
            @endif

            <!-- DETAIL LOKASI GOR & ALAMAT GOOGLE MAPS -->
            <div class="bg-surface-page p-4 rounded-2xl border border-border-default text-left space-y-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-text-muted block">Lokasi & Alamat GOR</span>
                <p class="text-xs font-bold text-text-default">{{ $booking->field->gor->name }}</p>
                <p class="text-xs text-text-muted leading-relaxed">{{ $booking->field->gor->address }}</p>

                @if($booking->field->gor->maps_url)
                    <a href="{{ $booking->field->gor->maps_url }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-action-primary hover:underline pt-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Buka Lokasi di Google Maps &rarr;
                    </a>
                @endif
            </div>

            <!-- Detail Ringkas Rincian Sewa -->
            <div class="text-left text-xs space-y-2 border-t border-border-default pt-4 text-text-muted">
                <div class="flex justify-between"><span class="text-text-muted">Nama Pemesan:</span> <strong class="text-text-default">{{ $booking->customer_name }} ({{$booking->customer_phone }})</strong></div>
                <div class="flex justify-between"><span class="text-text-muted">Lapangan:</span> <strong class="text-text-default">{{ $booking->field->name }} ({{ strtoupper($booking->field->type) }})</strong></div>
                <div class="flex justify-between"><span class="text-text-muted">Tanggal Play:</span> <strong class="text-text-default">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</strong></div>
                <div class="flex justify-between items-center"><span class="text-text-muted">Total Pembayaran:</span> <strong class="text-action-primary text-sm font-bold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></div>
                <div class="flex justify-between"><span class="text-text-muted">Metode Pembayaran:</span> <strong class="uppercase text-text-default">{{ $booking->payment_method === 'transfer' ? 'Transfer Bank' : 'Cash di GOR' }}</strong></div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2 pt-2">
                @if($booking->payment_method === 'transfer')
                    <a href="{{ route('booking.check', ['code' => $booking->booking_code]) }}" class="block w-full py-3 bg-action-primary hover:bg-action-primary-hover text-white font-bold text-xs rounded-xl transition-colors shadow-xs">
                        Upload Bukti Transfer Sekarang
                    </a>
                @endif
                <a href="{{ route('booking.index') }}" class="block w-full py-3 bg-surface-page text-text-default font-bold text-xs rounded-xl hover:bg-border-default transition-colors border border-border-default">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
