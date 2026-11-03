@props([
    'fields' => [],
    'selectedFieldId' => null
])

<div class="space-y-3">
    <div class="flex items-center justify-between">
        <h3 class="text-xs font-bold text-text-default uppercase tracking-wider">
            Pilih Lapangan Favorit
        </h3>
        <span class="text-[11px] font-semibold text-text-muted">
            Klik kartu untuk memilih
        </span>
    </div>

    <!-- Container Card Grid / Horizontal Scroll Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5">
        @forelse($fields as $field)
            @php
                $cover = $field->cover_image ?? $field->images->first();
                $isSelected = (string)$field->id === (string)$selectedFieldId;
            @endphp

            <div @click="selectedField = '{{ $field->id }}'; fetchSlots()"
                 :class="{
                     'ring-2 ring-action-primary border-action-primary bg-action-primary/5 shadow-md': selectedField == '{{ $field->id }}',
                     'border-border-default bg-surface-card hover:border-action-primary/50 hover:shadow-xs': selectedField != '{{ $field->id }}'
                 }"
                 class="relative rounded-2xl border p-3.5 transition-all cursor-pointer group flex flex-col justify-between space-y-3">

                <!-- Badge Selected Check Indicator -->
                <div x-show="selectedField == '{{ $field->id }}'"
                     class="absolute top-2.5 right-2.5 z-10 bg-action-primary text-white p-1 rounded-full shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>

                <div class="space-y-3">
                    <!-- Thumbnail Image Cover -->
                    <div class="relative h-32 w-full rounded-xl overflow-hidden bg-surface-page border border-border-default/60 shrink-0">
                        @if($cover)
                            <img src="{{ asset('storage/' . ($cover->thumb_path ?? $cover->path)) }}"
                                 alt="{{ $field->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-text-muted text-[11px] font-semibold">
                                No Photo Available
                            </div>
                        @endif

                        <!-- Tipe Lapangan Badge -->
                        <span class="absolute bottom-2 left-2 px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-black/60 backdrop-blur-xs text-white">
                            {{ $field->type }}
                        </span>
                    </div>

                    <!-- Informational Meta -->
                    <div>
                        <span class="text-[10px] font-bold text-action-primary uppercase tracking-wider block truncate">
                            {{ $field->gor->name ?? 'GOR' }}
                        </span>
                        <h4 class="text-sm font-bold text-text-default truncate group-hover:text-action-primary transition-colors">
                            {{ $field->name }}
                        </h4>

                        <p class="text-[11px] text-text-muted line-clamp-1 mt-0.5">
                            {{ $field->gor->address ?? 'Lokasi Cengkareng' }}
                        </p>
                    </div>
                </div>

                <!-- Footer Card: Harga & Operating Hours -->
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
            <div class="col-span-full py-8 text-center bg-surface-card rounded-2xl border border-dashed border-border-default text-xs text-text-muted">
                Belum ada lapangan yang aktif saat ini.
            </div>
        @endforelse
    </div>
</div>

