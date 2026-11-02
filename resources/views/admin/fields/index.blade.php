<x-layouts.admin headerTitle="Manajemen Lapangan">
    <div class="space-y-6" x-data="{ showModal: false, editMode: false, form: { id: null, gor_id: '', name: '', type: 'Vinyl', price_per_hour: 0, is_active: 1 } }">

        <!-- Header Actions (Responsive Flex Layout) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-text-default">Daftar Lapangan</h2>
                <p class="text-xs text-text-muted">Kelola harga sewa, tipe lantai/rumput, dan status operasional lapangan.</p>
            </div>
            <button @click="showModal = true; editMode = false; form = { id: null, gor_id: '{{ $gors->first()?->id }}', name: '', type: 'Vinyl', price_per_hour: 100000, is_active: 1 }"
                    class="w-full sm:w-auto px-4 py-2 bg-action-primary hover:bg-action-primary-hover text-white font-bold text-xs rounded-xl transition-colors shadow-sm text-center">
                + Tambah Lapangan
            </button>
        </div>

        <!-- TABEL STABIL RESPONSIF (Horizontal Scroll Isolasi) -->
        <div class="bg-surface-card border border-border-default rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left border-collapse min-w-[750px] table-fixed">
                    <thead>
                        <tr class="bg-surface-page border-b border-border-default text-xs font-bold uppercase text-text-muted">
                            <th class="p-4 w-48">GOR / Wilayah</th>
                            <th class="p-4 w-44">Nama Lapangan</th>
                            <th class="p-4 w-36">Tipe Jenis</th>
                            <th class="p-4 w-40">Harga / Jam</th>
                            <th class="p-4 w-28">Status</th>
                            <th class="p-4 w-36 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-default text-sm">
                        @forelse($fields as $field)
                            <tr class="hover:bg-surface-page/50 transition-colors">
                                <td class="p-4 font-semibold text-xs truncate">{{ $field->gor->name }}</td>
                                <td class="p-4 font-bold text-xs text-text-default truncate">{{ $field->name }}</td>
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
                                <td class="p-4 text-right space-x-1.5 whitespace-nowrap">
                                    <button @click="showModal = true; editMode = true; form = { id: {{ $field->id }}, gor_id: '{{ $field->gor_id }}', name: '{{ addslashes($field->name) }}', type: '{{ $field->type }}', price_per_hour: {{ (int)$field->price_per_hour }}, is_active: {{ $field->is_active ? 1 : 0 }} }"
                                            class="px-2.5 py-1 text-xs font-bold bg-surface-page border border-border-default rounded-lg hover:bg-border-default transition-colors">
                                        Edit
                                    </button>
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
                                <td colspan="6" class="p-8 text-center text-xs text-text-muted">Belum ada lapangan terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Form (Tambah / Edit Lapangan) - Responsive Layout -->
        <div x-show="showModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" x-cloak>
            <div class="bg-surface-card rounded-2xl max-w-md w-full p-6 space-y-4 border border-border-default shadow-lg max-h-[90vh] overflow-y-auto" @click.away="showModal = false">
                <div class="flex justify-between items-center border-b border-border-default pb-2">
                    <h3 class="text-base font-bold text-text-default" x-text="editMode ? 'Edit Data Lapangan' : 'Tambah Lapangan Baru'"></h3>
                    <button @click="showModal = false" class="text-text-muted hover:text-text-default font-bold text-base">&times;</button>
                </div>

                <form :action="editMode ? '/admin/fields/' + form.id : '{{ route('admin.fields.store') }}'" method="POST" class="space-y-4">
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
                            <label class="block text-xs font-bold text-text-muted mb-1">Tipe Lapangan</label>
                            <select name="type" x-model="form.type" required class="w-full px-3 py-2 text-xs rounded-xl border border-border-default outline-none bg-surface-card">
                                <option value="Vinyl">Vinyl</option>
                                <option value="Rumput Sintetis">Rumput Sintetis</option>
                                <option value="Semen">Semen</option>
                                <option value="Interlock">Interlock</option>
                                <option value="Parquet/Kayu">Parquet / Kayu</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-text-muted mb-1">Harga / Jam (Rp)</label>
                            <input type="number" name="price_per_hour" x-model="form.price_per_hour" required class="w-full px-3 py-2 text-xs rounded-xl border border-border-default outline-none">
                        </div>
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

    </div>
</x-layouts.admin>
