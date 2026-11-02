<x-layouts.admin headerTitle="Manajemen Lapangan">
    <div class="space-y-6" x-data="{
        showModal: false,
        showGalleryModal: false,
        editMode: false,
        currentField: null,
        form: { id: null, gor_id: '', name: '', type: 'vinyl', price_per_hour: 0, is_active: 1, description: '' }
    }">

        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-text-default">Daftar Lapangan</h2>
                <p class="text-xs text-text-muted">Kelola harga sewa, foto galeri, deskripsi, dan status operasional lapangan.</p>
            </div>
            <button @click="showModal = true; editMode = false; form = { id: null, gor_id: '{{ $gors->first()?->id }}', name: '', type: 'vinyl', price_per_hour: 100000, is_active: 1, description: '' }"
                    class="w-full sm:w-auto px-4 py-2 bg-action-primary hover:bg-action-primary-hover text-white font-bold text-xs rounded-xl transition-colors shadow-xs text-center">
                + Tambah Lapangan
            </button>
        </div>

        <!-- TABEL LAPANGAN -->
        <div class="bg-surface-card border border-border-default rounded-xl shadow-xs overflow-hidden">
            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left border-collapse min-w-[850px] table-fixed">
                    <thead>
                        <tr class="bg-surface-page border-b border-border-default text-xs font-bold uppercase text-text-muted">
                            <th class="p-4 w-20">Foto</th>
                            <th class="p-4 w-44">GOR / Wilayah</th>
                            <th class="p-4 w-44">Nama Lapangan</th>
                            <th class="p-4 w-32">Tipe Jenis</th>
                            <th class="p-4 w-36">Harga / Jam</th>
                            <th class="p-4 w-28">Status</th>
                            <th class="p-4 w-44 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-default text-sm">
                        @forelse($fields as $field)
                            <tr class="hover:bg-surface-page/50 transition-colors">
                                <!-- Preview Image / Cover -->
                                <td class="p-4">
                                    <div class="w-12 h-12 rounded-lg bg-surface-page border border-border-default overflow-hidden flex items-center justify-center shrink-0">
                                        @if($field->cover_image)
                                            <img src="{{ asset('storage/' . $field->cover_image->path) }}" alt="{{ $field->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-[9px] text-text-muted font-semibold">No Photo</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4 font-semibold text-xs truncate">{{ $field->gor->name }}</td>
                                <td class="p-4 font-bold text-xs text-text-default truncate">
                                    {{ $field->name }}
                                    <span class="block text-[10px] font-mono text-text-muted font-normal truncate">/{{ $field->slug }}</span>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded bg-surface-page border border-border-default text-xs font-semibold capitalize whitespace-nowrap">
                                        {{ $field->type }}
                                    </span>
                                </td>
                                <td class="p-4 font-bold text-xs text-action-primary whitespace-nowrap">
                                    Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    @if($field->is_active)
                                        <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-[#DCFCE7] text-[#166534]">Aktif</span>
                                    @else
                                        <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-[#FEE2E2] text-[#991B1B]">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right space-x-1 whitespace-nowrap">
                                    <!-- Kelola Galeri -->
                                    <button @click="showGalleryModal = true; currentField = {{ json_encode($field->load('images')) }}"
                                            class="px-2 py-1 text-xs font-semibold bg-action-primary/10 text-action-primary rounded-lg hover:bg-action-primary hover:text-white transition-colors">
                                        Galeri ({{ $field->images->count() }})
                                    </button>

                                    <!-- Edit Data -->
                                    <button @click="showModal = true; editMode = true; form = {
                                                id: {{ $field->id }},
                                                gor_id: '{{ $field->gor_id }}',
                                                name: '{{ addslashes($field->name) }}',
                                                type: '{{ $field->type }}',
                                                price_per_hour: {{ (int)$field->price_per_hour }},
                                                is_active: {{ $field->is_active ? 1 : 0 }},
                                                description: '{{ addslashes($field->description ?? '') }}'
                                            }"
                                            class="px-2.5 py-1 text-xs font-bold bg-surface-page border border-border-default rounded-lg hover:bg-border-default transition-colors">
                                        Edit
                                    </button>

                                    <!-- Hapus -->
                                    <form method="POST" action="{{ route('admin.fields.destroy', $field->id) }}" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus lapangan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 text-xs font-bold bg-status-danger-bg text-status-danger-text rounded-lg hover:underline transition-all">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-xs text-text-muted">Belum ada lapangan terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL FORM (Tambah / Edit Lapangan) -->
        <div x-show="showModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" x-cloak>
            <div class="bg-surface-card rounded-2xl max-w-lg w-full p-6 space-y-4 border border-border-default shadow-lg max-h-[90vh] overflow-y-auto" @click.away="showModal = false">
                <div class="flex justify-between items-center border-b border-border-default pb-2">
                    <h3 class="text-base font-bold text-text-default" x-text="editMode ? 'Edit Data Lapangan' : 'Tambah Lapangan Baru'"></h3>
                    <button @click="showModal = false" class="text-text-muted hover:text-text-default font-bold text-base">&times;</button>
                </div>

                <form :action="editMode ? '/admin/fields/' + form.id : '{{ route('admin.fields.store') }}'" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Lokasi GOR</label>
                        <select name="gor_id" x-model="form.gor_id" required class="w-full px-3 py-2 text-xs rounded-xl border border-border-default outline-none bg-surface-card">
                            @foreach($gors as $gor)
                                <option value="{{ $gor->id }}">{{ $gor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Nama Lapangan</label>
                        <input type="text" name="name" x-model="form.name" required placeholder="cth: Lapangan A (Vinyl)" class="w-full px-3 py-2 text-xs rounded-xl border border-border-default outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-text-muted mb-1">Tipe Jenis Lapangan</label>
                            <select name="type" x-model="form.type" required class="w-full px-3 py-2 text-xs rounded-xl border border-border-default outline-none bg-surface-card capitalize">
                                <option value="vinyl">Vinyl</option>
                                <option value="sintetis">Rumput Sintetis</option>
                                <option value="semen">Semen</option>
                                <option value="interlock">Interlock</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-text-muted mb-1">Harga / Jam (Rp)</label>
                            <input type="number" name="price_per_hour" x-model="form.price_per_hour" required class="w-full px-3 py-2 text-xs rounded-xl border border-border-default outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Deskripsi Lapangan (Opsional)</label>
                        <textarea name="description" x-model="form.description" rows="2" placeholder="Informasi ukuran, penerangan, atau fasilitas khusus..." class="w-full px-3 py-2 text-xs rounded-xl border border-border-default outline-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Upload Foto Lapangan (Max 8 Foto, WebP/JPG/PNG)</label>
                        <input type="file" name="images[]" multiple accept="image/*" class="w-full text-xs text-text-muted file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-action-primary file:text-white cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Status Operasional</label>
                        <select name="is_active" x-model="form.is_active" class="w-full px-3 py-2 text-xs rounded-xl border border-border-default outline-none bg-surface-card">
                            <option value="1">Aktif (Siap Disewa)</option>
                            <option value="0">Nonaktif (Maintenance)</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-border-default">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-bold bg-surface-page rounded-xl border border-border-default hover:bg-border-default">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold bg-action-primary text-white rounded-xl hover:bg-action-primary-hover" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Lapangan'"></button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL KELOLA GALERI FOTO -->
        <div x-show="showGalleryModal" class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4" x-cloak>
            <div class="bg-surface-card rounded-2xl max-w-2xl w-full p-6 space-y-4 border border-border-default shadow-xl max-h-[90vh] overflow-y-auto" @click.away="showGalleryModal = false">
                <div class="flex justify-between items-center border-b border-border-default pb-3">
                    <div>
                        <h3 class="text-base font-bold text-text-default">Galeri Foto Lapangan</h3>
                        <p class="text-xs text-text-muted" x-text="currentField ? currentField.name : ''"></p>
                    </div>
                    <button @click="showGalleryModal = false" class="text-text-muted hover:text-text-default font-bold text-base">&times;</button>
                </div>

                <!-- Form Upload Foto Tambahan -->
                <form :action="'/admin/fields/' + (currentField ? currentField.id : '')" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 bg-surface-page p-3 rounded-xl border border-border-default">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="name" :value="currentField ? currentField.name : ''">
                    <input type="hidden" name="type" :value="currentField ? currentField.type : ''">
                    <input type="hidden" name="price_per_hour" :value="currentField ? currentField.price_per_hour : 0">
                    <input type="hidden" name="is_active" :value="currentField ? (currentField.is_active ? 1 : 0) : 1">

                    <input type="file" name="images[]" multiple accept="image/*" required class="flex-1 text-xs text-text-muted file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-action-primary file:text-white cursor-pointer">
                    <button type="submit" class="px-3 py-1.5 bg-action-primary text-white text-xs font-bold rounded-lg hover:bg-action-primary-hover shadow-xs">Tambah</button>
                </form>

                <!-- Grid Preview Foto Terunggah -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2">
                    <template x-if="currentField && currentField.images.length > 0">
                        <template x-for="img in currentField.images" :key="img.id">
                            <div class="relative group rounded-xl overflow-hidden border border-border-default bg-surface-page flex flex-col items-center">
                                <img :src="'/storage/' + img.path" class="w-full h-32 object-cover">

                                <div class="p-2 w-full flex items-center justify-between bg-surface-card border-t border-border-default">
                                    <form :action="'/admin/fields/' + currentField.id + '/images/' + img.id + '/set-cover'" method="POST">
                                        @csrf
                                        <button type="submit"
                                                :class="img.is_cover ? 'bg-action-primary text-white' : 'bg-surface-page text-text-muted hover:text-text-default border border-border-default'"
                                                class="px-2 py-0.5 text-[10px] font-bold rounded-md transition-colors">
                                            <span x-text="img.is_cover ? 'Cover Utama' : 'Jadikan Cover'"></span>
                                        </button>
                                    </form>

                                    <form :action="'/admin/images/' + img.id" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-status-danger-text text-xs hover:underline font-bold px-1">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </template>
                    </template>

                    <template x-if="!currentField || currentField.images.length === 0">
                        <div class="col-span-full py-8 text-center text-xs text-text-muted">
                            Belum ada foto galeri untuk lapangan ini.
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>
</x-layouts.admin>
