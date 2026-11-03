@props([
    'fields' => [],
    'selectedFieldId' => null,
    'districts' => [],
    'gors' => []
])

<div class="space-y-6" id="field-list-container">
    <!-- PANEL FILTER CARI & RESET -->
    <div class="bg-surface-card border border-border-default p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-border-default pb-3">
            <div>
                <h4 class="text-sm font-bold text-text-default">Cari & Filter Lapangan</h4>
                <p class="text-[11px] text-text-muted">Filter berdasarkan wilayah, GOR, atau jenis lantai sesuai kebutuhan Anda.</p>
            </div>
            @if(request()->hasAny(['district_id', 'gor_id', 'type']))
                <button type="button" @click="resetFilter()" class="text-xs font-bold text-status-danger-text hover:underline self-start sm:self-auto cursor-pointer">
                    Reset Filter
                </button>
            @endif
        </div>

        <!-- Submit Form Menggunakan AJAX (Mencegah Browser Reload) -->
        <form @submit.prevent="submitFilter($event)" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                <!-- 1. Filter Wilayah -->
                <div>
                    <label class="block text-xs font-bold text-text-muted mb-1.5">Wilayah / Kecamatan</label>
                    <select name="district_id" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-border-default bg-surface-card text-text-default outline-none focus:ring-2 focus:ring-action-primary">
                        <option value="">-- Semua Wilayah --</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                {{ $district->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 2. Filter GOR -->
                <div>
                    <label class="block text-xs font-bold text-text-muted mb-1.5">Nama GOR Futsal</label>
                    <select name="gor_id" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-border-default bg-surface-card text-text-default outline-none focus:ring-2 focus:ring-action-primary">
                        <option value="">-- Semua GOR --</option>
                        @foreach($gors as $gor)
                            <option value="{{ $gor->id }}" {{ request('gor_id') == $gor->id ? 'selected' : '' }}>
                                {{ $gor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Filter Jenis Lantai -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="block text-xs font-bold text-text-muted mb-1.5">Jenis Lantai</label>
                    <select name="type" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-border-default bg-surface-card text-text-default outline-none focus:ring-2 focus:ring-action-primary">
                        <option value="">-- Semua Lantai --</option>
                        <option value="vinyl" {{ request('type') == 'vinyl' ? 'selected' : '' }}>Vinyl</option>
                        <option value="sintetis" {{ request('type') == 'sintetis' ? 'selected' : '' }}>Rumput Sintetis</option>
                        <option value="parquet" {{ request('type') == 'parquet' ? 'selected' : '' }}>Parquet (Kayu)</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end pt-1">
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-action-primary hover:bg-action-primary-hover text-white text-xs font-bold rounded-xl transition-colors shadow-xs cursor-pointer flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- DAFTAR LAPANGAN -->
    <div class="space-y-4 relative">
        <!-- Loading Overlay saat fetch data -->
        <div x-show="loadingFields" class="absolute inset-0 bg-surface-page/70 backdrop-blur-xs flex items-center justify-center z-20 rounded-2xl" x-cloak>
            <div class="flex items-center gap-2 px-4 py-2 bg-surface-card border border-border-default rounded-xl shadow-md text-xs font-bold text-action-primary">
                <svg class="animate-spin h-4 w-4 text-action-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Memuat Lapangan...
            </div>
        </div>

        <div class="flex items-center justify-between">
            <h4 class="text-xs font-bold text-text-default uppercase tracking-wider">Pilih Lapangan Futsal</h4>
            <span class="text-[11px] font-semibold text-text-muted">Klik salah satu kartu untuk memilih</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($fields as $field)
                @php
                    $cover = $field->cover_image ?? $field->images->first();
                @endphp

                <div @click="selectedField = '{{ $field->id }}'; fetchSlots(); $nextTick(() => { document.getElementById('booking-form-section')?.scrollIntoView({ behavior: 'smooth' }); })"
                     :class="{
                         'ring-2 ring-action-primary border-action-primary bg-action-primary/10 shadow-md': selectedField == '{{ $field->id }}',
                         'border-border-default bg-surface-card hover:border-action-primary/50 hover:shadow-xs': selectedField != '{{ $field->id }}'
                     }"
                     class="relative rounded-2xl border p-4 transition-all cursor-pointer group flex flex-col justify-between space-y-3">

                    <div x-show="selectedField == '{{ $field->id }}'"
                         class="absolute top-3 right-3 z-10 bg-action-primary text-white p-1 rounded-full shadow-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>

                    <div class="space-y-3">
                        <div class="relative h-36 w-full rounded-xl overflow-hidden bg-surface-page border border-border-default/60 shrink-0">
                            @if($cover)
                                <img src="{{ asset('storage/' . ($cover->thumb_path ?? $cover->path)) }}"
                                     alt="{{ $field->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-text-muted text-[11px] font-semibold">
                                    No Photo Available
                                </div>
                            @endif

                            <span class="absolute bottom-2 left-2 px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-black/70 backdrop-blur-xs text-white">
                                {{ $field->type }}
                            </span>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold text-action-primary uppercase tracking-wider block truncate">
                                {{ $field->gor->name ?? 'GOR' }}
                            </span>
                            <h4 class="text-sm font-bold text-text-default truncate group-hover:text-action-primary transition-colors">
                                {{ $field->name }}
                            </h4>
                            <p class="text-[11px] text-text-muted line-clamp-1 mt-0.5">
                                {{ $field->gor->address ?? 'Alamat GOR' }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-border-default/60 flex items-center justify-between text-xs">
                        <span class="font-bold text-action-primary">
                            Rp {{ number_format($field->price_per_hour, 0, ',', '.') }} <span class="text-[10px] font-normal text-text-muted">/ jam</span>
                        </span>
                        <span class="text-[10px] font-semibold text-text-muted bg-surface-page px-2 py-0.5 rounded-lg border border-border-default">
                            {{ substr($field->gor->open_time ?? '08:00', 0, 5) }} - {{ substr($field->gor->close_time ?? '23:00', 0, 5) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-surface-card rounded-2xl border border-dashed border-border-default text-xs text-text-muted space-y-1">
                    <p class="font-bold text-text-default">Tidak Ada Lapangan Ditemukan</p>
                    <p>Coba sesuaikan pilihan kecamatan atau nama GOR pada filter di atas.</p>
                </div>
            @endforelse
        </div>

        <!-- PAGINATION BAWAAN LARAVEL (Mencegah Reload lewat Event Klik) -->
        @if(method_exists($fields, 'hasPages') && $fields->hasPages())
            <div class="pt-4 border-t border-border-default overflow-x-auto pagination-ajax-container"
                 @click="handlePaginationClick($event)">
                {{ $fields->links() }}
            </div>
        @endif
    </div>
</div>
