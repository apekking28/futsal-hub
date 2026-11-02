<x-layouts.app title="Sewa Lapangan Futsal">
    <div class="max-w-4xl mx-auto space-y-6" x-data="bookingApp()">

        <form method="POST" action="{{ route('booking.store') }}" class="bg-surface-card p-6 rounded-2xl border border-border-default shadow-sm space-y-6">
            @csrf

            <!-- Selection Inputs -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-text-muted mb-1 uppercase">Pilih Lapangan</label>
                    <select name="field_id" x-model="selectedField" @change="fetchSlots()" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-border-default font-semibold outline-none focus:ring-2 focus:ring-action-primary">
                        @foreach($fields as $field)
                            <option value="{{ $field->id }}">{{ $field->gor->name }} - {{ $field->name }} (Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}/jam)</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-text-muted mb-1 uppercase">Tanggal Main</label>
                    <input type="date" name="booking_date" min="{{ date('Y-m-d') }}" x-model="selectedDate" @change="fetchSlots()" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-border-default font-semibold outline-none focus:ring-2 focus:ring-action-primary">
                </div>
            </div>

            <!-- Slots Grid -->
            <div>
                <label class="block text-xs font-bold text-text-muted mb-2 uppercase">Pilih Jam (Klik Slot Jam yang Ingin Disewa)</label>

                <!-- Loading State -->
                <div x-show="loading" class="text-center py-6 text-xs text-text-muted">
                    Memuat ketersediaan slot jam...
                </div>

                <!-- Slots List -->
                <div x-show="!loading" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-3">
                    <template x-for="slot in slots" :key="slot.time">
                        <button type="button"
                                @click="toggleSlot(slot.time)"
                                :disabled="!slot.available"
                                :class="{
                                    'bg-action-primary text-white border-action-primary': selectedSlots.includes(slot.time),
                                    'bg-surface-card border-border-default hover:border-action-primary text-text-default': slot.available && !selectedSlots.includes(slot.time),
                                    'bg-surface-page border-border-default opacity-50 cursor-not-allowed text-text-muted': !slot.available
                                }"
                                class="p-3 border rounded-xl flex flex-col items-center justify-center transition-all text-center">
                            <span class="font-bold text-sm" x-text="slot.time"></span>
                            <span class="text-[10px] font-semibold mt-0.5"
                                  :class="selectedSlots.includes(slot.time) ? 'text-white' : (slot.is_booked ? 'text-status-danger-text' : (slot.is_past ? 'text-text-muted' : 'text-action-primary'))"
                                  x-text="slot.is_booked ? 'Terisi' : (slot.is_past ? 'Lewat' : (selectedSlots.includes(slot.time) ? 'Dipilih' : 'Tersedia'))">
                            </span>
                        </button>
                    </template>
                </div>

                <!-- Hidden Input array untuk dikirim ke Backend -->
                <template x-for="time in selectedSlots" :key="time">
                    <input type="hidden" name="slots[]" :value="time">
                </template>
            </div>

            <!-- Form Pemesan -->
            <div class="space-y-4 border-t border-border-default pt-4">
                <h3 class="text-sm font-bold text-text-default">Informasi Pemesan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Nama Pemesan</label>
                        <input type="text" name="customer_name" required placeholder="Masukkan Nama Lengkap..." class="w-full px-3.5 py-2 text-xs rounded-xl border border-border-default outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">No. WhatsApp</label>
                        <input type="text" name="customer_phone" required placeholder="08xxxxxxxxxx" class="w-full px-3.5 py-2 text-xs rounded-xl border border-border-default outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-text-muted mb-1">Metode Pembayaran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="p-3 border rounded-xl flex items-center gap-2 cursor-pointer hover:bg-surface-page">
                            <input type="radio" name="payment_method" value="transfer" checked class="text-action-primary">
                            <span class="text-xs font-bold">Transfer Bank / QRIS</span>
                        </label>
                        <label class="p-3 border rounded-xl flex items-center gap-2 cursor-pointer hover:bg-surface-page">
                            <input type="radio" name="payment_method" value="cash" class="text-action-primary">
                            <span class="text-xs font-bold">Bayar di GOR (Cash)</span>
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" :disabled="selectedSlots.length === 0" class="w-full py-3 bg-action-primary hover:bg-action-primary-hover disabled:opacity-50 text-white font-bold text-sm rounded-xl transition-all">
                Lanjut Booking (<span x-text="selectedSlots.length"></span> Jam)
            </button>
        </form>
    </div>

    <script>
        function bookingApp() {
            return {
                selectedField: '{{ $selectedFieldId }}',
                selectedDate: '{{ $selectedDate }}',
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
