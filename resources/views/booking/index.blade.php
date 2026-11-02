<x-layouts.app title="Sewa Lapangan Futsal - FutsalHub">
    <div class="max-w-5xl mx-auto space-y-8" x-data="bookingApp()">

        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-surface-card rounded-3xl border border-border-default p-6 sm:p-10 shadow-xs">
            <!-- Decorative Accent Background -->
            <div class="absolute -top-12 -right-12 w-64 h-64 bg-action-primary/5 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 max-w-2xl space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-page border border-border-default text-text-muted text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-action-primary animate-pulse"></span>
                    Booking Realtime Cengkareng & Sekitarnya
                </div>

                <h1 class="text-2xl sm:text-4xl font-black text-text-default tracking-tight leading-tight">
                    Main Futsal Tanpa Ribet, <br class="hidden sm:inline">
                    <span class="text-action-primary">Pilih Lapangan & Jam Langsung.</span>
                </h1>

                <p class="text-xs sm:text-sm text-text-muted leading-relaxed">
                    Pilih slot jam yang tersedia secara langsung tanpa perlu mendaftar akun. Dapatkan Kode Booking instan dan bayar secara fleksibel lewat Transfer/QRIS atau Cash di lokasi.
                </p>

                <!-- Value Props / Feature Badges -->
                <div class="grid grid-cols-3 gap-3 pt-2">
                    <div class="p-3 rounded-2xl bg-surface-page border border-border-default text-center">
                        <span class="block text-base sm:text-lg font-extrabold text-action-primary">100%</span>
                        <span class="text-[10px] sm:text-xs font-semibold text-text-muted">Realtime Slot</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-surface-page border border-border-default text-center">
                        <span class="block text-base sm:text-lg font-extrabold text-action-primary">0 min</span>
                        <span class="text-[10px] sm:text-xs font-semibold text-text-muted">Tanpa Login</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-surface-page border border-border-default text-center">
                        <span class="block text-base sm:text-lg font-extrabold text-action-primary">Instan</span>
                        <span class="text-[10px] sm:text-xs font-semibold text-text-muted">Kode Booking</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header Ringkasan Lapangan (Jika Diarahkan dari Katalog dengan Query ?field=ID) -->
        @if(isset($selectedFieldModel))
            <div class="bg-surface-card p-4 sm:p-5 rounded-2xl border border-border-default flex items-center justify-between gap-4 shadow-xs">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden bg-surface-page border border-border-default shrink-0">
                        @if($selectedFieldModel->images && $selectedFieldModel->images->first())
                            <img src="{{ asset('storage/' . $selectedFieldModel->images->first()->thumb_path) }}" alt="{{ $selectedFieldModel->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-text-muted text-[10px]">No Image</div>
                        @endif
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-action-primary uppercase tracking-wider">{{ $selectedFieldModel->gor->name ?? '' }}</span>
                        <h2 class="text-base sm:text-lg font-bold text-text-default">{{ $selectedFieldModel->name }}</h2>
                        <p class="text-xs font-bold text-text-muted mt-0.5">Rp {{ number_format($selectedFieldModel->price_per_hour, 0, ',', '.') }} / jam</p>
                    </div>
                </div>
                @if(Route::has('fields.show'))
                    <a href="{{ route('fields.show', $selectedFieldModel->slug) }}" class="hidden sm:inline-flex px-3 py-1.5 text-xs font-semibold text-text-muted hover:text-text-default border border-border-default rounded-lg hover:bg-surface-page transition-colors">
                        Lihat Detail
                    </a>
                @endif
            </div>
        @endif

        <!-- Form Booking Utama -->
        <form method="POST" action="{{ route('booking.store') }}" class="bg-surface-card p-5 sm:p-8 rounded-2xl border border-border-default shadow-xs space-y-8">
            @csrf

            <!-- Section 1: Tanggal & Lapangan -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-text-default uppercase tracking-wider border-b border-border-default pb-2">1. Pilih Lapangan & Tanggal</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1.5 uppercase">Lapangan</label>
                        <select name="field_id" x-model="selectedField" @change="fetchSlots()" class="w-full px-4 py-2.5 text-xs font-semibold rounded-xl border border-border-default bg-surface-card text-text-default outline-none focus:ring-2 focus:ring-action-primary focus:border-action-primary transition-all">
                            @if(isset($fields) && count($fields) > 0)
                                @foreach($fields as $field)
                                    <option value="{{ $field->id }}">
                                        {{ $field->gor->name ?? 'GOR' }} - {{ $field->name }} (Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}/jam)
                                    </option>
                                @endforeach
                            @else
                                <option value="">Tidak ada lapangan tersedia</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1.5 uppercase">Tanggal Main</label>
                        <input type="date" name="booking_date" min="{{ date('Y-m-d') }}" x-model="selectedDate" @change="fetchSlots()" class="w-full px-4 py-2.5 text-xs font-semibold rounded-xl border border-border-default bg-surface-card text-text-default outline-none focus:ring-2 focus:ring-action-primary focus:border-action-primary transition-all">
                    </div>
                </div>
            </div>

            <!-- Section 2: Pemilihan Slot Jam -->
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-border-default pb-2">
                    <h3 class="text-xs font-bold text-text-default uppercase tracking-wider">2. Pilih Jam Main</h3>
                    <div class="flex items-center gap-3 text-[11px] text-text-muted">
                        <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-surface-card border border-border-default"></span> Tersedia</span>
                        <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-action-primary"></span> Dipilih</span>
                        <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-surface-page border border-border-default opacity-50"></span> Terisi</span>
                    </div>
                </div>

                <!-- Loading State -->
                <div x-show="loading" class="text-center py-12 bg-surface-page rounded-xl border border-dashed border-border-default space-y-2">
                    <svg class="animate-spin h-5 w-5 text-action-primary mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-xs font-semibold text-text-muted">Memuat ketersediaan slot jam...</p>
                </div>

                <!-- Slots List (Item Alpine.js) -->
                <div x-show="!loading" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-2.5">
                    <template x-for="item in slots" :key="item.time">
                        <button type="button"
                                @click="toggleSlot(item.time)"
                                :disabled="!item.available"
                                :class="{
                                    'bg-action-primary text-white border-action-primary shadow-xs': selectedSlots.includes(item.time),
                                    'bg-surface-card border-border-default hover:border-action-primary text-text-default': item.available && !selectedSlots.includes(item.time),
                                    'bg-surface-page border-border-default opacity-50 cursor-not-allowed text-text-muted': !item.available
                                }"
                                class="p-3 border rounded-xl flex flex-col items-center justify-center transition-all text-center group min-h-[64px]">
                            <span class="font-bold text-sm" x-text="item.time"></span>
                            <span class="text-[10px] font-semibold mt-0.5"
                                  :class="selectedSlots.includes(item.time) ? 'text-white' : (item.is_booked ? 'text-status-danger-text' : (item.is_past ? 'text-text-muted' : 'text-action-primary'))"
                                  x-text="item.is_booked ? 'Terisi' : (item.is_past ? 'Lewat' : (selectedSlots.includes(item.time) ? 'Dipilih' : 'Tersedia'))">
                            </span>
                        </button>
                    </template>
                </div>

                <!-- Hidden Input array untuk dikirim ke Backend -->
                <template x-for="time in selectedSlots" :key="time">
                    <input type="hidden" name="slots[]" :value="time">
                </template>
            </div>

            <!-- Section 3: Informasi Pemesan & Pembayaran -->
            <div class="space-y-4 pt-4 border-t border-border-default">
                <h3 class="text-xs font-bold text-text-default uppercase tracking-wider">3. Informasi Pemesan & Pembayaran</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1.5">Nama Pemesan</label>
                        <input type="text" name="customer_name" required placeholder="Masukkan nama lengkap..." class="w-full px-4 py-2.5 text-xs font-semibold rounded-xl border border-border-default bg-surface-card text-text-default outline-none focus:ring-2 focus:ring-action-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1.5">No. WhatsApp</label>
                        <input type="text" name="customer_phone" required placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 text-xs font-semibold rounded-xl border border-border-default bg-surface-card text-text-default outline-none focus:ring-2 focus:ring-action-primary">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-text-muted mb-1.5">Metode Pembayaran</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="p-3.5 border border-border-default rounded-xl flex items-center gap-3 cursor-pointer hover:bg-surface-page transition-colors">
                            <input type="radio" name="payment_method" value="transfer" checked class="text-action-primary focus:ring-action-primary">
                            <div>
                                <span class="block text-xs font-bold text-text-default">Transfer Bank / QRIS</span>
                                <span class="block text-[11px] text-text-muted">Verifikasi bukti bayar oleh staff</span>
                            </div>
                        </label>
                        <label class="p-3.5 border border-border-default rounded-xl flex items-center gap-3 cursor-pointer hover:bg-surface-page transition-colors">
                            <input type="radio" name="payment_method" value="cash" class="text-action-primary focus:ring-action-primary">
                            <div>
                                <span class="block text-xs font-bold text-text-default">Bayar di GOR (Cash)</span>
                                <span class="block text-[11px] text-text-muted">Bayar tunai saat berada di lokasi</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button Desktop -->
            <button type="submit" :disabled="selectedSlots.length === 0" class="w-full py-3.5 bg-action-primary hover:bg-action-primary-hover disabled:opacity-50 text-white font-bold text-sm rounded-xl transition-all shadow-xs cursor-pointer disabled:cursor-not-allowed">
                Lanjut Booking (<span x-text="selectedSlots.length"></span> Jam)
            </button>
        </form>

        <!-- Sticky Summary Mobile Bar -->
        <div x-show="selectedSlots.length > 0"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="translate-y-full opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-full opacity-0"
             class="fixed bottom-0 left-0 right-0 bg-surface-card border-t border-border-default p-4 shadow-lg z-30 md:hidden flex items-center justify-between gap-4">
            <div>
                <p class="text-[11px] font-semibold text-text-muted">Total Jam Terpilih</p>
                <p class="text-sm font-bold text-action-primary"><span x-text="selectedSlots.length"></span> Jam Dipilih</p>
            </div>
            <button type="button" @click="document.querySelector('form').requestSubmit()" class="px-5 py-2.5 bg-action-primary hover:bg-action-primary-hover text-white font-bold text-xs rounded-xl shadow-xs">
                Selesaikan Booking
            </button>
        </div>
    </div>

    <script>
        function bookingApp() {
            return {
                selectedField: '{{ $selectedFieldId ?? '' }}',
                selectedDate: '{{ $selectedDate ?? date('Y-m-d') }}',
                slots: [],
                selectedSlots: [],
                loading: false,

                init() {
                    this.fetchSlots();
                },

                async fetchSlots() {
                    if (!this.selectedField || !this.selectedDate) return;
                    this.loading = true;
                    this.selectedSlots = [];

                    try {
                        const res = await fetch(`/booking/slots?field_id=${this.selectedField}&date=${this.selectedDate}`);
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
