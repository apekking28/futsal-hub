<x-layouts.admin headerTitle="Kelola Booking">
    <div class="space-y-6" x-data="{
        showEditModal: false,
        showProofModal: false,
        currentProofUrl: '',
        selectedBooking: { id: null, code: '', payment_status: 'pending', booking_status: 'reserved' }
    }">

        <!-- Quick Check-in Bar -->
        <div class="bg-surface-card p-4 rounded-xl border border-border-default shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="space-y-0.5">
                <h3 class="text-sm font-bold text-text-default">Quick Check-In Kasir</h3>
                <p class="text-xs text-text-muted">Masukkan Kode Booking pelanggan untuk verifikasi kehadiran & pelunasan di tempat.</p>
            </div>
            <form method="POST" action="{{ route('admin.bookings.checkin') }}" class="flex flex-col sm:flex-row w-full md:w-auto gap-2">
                @csrf
                <input type="text" name="booking_code" placeholder="Kode Booking (cth: FTS-XXXX)" required class="w-full sm:w-64 px-3.5 py-2 text-xs rounded-lg border border-border-default uppercase font-mono font-bold outline-none focus:ring-2 focus:ring-action-primary">
                <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-action-primary hover:bg-action-primary-hover text-white text-xs font-bold rounded-lg transition-colors whitespace-nowrap text-center">
                    Verifikasi & Check-In
                </button>
            </form>
        </div>

        <!-- Filter & Search Bar -->
        <div class="flex flex-col sm:flex-row justify-between gap-3">
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="flex flex-col sm:flex-row flex-1 gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode Booking / Nama / HP..." class="w-full sm:max-w-xs px-3.5 py-2 text-xs rounded-lg border border-border-default outline-none focus:ring-2 focus:ring-action-primary">
                <select name="status" onchange="this.form.submit()" class="w-full sm:w-auto px-3.5 py-2 text-xs rounded-lg border border-border-default outline-none bg-surface-card">
                    <option value="">Semua Status Bayar</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </form>
        </div>

        <!-- TABEL STABIL RESPONSIF (Horizontal & Vertical Scroll Only) -->
        <div class="bg-surface-card border border-border-default rounded-xl shadow-sm overflow-hidden">
            <!-- Container Scroll Samping Lembut -->
            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left border-collapse min-w-[850px] table-fixed">
                    <thead>
                        <tr class="bg-surface-page border-b border-border-default text-xs font-bold uppercase text-text-muted sticky top-0 z-10">
                            <th class="p-4 w-48">Kode & Pemesan</th>
                            <th class="p-4 w-44">Lapangan</th>
                            <th class="p-4 w-40">Tanggal & Jam</th>
                            <th class="p-4 w-44">Total & Metode</th>
                            <th class="p-4 w-40">Status Bayar / Booking</th>
                            <th class="p-4 w-32 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-default text-sm">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-surface-page/50 transition-colors">
                                <td class="p-4">
                                    <p class="font-mono font-bold text-action-primary text-xs truncate">{{ $booking->booking_code }}</p>
                                    <p class="font-semibold text-text-default text-xs truncate">{{ $booking->customer_name }}</p>
                                    <p class="text-[11px] text-text-muted truncate">{{ $booking->customer_phone }}</p>
                                </td>
                                <td class="p-4">
                                    <p class="font-semibold text-xs truncate">{{ $booking->field->gor->name }}</p>
                                    <p class="text-xs text-text-muted truncate">{{ $booking->field->name }}</p>
                                </td>
                                <td class="p-4">
                                    <p class="font-bold text-xs">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
                                    <p class="text-[11px] text-text-muted truncate">
                                        {{ $booking->details->pluck('start_time')->map(fn($t) => substr($t, 0, 5))->join(', ') }}
                                    </p>
                                </td>
                                <td class="p-4 space-y-1">
                                    <p class="font-bold text-xs text-action-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="px-1.5 py-0.5 text-[10px] font-bold uppercase rounded bg-surface-page border border-border-default">{{ $booking->payment_method }}</span>

                                        @if($booking->proof_of_payment)
                                            <button @click="showProofModal = true; currentProofUrl = '{{ asset('storage/' . $booking->proof_of_payment) }}'"
                                                    class="px-2 py-0.5 text-[10px] font-bold text-white bg-action-primary hover:bg-action-primary-hover rounded transition-colors inline-flex items-center gap-1 shadow-sm whitespace-nowrap">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Bukti TF
                                            </button>
                                        @elseif($booking->payment_method === 'transfer')
                                            <span class="text-[10px] text-text-muted italic bg-surface-page px-1.5 py-0.5 rounded border border-border-default whitespace-nowrap">(Belum TF)</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4 space-y-1">
                                    <div><x-status-badge :status="$booking->payment_status" /></div>
                                    <div><span class="px-2 py-0.5 text-[10px] font-bold rounded bg-surface-page border border-border-default uppercase">{{ $booking->booking_status }}</span></div>
                                </td>
                                <td class="p-4 text-right space-x-1 whitespace-nowrap">
                                    @hasanyrole('super-admin|admin-gor')
                                    <button @click="showEditModal = true; selectedBooking = { id: {{ $booking->id }}, code: '{{ $booking->booking_code }}', payment_status: '{{ $booking->payment_status }}', booking_status: '{{ $booking->booking_status }}' }"
                                            class="px-2.5 py-1 text-xs font-bold bg-action-primary/10 text-action-primary hover:bg-action-primary hover:text-white rounded transition-colors">
                                        Edit Status
                                    </button>
                                    @endhasanyrole

                                    @if($booking->payment_status === 'pending')
                                        <form method="POST" action="{{ route('admin.bookings.approve', $booking->id) }}" class="inline-block">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="px-2.5 py-1 text-xs font-bold bg-[#DCFCE7] text-[#166534] rounded hover:opacity-80">Approve</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-xs text-text-muted">Tidak ada data booking ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pop-up Modal 1: Preview Bukti Transfer -->
        <div x-show="showProofModal" class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4" x-cloak>
            <div class="bg-surface-card rounded-2xl max-w-lg w-full p-4 space-y-3 border border-border-default shadow-xl relative max-h-[90vh] flex flex-col" @click.away="showProofModal = false">
                <div class="flex justify-between items-center border-b border-border-default pb-2 shrink-0">
                    <h3 class="text-sm font-bold text-text-default">Bukti Transfer Pelanggan</h3>
                    <button @click="showProofModal = false" class="text-text-muted hover:text-text-default font-bold text-base">&times;</button>
                </div>
                <div class="bg-surface-page rounded-xl p-2 flex items-center justify-center flex-1 overflow-auto">
                    <img :src="currentProofUrl" alt="Bukti Transfer" class="max-h-[60vh] w-auto object-contain rounded-lg shadow">
                </div>
                <div class="text-right shrink-0 pt-2 border-t border-border-default">
                    <button @click="showProofModal = false" class="px-4 py-1.5 text-xs font-bold bg-surface-page border border-border-default rounded-lg hover:bg-border-default">Tutup</button>
                </div>
            </div>
        </div>

        <!-- Pop-up Modal 2: Re-Update Status Booking -->
        <div x-show="showEditModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" x-cloak>
            <div class="bg-surface-card rounded-2xl max-w-sm w-full p-6 space-y-4 border border-border-default shadow-lg" @click.away="showEditModal = false">
                <div>
                    <h3 class="text-base font-bold">Koreksi Status Transaksi</h3>
                    <p class="text-xs text-text-muted">Kode: <span class="font-mono font-bold text-action-primary" x-text="selectedBooking.code"></span></p>
                </div>

                <form :action="'/admin/bookings/' + selectedBooking.id + '/update-status'" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Status Pembayaran</label>
                        <select name="payment_status" x-model="selectedBooking.payment_status" class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none bg-surface-card">
                            <option value="pending">Pending (Menunggu)</option>
                            <option value="approved">Approved (Lunas / Disetujui)</option>
                            <option value="rejected">Rejected (Ditolak)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Status Booking</label>
                        <select name="booking_status" x-model="selectedBooking.booking_status" class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none bg-surface-card">
                            <option value="reserved">Reserved (Tersimpan)</option>
                            <option value="confirmed">Confirmed (Terkonfirmasi)</option>
                            <option value="completed">Completed (Selesai)</option>
                            <option value="cancelled">Cancelled (Dibatalkan)</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 text-xs font-bold bg-surface-page rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold bg-action-primary text-white rounded-lg">Simpan Koreksi</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts.admin>
