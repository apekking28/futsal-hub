<x-layouts.app title="Sewa Lapangan Futsal - FutsalHub">
    <div class="max-w-5xl mx-auto space-y-8" x-data="bookingApp()">

        <!-- Hero Section Header -->
        <div
            class="relative overflow-hidden bg-surface-card rounded-3xl border border-border-default p-6 sm:p-8 shadow-xs">
            <div
                class="absolute -top-12 -right-12 w-64 h-64 bg-action-primary/5 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="relative z-10 max-w-2xl space-y-3">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-page border border-border-default text-text-muted text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-action-primary animate-pulse"></span>
                    Booking Realtime Cengkareng & Sekitarnya
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-text-default tracking-tight leading-tight">
                    Sewa Lapangan Futsal Mudah & Instan
                </h1>

                <p class="text-xs sm:text-sm text-text-muted leading-relaxed">
                    Cari wilayah atau GOR terdekat, pilih lapangan favorit Anda, lalu pilih jam main yang tersedia
                    secara realtime tanpa perlu buat akun.
                </p>
            </div>
        </div>

        <div id="card-list-wrapper">
            <x-field-card-list :fields="$fields" :selectedFieldId="$selectedFieldId" :districts="$districts" :gors="$gors" />
        </div>

        <!-- FORMULIR BOOKING (HANYA MUNCUL JIKA USER SUDAH MEMILIH SALAH SATU LAPANGAN) -->
        <form method="POST" action="{{ route('booking.store') }}" id="booking-form-section" x-show="selectedField"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            class="bg-surface-card p-6 sm:p-8 rounded-3xl border-2 border-action-primary/30 shadow-md space-y-8"
            x-cloak>
            @csrf

            <!-- Hidden Input ID Lapangan Terpilih -->
            <input type="hidden" name="field_id" x-model="selectedField">

            <!-- LANGKAH 3A: TANGGAL MAIN & JADWAL JAM REALTIME -->
            <div class="space-y-6">
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-border-default pb-4">
                    <div>
                        <span
                            class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full bg-action-primary text-white tracking-wider">Langkah
                            Terakhir</span>
                        <h3 class="text-base font-bold text-text-default mt-1">Pilih Tanggal & Slot Jam Playing</h3>
                    </div>

                    <!-- Input Pilih Tanggal -->
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-text-muted shrink-0">Tanggal Main:</label>
                        <input type="date" name="booking_date" min="{{ date('Y-m-d') }}" x-model="selectedDate"
                            @change="fetchSlots()"
                            class="px-3.5 py-2 text-xs font-semibold rounded-xl border border-border-default bg-surface-page text-text-default outline-none focus:ring-2 focus:ring-action-primary">
                    </div>
                </div>

                <!-- Legend Keterangan Slot Jam -->
                <div class="flex items-center gap-4 text-xs text-text-muted">
                    <span class="flex items-center gap-1.5"><span
                            class="w-3 h-3 rounded-md bg-surface-card border border-border-default"></span>
                        Tersedia</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-md bg-action-primary"></span>
                        Dipilih</span>
                    <span class="flex items-center gap-1.5"><span
                            class="w-3 h-3 rounded-md bg-surface-page border border-border-default opacity-50"></span>
                        Terisi / Lewat</span>
                </div>

                <!-- Loading Spinner State -->
                <div x-show="loading"
                    class="text-center py-12 bg-surface-page rounded-2xl border border-dashed border-border-default space-y-2">
                    <svg class="animate-spin h-6 w-6 text-action-primary mx-auto" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <p class="text-xs font-semibold text-text-muted">Mengecek jadwal slot jam ketersediaan...</p>
                </div>

                <!-- Daftar Slot Jam Realtime -->
                <div x-show="!loading" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-2.5">
                    <template x-for="item in slots" :key="item.time">
                        <button type="button" @click="toggleSlot(item.time)" :disabled="!item.available"
                            :class="{
                                'bg-action-primary text-white border-action-primary shadow-xs': selectedSlots.includes(
                                    item.time),
                                'bg-surface-card border-border-default hover:border-action-primary text-text-default': item
                                    .available && !selectedSlots.includes(item.time),
                                'bg-surface-page border-border-default opacity-50 cursor-not-allowed text-text-muted': !
                                    item.available
                            }"
                            class="p-3 border rounded-xl flex flex-col items-center justify-center transition-all text-center group min-h-[64px] cursor-pointer">
                            <span class="font-bold text-sm" x-text="item.time"></span>
                            <span class="text-[10px] font-semibold mt-0.5"
                                :class="selectedSlots.includes(item.time) ? 'text-white' : (item.is_booked ?
                                    'text-status-danger-text' : (item.is_past ? 'text-text-muted' :
                                        'text-action-primary'))"
                                x-text="item.is_booked ? 'Terisi' : (item.is_past ? 'Lewat' : (selectedSlots.includes(item.time) ? 'Dipilih' : 'Tersedia'))">
                            </span>
                        </button>
                    </template>
                </div>

                <!-- Hidden Input Array Slot Jam -->
                <template x-for="time in selectedSlots" :key="time">
                    <input type="hidden" name="slots[]" :value="time">
                </template>
            </div>

            <!-- LANGKAH 3B: DATA PEMESAN & PEMBAYARAN -->
            <div class="space-y-4 pt-4 border-t border-border-default">
                <h3 class="text-xs font-bold text-text-default uppercase tracking-wider">Informasi Pemesan & Pembayaran
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1.5">Nama Pemesan</label>
                        <input type="text" name="customer_name" required placeholder="Masukkan nama lengkap..."
                            class="w-full px-4 py-2.5 text-xs font-semibold rounded-xl border border-border-default bg-surface-card text-text-default outline-none focus:ring-2 focus:ring-action-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1.5">No. WhatsApp / HP</label>
                        <input type="text" name="customer_phone" required placeholder="08xxxxxxxxxx"
                            class="w-full px-4 py-2.5 text-xs font-semibold rounded-xl border border-border-default bg-surface-card text-text-default outline-none focus:ring-2 focus:ring-action-primary">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-text-muted mb-1.5">Metode Pembayaran</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label
                            class="p-3.5 border border-border-default rounded-2xl flex items-center gap-3 cursor-pointer hover:bg-surface-page transition-colors">
                            <input type="radio" name="payment_method" value="transfer" checked
                                class="text-action-primary focus:ring-action-primary">
                            <div>
                                <span class="block text-xs font-bold text-text-default">Transfer Bank / QRIS</span>
                                <span class="block text-[11px] text-text-muted">Unggah bukti bayar setelah
                                    pemesanan</span>
                            </div>
                        </label>
                        <label
                            class="p-3.5 border border-border-default rounded-2xl flex items-center gap-3 cursor-pointer hover:bg-surface-page transition-colors">
                            <input type="radio" name="payment_method" value="cash"
                                class="text-action-primary focus:ring-action-primary">
                            <div>
                                <span class="block text-xs font-bold text-text-default">Bayar di GOR (Cash)</span>
                                <span class="block text-[11px] text-text-muted">Bayar tunai di lokasi kasir GOR</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Tombol Konfirmasi Booking -->
            <button type="submit" :disabled="selectedSlots.length === 0"
                class="w-full py-3.5 bg-action-primary hover:bg-action-primary-hover disabled:opacity-50 text-white font-bold text-sm rounded-xl transition-all shadow-xs cursor-pointer disabled:cursor-not-allowed">
                Lanjut Booking (<span x-text="selectedSlots.length"></span> Jam Dipilih)
            </button>
        </form>

    </div>

    <script>
        function bookingApp() {
            return {
                selectedField: '',
                selectedDate: '{{ $selectedDate ?? date('Y-m-d') }}',
                slots: [],
                selectedSlots: [],
                loading: false,
                loadingFields: false,

                // Interseptor Klik Pagination (Bawaan Laravel Links) Tanpa Reload
                handlePaginationClick(e) {
                    const targetLink = e.target.closest('a');
                    if (targetLink && targetLink.href) {
                        e.preventDefault();
                        this.fetchFieldsFromUrl(targetLink.href);
                    }
                },

                // Submit Form Filter via AJAX
                submitFilter(event) {
                    const formData = new FormData(event.target);
                    const params = new URLSearchParams(formData).toString();
                    const url = `{{ route('booking.index') }}?${params}`;
                    this.fetchFieldsFromUrl(url);
                },

                // Reset Filter
                resetFilter() {
                    this.fetchFieldsFromUrl('{{ route('booking.index') }}');
                },

                // Helper Fetch Data Lapangan
                async fetchFieldsFromUrl(url) {
                    this.loadingFields = true;
                    try {
                        const res = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const html = await res.text();

                        // Replace container kartu lapangan dengan HTML baru secara transparan
                        const wrapper = document.getElementById('card-list-wrapper');
                        if (wrapper) {
                            wrapper.innerHTML = html;
                        }

                        // Update URL browser tanpa me-refresh halaman (History API)
                        window.history.pushState({}, '', url);

                    } catch (err) {
                        console.error("Gagal memuat data lapangan:", err);
                    } finally {
                        this.loadingFields = false;
                    }
                },

                async fetchSlots() {
                    if (!this.selectedField || !this.selectedDate) return;
                    this.loading = true;
                    this.selectedSlots = [];

                    try {
                        const res = await fetch(
                            `/booking/slots?field_id=${this.selectedField}&date=${this.selectedDate}`);
                        const data = await res.json();
                        if (data.success) {
                            this.slots = data.slots;
                        }
                    } catch (e) {
                        console.error("Gagal mengambil data slot:", e);
                    } finally {
                        this.loading = false;
                    }
                },

                toggleSlot(time) {
                    if (this.selectedSlots.includes(time)) {
                        this.selectedSlots = this.selectedSlots.filter(t => t !== time);
                    } else {
                        this.selectedSlots.push(time);
                    }
                }
            }
        }
    </script>
</x-layouts.app>
