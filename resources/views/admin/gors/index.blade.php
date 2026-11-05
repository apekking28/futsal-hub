<x-layouts.admin headerTitle="Manajemen GOR">
    <div class="space-y-6" x-data="{
        showModal: false,
        showGalleryModal: false,
        editMode: false,
        currentGor: null,
        form: {
            id: null,
            district_id: '',
            name: '',
            address: '',
            description: '',
            maps_url: '',
            phone_number: '',
            open_time: '08:00',
            close_time: '23:00',
            bank_name: '',
            bank_account_number: '',
            bank_account_holder: '',
            is_active: 1
        }
    }">

        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-text-default">Daftar GOR (Gelanggang Olahraga)</h2>
                <p class="text-xs text-text-muted">Kelola lokasi GOR, galeri foto, jam operasional, rekening bank, dan status.</p>
            </div>
            <button @click="showModal = true; editMode = false; form = {
                        id: null,
                        district_id: '{{ $districts->first()?->id }}',
                        name: '',
                        address: '',
                        description: '',
                        maps_url: '',
                        phone_number: '',
                        open_time: '08:00',
                        close_time: '23:00',
                        bank_name: '',
                        bank_account_number: '',
                        bank_account_holder: '',
                        is_active: 1
                    }"
                    class="w-full sm:w-auto px-4 py-2 bg-action-primary hover:bg-action-primary-hover text-white font-bold text-xs rounded-xl transition-colors shadow-xs text-center cursor-pointer">
                + Tambah GOR
            </button>
        </div>

        <!-- Filter & Search Bar -->
        <div class="flex flex-col sm:flex-row justify-between gap-3">
            <form method="GET" action="{{ route('admin.gors.index') }}" class="flex flex-col sm:flex-row flex-1 gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama GOR / Alamat / Telepon..." class="w-full sm:max-w-xs px-3.5 py-2 text-xs rounded-xl border border-border-default outline-none focus:ring-2 focus:ring-action-primary bg-surface-card text-text-default">
                <select name="status" onchange="this.form.submit()" class="w-full sm:w-auto px-3.5 py-2 text-xs rounded-xl border border-border-default outline-none bg-surface-card font-semibold text-text-default">
                    <option value="">Semua Status Operasional</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.gors.index') }}" class="px-3 py-2 text-xs font-bold text-status-danger-text hover:underline flex items-center">
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>

        <!-- TABEL GOR LENGKAP -->
        <div class="bg-surface-card border border-border-default rounded-xl shadow-xs overflow-hidden space-y-4">
            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left border-collapse min-w-[1150px] table-fixed">
                    <thead>
                        <tr class="bg-surface-page border-b border-border-default text-xs font-bold uppercase text-text-muted sticky top-0 z-10">
                            <!-- 1. Header Nomor Diganti 'No' -->
                            <th class="p-4 w-14 text-center">No</th>
                            <th class="p-4 w-16">Foto</th>
                            <th class="p-4 w-48">Nama & Wilayah GOR</th>
                            <th class="p-4 w-48">Alamat & Maps</th>
                            <th class="p-4 w-36">Jam Operasional</th>
                            <th class="p-4 w-44">Info Bank / Rekening</th>
                            <th class="p-4 w-28">Status</th>
                            <th class="p-4 w-40">Riwayat Waktu</th>
                            <th class="p-4 w-40 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-default text-sm">
                        @forelse($gors as $gor)
                            <tr class="hover:bg-surface-page/50 transition-colors">
                                <!-- 2. Nomor Urut Dinamis -->
                                <td class="p-4 text-center font-bold text-xs text-text-muted">
                                    {{ $loop->iteration + $gors->firstItem() - 1 }}
                                </td>

                                <!-- Preview Image / Cover -->
                                <td class="p-4">
                                    <div class="w-12 h-12 rounded-lg bg-surface-page border border-border-default overflow-hidden flex items-center justify-center shrink-0">
                                        @if($gor->cover_image)
                                            <img src="{{ asset('storage/' . $gor->cover_image->path) }}" alt="{{ $gor->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-[9px] text-text-muted font-semibold">No Photo</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Nama & Wilayah GOR -->
                                <td class="p-4">
                                    <p class="font-bold text-text-default text-xs truncate">{{ $gor->name }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded-lg bg-surface-page border border-border-default text-[10px] font-semibold text-text-muted">
                                        {{ $gor->district->name ?? 'Wilayah' }}
                                    </span>
                                </td>

                                <!-- Alamat & Maps -->
                                <td class="p-4">
                                    <p class="text-[11px] text-text-default leading-tight line-clamp-2" title="{{ $gor->address }}">{{ $gor->address }}</p>
                                    @if($gor->maps_url)
                                        <a href="{{ $gor->maps_url }}" target="_blank" class="text-[10px] text-action-primary font-bold hover:underline inline-flex items-center gap-0.5 mt-1">
                                            Lihat di Maps &rarr;
                                        </a>
                                    @endif
                                </td>

                                <!-- Jam Buka & Lapangan -->
                                <td class="p-4">
                                    <p class="font-mono text-xs text-text-default font-semibold">
                                        {{ substr($gor->open_time ?? '08:00', 0, 5) }} - {{ substr($gor->close_time ?? '23:00', 0, 5) }}
                                    </p>
                                    <p class="text-[11px] text-action-primary font-bold mt-0.5">{{ $gor->fields->count() }} Lapangan Tersedia</p>
                                </td>

                                <!-- Info Bank -->
                                <td class="p-4">
                                    @if($gor->bank_name && $gor->bank_account_number)
                                        <p class="text-xs font-bold text-text-default uppercase">{{ $gor->bank_name }} - {{ $gor->bank_account_number }}</p>
                                        <p class="text-[11px] text-text-muted truncate">a.n {{ $gor->bank_account_holder ?? '-' }}</p>
                                    @else
                                        <span class="text-[11px] text-text-muted italic">Belum diset</span>
                                    @endif
                                </td>

                                <!-- Status Operasional -->
                                <td class="p-4 whitespace-nowrap">
                                    @if($gor->is_active)
                                        <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-[#DCFCE7] text-[#166534]">Aktif</span>
                                    @else
                                        <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-[#FEE2E2] text-[#991B1B]">Nonaktif</span>
                                    @endif
                                </td>

                                <!-- Tanggal Dibuat & Tanggal Di-update -->
                                <td class="p-4 space-y-1 text-xs">
                                    <div>
                                        <span class="text-[10px] font-semibold text-text-muted block">Dibuat:</span>
                                        <span class="font-medium text-text-default">{{ $gor->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-semibold text-text-muted block">Di-update:</span>
                                        <span class="font-medium text-text-default">{{ $gor->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </td>

                                <!-- Aksi -->
                                <td class="p-4 text-right space-x-1 whitespace-nowrap">
                                    <!-- Kelola Galeri Foto -->
                                    <button @click="showGalleryModal = true; currentGor = {{ json_encode($gor->load('images')) }}"
                                            class="px-2 py-1 text-xs font-semibold bg-action-primary/10 text-action-primary rounded-lg hover:bg-action-primary hover:text-white transition-colors cursor-pointer">
                                        Foto ({{ $gor->images->count() }})
                                    </button>

                                    <!-- Edit -->
                                    <button @click="showModal = true; editMode = true; form = {
                                                id: {{ $gor->id }},
                                                district_id: '{{ $gor->district_id }}',
                                                name: '{{ addslashes($gor->name) }}',
                                                address: '{{ addslashes($gor->address) }}',
                                                description: '{{ addslashes($gor->description ?? '') }}',
                                                maps_url: '{{ addslashes($gor->maps_url ?? '') }}',
                                                phone_number: '{{ $gor->phone_number }}',
                                                open_time: '{{ substr($gor->open_time ?? '08:00', 0, 5) }}',
                                                close_time: '{{ substr($gor->close_time ?? '23:00', 0, 5) }}',
                                                bank_name: '{{ addslashes($gor->bank_name ?? '') }}',
                                                bank_account_number: '{{ addslashes($gor->bank_account_number ?? '') }}',
                                                bank_account_holder: '{{ addslashes($gor->bank_account_holder ?? '') }}',
                                                is_active: {{ $gor->is_active ? 1 : 0 }}
                                            }"
                                            class="px-2.5 py-1 text-xs font-bold bg-surface-page border border-border-default rounded-lg hover:bg-border-default text-text-default transition-colors cursor-pointer">
                                        Edit
                                    </button>

                                    <!-- Hapus -->
                                    <form method="POST" action="{{ route('admin.gors.destroy', $gor->id) }}" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus GOR ini beserta seluruh lapangannya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 text-xs font-bold bg-status-danger-bg text-status-danger-text rounded-lg hover:underline transition-all cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-8 text-center text-xs text-text-muted">Belum ada GOR terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- 3. PAGINATION ANGKA LARAVEL DEFAULT -->
            @if(method_exists($gors, 'hasPages') && $gors->hasPages())
                <div class="p-4 border-t border-border-default overflow-x-auto">
                    {{ $gors->links() }}
                </div>
            @endif
        </div>

        <!-- MODAL FORM (Tambah / Edit GOR) -->
        <div x-show="showModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" x-cloak>
            <div class="bg-surface-card rounded-2xl max-w-lg w-full p-6 space-y-4 border border-border-default shadow-xl max-h-[90vh] overflow-y-auto" @click.away="showModal = false">
                <div class="flex justify-between items-center border-b border-border-default pb-3">
                    <h3 class="text-base font-bold text-text-default" x-text="editMode ? 'Edit Data GOR' : 'Tambah GOR Baru'"></h3>
                    <button @click="showModal = false" class="text-text-muted hover:text-text-default font-bold text-base cursor-pointer">&times;</button>
                </div>

                <form :action="editMode ? '/admin/gors/' + form.id : '{{ route('admin.gors.store') }}'" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-text-muted mb-1.5">Wilayah / Kecamatan</label>
                            <select name="district_id" x-model="form.district_id" required class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-border-default outline-none bg-surface-card text-text-default">
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-text-muted mb-1.5">Nama GOR</label>
                            <input type="text" name="name" x-model="form.name" required placeholder="cth: Cengkareng Futsal Center" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-border-default outline-none bg-surface-card text-text-default">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-text-muted mb-1.5">No. Telepon / WhatsApp</label>
                            <input type="text" name="phone_number" x-model="form.phone_number" required placeholder="08xxxxxxxxxx" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-border-default outline-none bg-surface-card text-text-default">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-text-muted mb-1.5">Link Google Maps (URL)</label>
                            <input type="url" name="maps_url" x-model="form.maps_url" placeholder="https://maps.google.com/..." class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-border-default outline-none bg-surface-card text-text-default">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-text-muted mb-1.5">Jam Buka</label>
                            <input type="time" name="open_time" x-model="form.open_time" required class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-border-default outline-none bg-surface-card text-text-default">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-text-muted mb-1.5">Jam Tutup</label>
                            <input type="time" name="close_time" x-model="form.close_time" required class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-border-default outline-none bg-surface-card text-text-default">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1.5">Alamat Lengkap GOR</label>
                        <textarea name="address" x-model="form.address" rows="2" required placeholder="Jl. Raya Utama No. 12..." class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-border-default outline-none bg-surface-card text-text-default"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1.5">Deskripsi GOR (Opsional)</label>
                        <textarea name="description" x-model="form.description" rows="2" placeholder="Fasilitas parkir luas, kantin, musala..." class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-border-default outline-none bg-surface-card text-text-default"></textarea>
                    </div>

                    <!-- Informasi Rekening Bank Transfer -->
                    <div class="p-3 bg-surface-page rounded-xl border border-border-default space-y-3">
                        <span class="block text-xs font-bold text-text-default">Informasi Rekening Bank (Untuk Pembayaran Transfer)</span>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div>
                                <label class="block text-[11px] font-semibold text-text-muted mb-1">Nama Bank</label>
                                <input type="text" name="bank_name" x-model="form.bank_name" placeholder="BCA / Mandiri" class="w-full px-3 py-1.5 text-xs rounded-lg border border-border-default outline-none bg-surface-card text-text-default">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-text-muted mb-1">No. Rekening</label>
                                <input type="text" name="bank_account_number" x-model="form.bank_account_number" placeholder="1234567890" class="w-full px-3 py-1.5 text-xs rounded-lg border border-border-default outline-none bg-surface-card text-text-default">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-text-muted mb-1">Atas Nama</label>
                                <input type="text" name="bank_account_holder" x-model="form.bank_account_holder" placeholder="PT Futsal Indonesia" class="w-full px-3 py-1.5 text-xs rounded-lg border border-border-default outline-none bg-surface-card text-text-default">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1.5">Upload Foto GOR (Max 5 Foto, JPG/PNG/WebP)</label>
                        <input type="file" name="images[]" multiple accept="image/*" class="w-full text-xs text-text-muted file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-action-primary file:text-white cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1.5">Status Operasional</label>
                        <select name="is_active" x-model="form.is_active" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-border-default outline-none bg-surface-card text-text-default">
                            <option value="1">Aktif (Operasional)</option>
                            <option value="0">Nonaktif (Tutup sementara)</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-border-default">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-bold bg-surface-page rounded-xl border border-border-default hover:bg-border-default transition-colors cursor-pointer">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold bg-action-primary hover:bg-action-primary-hover text-white rounded-xl shadow-xs transition-colors cursor-pointer" x-text="editMode ? 'Simpan Perubahan' : 'Tambah GOR'"></button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL GALERI FOTO GOR -->
        <div x-show="showGalleryModal" class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4" x-cloak>
            <div class="bg-surface-card rounded-2xl max-w-2xl w-full p-6 space-y-4 border border-border-default shadow-xl max-h-[90vh] overflow-y-auto" @click.away="showGalleryModal = false">
                <div class="flex justify-between items-center border-b border-border-default pb-3">
                    <div>
                        <h3 class="text-base font-bold text-text-default">Galeri Foto GOR</h3>
                        <p class="text-xs text-text-muted" x-text="currentGor ? currentGor.name : ''"></p>
                    </div>
                    <button @click="showGalleryModal = false" class="text-text-muted hover:text-text-default font-bold text-base cursor-pointer">&times;</button>
                </div>

                <!-- Form Upload Foto Tambahan -->
                <form :action="'/admin/gors/' + (currentGor ? currentGor.id : '') + '/images'" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 bg-surface-page p-3 rounded-xl border border-border-default">
                    @csrf
                    <input type="file" name="images[]" multiple accept="image/*" required class="flex-1 text-xs text-text-muted file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-action-primary file:text-white cursor-pointer">
                    <button type="submit" class="px-3 py-1.5 bg-action-primary text-white text-xs font-bold rounded-lg hover:bg-action-primary-hover shadow-xs cursor-pointer">Tambah Foto</button>
                </form>

                <!-- Grid Preview Foto GOR Terunggah -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2">
                    <template x-if="currentGor && currentGor.images && currentGor.images.length > 0">
                        <template x-for="img in currentGor.images" :key="img.id">
                            <div class="relative group rounded-xl overflow-hidden border border-border-default bg-surface-page flex flex-col items-center">
                                <img :src="'/storage/' + img.path" class="w-full h-32 object-cover">

                                <div class="p-2 w-full flex items-center justify-between bg-surface-card border-t border-border-default">
                                    <form :action="'/admin/gors/' + currentGor.id + '/images/' + img.id + '/set-cover'" method="POST">
                                        @csrf
                                        <button type="submit"
                                                :class="img.is_cover ? 'bg-action-primary text-white' : 'bg-surface-page text-text-muted hover:text-text-default border border-border-default'"
                                                class="px-2 py-0.5 text-[10px] font-bold rounded-md transition-colors cursor-pointer">
                                            <span x-text="img.is_cover ? 'Cover Utama' : 'Jadikan Cover'"></span>
                                        </button>
                                    </form>

                                    <form :action="'/admin/gors/images/' + img.id" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-status-danger-text text-xs hover:underline font-bold px-1 cursor-pointer">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </template>
                    </template>

                    <template x-if="!currentGor || !currentGor.images || currentGor.images.length === 0">
                        <div class="col-span-full py-8 text-center text-xs text-text-muted">
                            Belum ada foto galeri untuk GOR ini.
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>
</x-layouts.admin>
