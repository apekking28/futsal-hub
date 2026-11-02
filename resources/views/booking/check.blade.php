<x-layouts.app title="Cek Status Booking - FutsalHub">
    <div class="max-w-xl mx-auto space-y-6">
        <div class="bg-surface-card p-6 sm:p-8 rounded-2xl border border-border-default shadow-xs space-y-6">
            <div class="space-y-1">
                <h2 class="text-lg font-bold text-text-default">Cek Status Booking</h2>
                <p class="text-xs text-text-muted">Masukkan Kode Booking untuk melihat status atau mengunggah bukti transfer.</p>
            </div>

            <!-- Form Pencarian Kode Booking -->
            <form method="GET" action="{{ route('booking.check') }}" class="flex flex-col sm:flex-row gap-2.5">
                <input type="text" name="code" value="{{ request('code') }}" placeholder="Contoh: FTS-XXXXX" required class="flex-1 px-4 py-2.5 text-sm rounded-xl border border-border-default uppercase font-mono font-bold focus:ring-2 focus:ring-action-primary outline-none bg-surface-card">
                <button type="submit" class="px-6 py-2.5 bg-action-primary hover:bg-action-primary-hover text-white text-xs font-bold rounded-xl transition-colors shadow-xs">Cari Booking</button>
            </form>

            @if(isset($booking))
                <div class="border-t border-border-default pt-6 space-y-5">
                    <!-- Status Header -->
                    <div class="flex items-center justify-between p-4 bg-surface-page rounded-xl border border-border-default">
                        <div>
                            <span class="block text-[10px] uppercase tracking-wider font-semibold text-text-muted">Kode Booking</span>
                            <span class="font-mono font-black text-xl text-action-primary tracking-wider">{{ $booking->booking_code }}</span>
                        </div>
                        <x-status-badge :status="$booking->payment_status" />
                    </div>

                    <!-- Detail Booking -->
                    <div class="text-xs space-y-2 text-text-muted bg-surface-card p-4 rounded-xl border border-border-default">
                        <div class="flex justify-between border-b border-border-default/60 pb-1.5">
                            <span>Nama Pemesan</span>
                            <strong class="text-text-default font-semibold">{{ $booking->customer_name }} ({{ $booking->customer_phone }})</strong>
                        </div>
                        <div class="flex justify-between border-b border-border-default/60 pb-1.5">
                            <span>GOR & Lapangan</span>
                            <strong class="text-text-default font-semibold">{{ $booking->field->gor->name }} - {{ $booking->field->name }}</strong>
                        </div>
                        <div class="flex justify-between border-b border-border-default/60 pb-1.5">
                            <span>Tanggal Play</span>
                            <strong class="text-text-default font-semibold">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</strong>
                        </div>
                        <div class="flex justify-between pt-0.5">
                            <span>Total Pembayaran</span>
                            <strong class="text-action-primary font-bold text-sm">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <!-- Bukti Transfer yang Sudah Di-upload -->
                    @if($booking->proof_of_payment)
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-text-default">Bukti Transfer Terunggah:</label>
                            <div class="relative w-full max-h-72 overflow-hidden rounded-xl border border-border-default bg-surface-page flex items-center justify-center p-3">
                                <img src="{{ asset('storage/' . $booking->proof_of_payment) }}" alt="Bukti Pembayaran" class="max-h-60 object-contain rounded-lg shadow-xs">
                            </div>
                            <p class="text-[11px] text-text-muted text-center">Bukti transfer sedang dalam verifikasi staff/kasir GOR.</p>
                        </div>
                    @endif

                    <!-- Form Upload / Re-upload Bukti Transfer -->
                    @if($booking->payment_method === 'transfer' && ($booking->payment_status === 'pending' || $booking->payment_status === 'rejected'))
                        <form method="POST" action="{{ route('booking.upload_proof', $booking->id) }}" enctype="multipart/form-data"
                              x-data="{ imageUrl: null }"
                              class="space-y-4 bg-surface-page p-4 rounded-xl border border-border-default">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold text-text-default mb-1.5">
                                    {{ $booking->proof_of_payment ? 'Ganti Bukti Transfer' : 'Upload Bukti Transfer (JPG, PNG)' }}
                                </label>
                                <input type="file" name="payment_proof" accept="image/jpeg,image/png,image/jpg" required
                                       @change="const file = $event.target.files[0]; if(file){ imageUrl = URL.createObjectURL(file) }"
                                       class="block w-full text-xs text-text-muted file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-action-primary file:text-white hover:file:bg-action-primary-hover cursor-pointer">
                            </div>

                            <!-- Live Preview Gambar Sebelum Dikirim -->
                            <template x-if="imageUrl">
                                <div class="space-y-1.5">
                                    <p class="text-[11px] font-semibold text-text-muted">Preview Gambar:</p>
                                    <div class="w-full max-h-56 overflow-hidden rounded-lg border border-border-default bg-surface-card flex items-center justify-center p-2">
                                        <img :src="imageUrl" class="max-h-48 object-contain rounded">
                                    </div>
                                </div>
                            </template>

                            <button type="submit" class="w-full py-2.5 bg-action-primary hover:bg-action-primary-hover text-white text-xs font-bold rounded-xl transition-colors shadow-xs">
                                {{ $booking->proof_of_payment ? 'Perbarui Bukti Transfer' : 'Kirim Bukti Pembayaran' }}
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
