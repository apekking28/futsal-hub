<x-layouts.admin headerTitle="Manajemen Lapangan">
    <div class="space-y-6" x-data="{ showModal: false, editMode: false, form: { id: null, gor_id: '', name: '', type: 'Vinyl', price_per_hour: 0, is_active: 1 } }">

        <!-- Header Actions -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold">Daftar Lapangan</h2>
                <p class="text-xs text-text-muted">Kelola harga sewa, tipe lantai/rumput, dan status operasional lapangan.</p>
            </div>
            <button @click="showModal = true; editMode = false; form = { id: null, gor_id: '{{ $gors->first()?->id }}', name: '', type: 'Vinyl', price_per_hour: 100000, is_active: 1 }"
                    class="px-4 py-2 bg-action-primary hover:bg-action-primary-hover text-white font-bold text-xs rounded-lg transition-colors">
                + Tambah Lapangan
            </button>
        </div>

        <!-- Data Table -->
        <div class="bg-surface-card border border-border-default rounded-xl overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-page border-b border-border-default text-xs font-bold uppercase text-text-muted">
                        <th class="p-4">GOR / Wilayah</th>
                        <th class="p-4">Nama Lapangan</th>
                        <th class="p-4">Tipe Jenis</th>
                        <th class="p-4">Harga / Jam</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-default text-sm">
                    @forelse($fields as $field)
                        <tr class="hover:bg-surface-page/50">
                            <td class="p-4 font-semibold">{{ $field->gor->name }}</td>
                            <td class="p-4 font-bold">{{ $field->name }}</td>
                            <td class="p-4"><span class="px-2 py-0.5 rounded bg-surface-page border border-border-default text-xs font-semibold capitalize">{{ $field->type }}</span></td>
                            <td class="p-4 font-bold text-action-primary">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</td>
                            <td class="p-4">
                                @if($field->is_active)
                                    <span class="px-2 py-1 text-[10px] font-bold rounded-full bg-[#DCFCE7] text-[#166534]">Aktif</span>
                                @else
                                    <span class="px-2 py-1 text-[10px] font-bold rounded-full bg-[#FEE2E2] text-[#991B1B]">Nonaktif</span>
                                @endif
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <button @click="showModal = true; editMode = true; form = { id: {{ $field->id }}, gor_id: '{{ $field->gor_id }}', name: '{{ addslashes($field->name) }}', type: '{{ $field->type }}', price_per_hour: {{ (int)$field->price_per_hour }}, is_active: {{ $field->is_active ? 1 : 0 }} }"
                                        class="px-2.5 py-1 text-xs font-bold bg-surface-page border border-border-default rounded hover:bg-border-default">
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('admin.fields.destroy', $field->id) }}" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus lapangan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 text-xs font-bold bg-status-danger-bg text-status-danger-text rounded hover:underline">
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

        <!-- Modal Form (Tambah / Edit Lapangan) -->
        <div x-show="showModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" x-cloak>
            <div class="bg-surface-card rounded-2xl max-w-md w-full p-6 space-y-4 border border-border-default shadow-lg" @click.away="showModal = false">
                <h3 class="text-lg font-bold" x-text="editMode ? 'Edit Data Lapangan' : 'Tambah Lapangan Baru'"></h3>

                <form :action="editMode ? '/admin/fields/' + form.id : '{{ route('admin.fields.store') }}'" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Lokasi GOR</label>
                        <select name="gor_id" x-model="form.gor_id" required class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none">
                            @foreach($gors as $gor)
                                <option value="{{ $gor->id }}">{{ $gor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Nama Lapangan</label>
                        <input type="text" name="name" x-model="form.name" required placeholder="cth: Lapangan A (Vinyl)" class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-text-muted mb-1">Tipe Lapangan</label>
                            <select name="type" x-model="form.type" required class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none">
                                <option value="Vinyl">Vinyl</option>
                                <option value="Rumput Sintetis">Rumput Sintetis</option>
                                <option value="Semen">Semen</option>
                                <option value="Interlock">Interlock</option>
                                <option value="Parquet/Kayu">Parquet / Kayu</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-text-muted mb-1">Harga / Jam (Rp)</label>
                            <input type="number" name="price_per_hour" x-model="form.price_per_hour" required class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Status Operasional</label>
                        <select name="is_active" x-model="form.is_active" class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none">
                            <option value="1">Aktif (Siap Disewa)</option>
                            <option value="0">Nonaktif (Maintenance)</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-bold bg-surface-page rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold bg-action-primary text-white rounded-lg" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Lapangan'"></button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts.admin>
