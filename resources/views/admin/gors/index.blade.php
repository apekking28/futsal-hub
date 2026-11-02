<x-layouts.admin headerTitle="Manajemen GOR">
    <div class="space-y-6" x-data="{ showModal: false, editMode: false, form: { id: null, district_id: '', name: '', address: '', phone_number: '', is_active: 1 } }">

        <!-- Header Actions -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold">Daftar GOR (Gelanggang Olahraga)</h2>
                <p class="text-xs text-text-muted">Kelola lokasi GOR, wilayah/kecamatan, kontak, dan status operasional.</p>
            </div>
            <button @click="showModal = true; editMode = false; form = { id: null, district_id: '{{ $districts->first()?->id }}', name: '', address: '', phone_number: '', is_active: 1 }"
                    class="px-4 py-2 bg-action-primary hover:bg-action-primary-hover text-white font-bold text-xs rounded-lg transition-colors">
                + Tambah GOR
            </button>
        </div>

        <!-- Data Table -->
        <div class="bg-surface-card border border-border-default rounded-xl overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-page border-b border-border-default text-xs font-bold uppercase text-text-muted">
                        <th class="p-4">Nama GOR</th>
                        <th class="p-4">Wilayah / Kecamatan</th>
                        <th class="p-4">Telepon</th>
                        <th class="p-4">Jumlah Lapangan</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-default text-sm">
                    @forelse($gors as $gor)
                        <tr class="hover:bg-surface-page/50">
                            <td class="p-4">
                                <p class="font-bold text-text-default">{{ $gor->name }}</p>
                                <p class="text-xs text-text-muted line-clamp-1">{{ $gor->address }}</p>
                            </td>
                            <td class="p-4 font-semibold text-xs"><span class="px-2 py-0.5 rounded bg-surface-page border border-border-default">{{ $gor->district->name }}</span></td>
                            <td class="p-4 font-mono text-xs">{{ $gor->phone_number }}</td>
                            <td class="p-4 font-bold text-action-primary">{{ $gor->fields->count() }} Lapangan</td>
                            <td class="p-4">
                                @if($gor->is_active)
                                    <span class="px-2 py-1 text-[10px] font-bold rounded-full bg-[#DCFCE7] text-[#166534]">Aktif</span>
                                @else
                                    <span class="px-2 py-1 text-[10px] font-bold rounded-full bg-[#FEE2E2] text-[#991B1B]">Nonaktif</span>
                                @endif
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <button @click="showModal = true; editMode = true; form = { id: {{ $gor->id }}, district_id: '{{ $gor->district_id }}', name: '{{ addslashes($gor->name) }}', address: '{{ addslashes($gor->address) }}', phone_number: '{{ $gor->phone_number }}', is_active: {{ $gor->is_active ? 1 : 0 }} }"
                                        class="px-2.5 py-1 text-xs font-bold bg-surface-page border border-border-default rounded hover:bg-border-default">
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('admin.gors.destroy', $gor->id) }}" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus GOR ini beserta seluruh lapangannya?')">
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
                            <td colspan="6" class="p-8 text-center text-xs text-text-muted">Belum ada GOR terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modal Form (Tambah / Edit GOR) -->
        <div x-show="showModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" x-cloak>
            <div class="bg-surface-card rounded-2xl max-w-md w-full p-6 space-y-4 border border-border-default shadow-lg" @click.away="showModal = false">
                <h3 class="text-lg font-bold" x-text="editMode ? 'Edit Data GOR' : 'Tambah GOR Baru'"></h3>

                <form :action="editMode ? '/admin/gors/' + form.id : '{{ route('admin.gors.store') }}'" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Wilayah / Kecamatan</label>
                        <select name="district_id" x-model="form.district_id" required class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none">
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Nama GOR</label>
                        <input type="text" name="name" x-model="form.name" required placeholder="cth: Cengkareng Futsal Center" class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="phone_number" x-model="form.phone_number" required placeholder="cth: 08123456789" class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Alamat Lengkap</label>
                        <textarea name="address" x-model="form.address" rows="3" required placeholder="Jl. Raya Utama No. 12..." class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Status Operasional</label>
                        <select name="is_active" x-model="form.is_active" class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none">
                            <option value="1">Aktif (Operasional)</option>
                            <option value="0">Nonaktif (Tutup sementara)</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-bold bg-surface-page rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold bg-action-primary text-white rounded-lg" x-text="editMode ? 'Simpan Perubahan' : 'Tambah GOR'"></button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts.admin>
