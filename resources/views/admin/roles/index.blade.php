<x-layouts.admin headerTitle="Manajemen Role & Hak Akses">
    <div class="space-y-6" x-data="{
        showModal: false,
        editMode: false,
        form: { id: null, name: '', is_core: false, permissions: [] },

        // Helper Alpine untuk Toggle Select All Per Modul
        toggleGroup(permsInGroup) {
            let allSelected = permsInGroup.every(p => this.form.permissions.includes(p));
            if (allSelected) {
                // Hapus semua dari grup ini
                this.form.permissions = this.form.permissions.filter(p => !permsInGroup.includes(p));
            } else {
                // Tambah semua dari grup ini
                permsInGroup.forEach(p => {
                    if (!this.form.permissions.includes(p)) this.form.permissions.push(p);
                });
            }
        },

        isGroupSelected(permsInGroup) {
            return permsInGroup.length > 0 && permsInGroup.every(p => this.form.permissions.includes(p));
        }
    }">

        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-text-default">Daftar Role Access (RBAC)</h2>
                <p class="text-xs text-text-muted">Kelola struktur peran pengguna (Role) dan atur checklist hak akses (Permissions).</p>
            </div>
            <button @click="showModal = true; editMode = false; form = { id: null, name: '', is_core: false, permissions: [] }"
                    class="w-full sm:w-auto px-4 py-2 bg-action-primary hover:bg-action-primary-hover text-white font-bold text-xs rounded-xl transition-colors shadow-xs text-center cursor-pointer">
                + Tambah Role Baru
            </button>
        </div>

        <!-- Filter & Search Bar -->
        <div class="flex flex-col sm:flex-row justify-between gap-3">
            <form method="GET" action="{{ route('admin.roles.index') }}" class="flex flex-col sm:flex-row flex-1 gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Role..." class="w-full sm:max-w-xs px-3.5 py-2 text-xs rounded-xl border border-border-default outline-none focus:ring-2 focus:ring-action-primary bg-surface-card text-text-default">
                @if(request()->has('search'))
                    <a href="{{ route('admin.roles.index') }}" class="px-3 py-2 text-xs font-bold text-status-danger-text hover:underline flex items-center">
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>

        <!-- TABEL ROLES -->
        <div class="bg-surface-card border border-border-default rounded-xl shadow-xs overflow-hidden space-y-4">
            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left border-collapse min-w-[950px] table-fixed">
                    <thead>
                        <tr class="bg-surface-page border-b border-border-default text-xs font-bold uppercase text-text-muted sticky top-0 z-10">
                            <!-- 1. Header Nomor Diganti 'No' -->
                            <th class="p-4 w-14 text-center">No</th>
                            <th class="p-4 w-44">Nama Role</th>
                            <th class="p-4 w-32">Total Pengguna</th>
                            <th class="p-4 w-80">Izin Akses Tercentang (Permissions)</th>
                            <th class="p-4 w-44">Riwayat Waktu</th>
                            <th class="p-4 w-36 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-default text-sm">
                        @forelse($roles as $role)
                            <tr class="hover:bg-surface-page/50 transition-colors">
                                <!-- 2. Nomor Urut Dinamis -->
                                <td class="p-4 text-center font-bold text-xs text-text-muted">
                                    {{ $loop->iteration + $roles->firstItem() - 1 }}
                                </td>

                                <!-- Nama Role -->
                                <td class="p-4">
                                    <p class="font-bold text-text-default text-xs uppercase">{{ $role->name }}</p>
                                    @if(in_array($role->name, ['super-admin', 'admin-gor', 'kasir']))
                                        <span class="inline-block mt-0.5 text-[9px] font-bold text-action-primary bg-action-primary/10 px-2 py-0.5 rounded-full">System Core Role</span>
                                    @endif
                                </td>

                                <!-- Total Pengguna -->
                                <td class="p-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-surface-page border border-border-default text-text-default">
                                        {{ $role->users_count }} User
                                    </span>
                                </td>

                                <!-- Permissions -->
                                <td class="p-4">
                                    <div class="flex flex-wrap gap-1 max-h-20 overflow-y-auto">
                                        @if($role->name === 'super-admin')
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-[#FEF3C7] text-[#92400E] border border-[#F59E0B]/20 uppercase">
                                                Akses Penuh (Full Super Admin)
                                            </span>
                                        @else
                                            @forelse($role->permissions as $perm)
                                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-surface-page border border-border-default text-text-muted">
                                                    {{ $perm->name }}
                                                </span>
                                            @empty
                                                <span class="text-[11px] text-text-muted italic">Tidak ada izin spesifik (Kosong)</span>
                                            @endforelse
                                        @endif
                                    </div>
                                </td>

                                <!-- Tanggal Dibuat & Tanggal Di-update -->
                                <td class="p-4 space-y-1 text-xs">
                                    <div>
                                        <span class="text-[10px] font-semibold text-text-muted block">Dibuat:</span>
                                        <span class="font-medium text-text-default">{{ $role->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-semibold text-text-muted block">Di-update:</span>
                                        <span class="font-medium text-text-default">{{ $role->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </td>

                                <!-- Aksi -->
                                <td class="p-4 text-right space-x-1.5 whitespace-nowrap">
                                    <button @click="showModal = true; editMode = true; form = {
                                                id: {{ $role->id }},
                                                name: '{{ $role->name }}',
                                                is_core: {{ in_array($role->name, ['super-admin', 'admin-gor', 'kasir']) ? 'true' : 'false' }},
                                                permissions: {{ json_encode($role->permissions->pluck('name')) }}
                                            }"
                                            class="px-2.5 py-1 text-xs font-bold bg-surface-page border border-border-default rounded-lg hover:bg-border-default text-text-default transition-colors cursor-pointer">
                                        Kelola Izin
                                    </button>

                                    @if(!in_array($role->name, ['super-admin', 'admin-gor', 'kasir']))
                                        <form method="POST" action="{{ route('admin.roles.destroy', $role->id) }}" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus role ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 text-xs font-bold bg-status-danger-bg text-status-danger-text rounded-lg hover:underline transition-all cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-xs text-text-muted">Belum ada role terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- 3. PAGINATION ANGKA LARAVEL DEFAULT -->
            @if(method_exists($roles, 'hasPages') && $roles->hasPages())
                <div class="p-4 border-t border-border-default overflow-x-auto">
                    {{ $roles->links() }}
                </div>
            @endif
        </div>

        <!-- MODAL CHECKLIST PERMISSION PER MODUL -->
        <div x-show="showModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" x-cloak>
            <div class="bg-surface-card rounded-2xl max-w-2xl w-full p-6 space-y-4 border border-border-default shadow-xl max-h-[90vh] overflow-y-auto" @click.away="showModal = false">
                <div class="flex justify-between items-center border-b border-border-default pb-3">
                    <div>
                        <h3 class="text-base font-bold text-text-default" x-text="editMode ? 'Kelola Hak Akses Role' : 'Tambah Role Baru'"></h3>
                        <p class="text-xs text-text-muted">Centang izin fitur yang boleh diakses oleh role ini.</p>
                    </div>
                    <button @click="showModal = false" class="text-text-muted hover:text-text-default font-bold text-base cursor-pointer">&times;</button>
                </div>

                <form :action="editMode ? '/admin/roles/' + form.id : '{{ route('admin.roles.store') }}'" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1.5">Nama Role</label>
                        <input type="text" name="name" x-model="form.name" :disabled="form.is_core" required placeholder="cth: manager-operasional" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-border-default outline-none bg-surface-card text-text-default disabled:opacity-60 uppercase">
                        <template x-if="form.is_core">
                            <span class="text-[10px] text-text-muted italic mt-1 block">Nama role bawaan sistem tidak dapat diubah (hanya checklist izinnya).</span>
                        </template>
                    </div>

                    <!-- KELOMPOK CHECKLIST PERMISSIONS -->
                    <div class="space-y-3 pt-2">
                        <label class="block text-xs font-bold text-text-default">Daftar Modul & Izin Fitur (Checklist)</label>

                        @forelse($groupedPermissions as $groupName => $perms)
                            @php
                                $permArray = $perms->pluck('name')->toArray();
                            @endphp
                            <div class="bg-surface-page border border-border-default rounded-xl p-3.5 space-y-2.5">
                                <div class="flex items-center justify-between border-b border-border-default/60 pb-2">
                                    <span class="text-xs font-bold text-action-primary uppercase tracking-wider">
                                        Modul: {{ $groupName }}
                                    </span>
                                    <button type="button"
                                            @click="toggleGroup({{ json_encode($permArray) }})"
                                            class="text-[11px] font-bold text-text-muted hover:text-action-primary cursor-pointer">
                                        <span x-text="isGroupSelected({{ json_encode($permArray) }}) ? 'Batal Pilih Semua' : 'Pilih Semua'"></span>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach($perms as $permission)
                                        <label class="flex items-center gap-2.5 p-1.5 rounded-lg hover:bg-surface-card transition-colors cursor-pointer select-none">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                   x-model="form.permissions"
                                                   class="w-4 h-4 rounded border-border-default text-action-primary focus:ring-action-primary cursor-pointer">
                                            <span class="text-xs text-text-default font-medium leading-tight">
                                                {{ $permission->name }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="p-4 bg-surface-page rounded-xl border border-border-default text-center text-xs text-text-muted">
                                Belum ada permission terdaftar di database. Tambahkan data di menu <strong>Manajemen Permission</strong> terlebih dahulu.
                            </div>
                        @endforelse
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-border-default">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-bold bg-surface-page rounded-xl border border-border-default hover:bg-border-default text-text-default transition-colors cursor-pointer">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold bg-action-primary hover:bg-action-primary-hover text-white rounded-xl shadow-xs transition-colors cursor-pointer" x-text="editMode ? 'Simpan Hak Akses' : 'Tambah Role'"></button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts.admin>
