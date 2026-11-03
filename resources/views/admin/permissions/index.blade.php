<x-layouts.admin headerTitle="Manajemen Permission">
    <div class="space-y-6" x-data="{
        showModal: false,
        editMode: false,
        form: { id: null, name: '' }
    }">

        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-text-default">Daftar Hak Akses (Permissions)</h2>
                <p class="text-xs text-text-muted">Kelola kunci izin fitur spesifik yang dapat diberikan kepada Role.</p>
            </div>
            <button @click="showModal = true; editMode = false; form = { id: null, name: '' }"
                    class="w-full sm:w-auto px-4 py-2 bg-action-primary hover:bg-action-primary-hover text-white font-bold text-xs rounded-xl transition-colors shadow-xs text-center cursor-pointer">
                + Tambah Permission
            </button>
        </div>

        <!-- Filter & Search Bar -->
        <div class="flex flex-col sm:flex-row justify-between gap-3">
            <form method="GET" action="{{ route('admin.permissions.index') }}" class="flex flex-col sm:flex-row flex-1 gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Permission..." class="w-full sm:max-w-xs px-3.5 py-2 text-xs rounded-xl border border-border-default outline-none focus:ring-2 focus:ring-action-primary bg-surface-card text-text-default">
                @if(request()->has('search'))
                    <a href="{{ route('admin.permissions.index') }}" class="px-3 py-2 text-xs font-bold text-status-danger-text hover:underline flex items-center">
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>

        <!-- TABEL PERMISSIONS -->
        <div class="bg-surface-card border border-border-default rounded-xl shadow-xs overflow-hidden space-y-4">
            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left border-collapse min-w-[900px] table-fixed">
                    <thead>
                        <tr class="bg-surface-page border-b border-border-default text-xs font-bold uppercase text-text-muted sticky top-0 z-10">
                            <!-- 1. Header Nomor Diganti 'No' -->
                            <th class="p-4 w-14 text-center">No</th>
                            <th class="p-4 w-60">Nama Permission</th>
                            <th class="p-4 w-28">Guard</th>
                            <th class="p-4 w-72">Digunakan oleh Role</th>
                            <th class="p-4 w-44">Riwayat Waktu</th>
                            <th class="p-4 w-36 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-default text-sm">
                        @forelse($permissions as $permission)
                            <tr class="hover:bg-surface-page/50 transition-colors">
                                <!-- 2. Nomor Urut Dinamis -->
                                <td class="p-4 text-center font-bold text-xs text-text-muted">
                                    {{ $loop->iteration + $permissions->firstItem() - 1 }}
                                </td>

                                <!-- Nama Permission Key -->
                                <td class="p-4 font-mono text-xs font-bold text-action-primary truncate">
                                    {{ $permission->name }}
                                </td>

                                <!-- Guard Name -->
                                <td class="p-4 font-mono text-xs text-text-muted uppercase whitespace-nowrap">
                                    {{ $permission->guard_name }}
                                </td>

                                <!-- Roles -->
                                <td class="p-4">
                                    <div class="flex flex-wrap gap-1 max-h-20 overflow-y-auto">
                                        @forelse($permission->roles as $role)
                                            <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded bg-surface-page border border-border-default text-text-default">
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span class="text-[11px] text-text-muted italic">Belum digunakan</span>
                                        @endforelse
                                    </div>
                                </td>

                                <!-- Tanggal Dibuat & Tanggal Di-update -->
                                <td class="p-4 space-y-1 text-xs">
                                    <div>
                                        <span class="text-[10px] font-semibold text-text-muted block">Dibuat:</span>
                                        <span class="font-medium text-text-default">{{ $permission->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-semibold text-text-muted block">Di-update:</span>
                                        <span class="font-medium text-text-default">{{ $permission->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </td>

                                <!-- Aksi -->
                                <td class="p-4 text-right space-x-1.5 whitespace-nowrap">
                                    <button @click="showModal = true; editMode = true; form = { id: {{ $permission->id }}, name: '{{ addslashes($permission->name) }}' }"
                                            class="px-2.5 py-1 text-xs font-bold bg-surface-page border border-border-default rounded-lg hover:bg-border-default text-text-default transition-colors cursor-pointer">
                                        Edit
                                    </button>

                                    <form method="POST" action="{{ route('admin.permissions.destroy', $permission->id) }}" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus permission ini?')">
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
                                <td colspan="6" class="p-8 text-center text-xs text-text-muted">Belum ada permission terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- 3. PAGINATION ANGKA LARAVEL DEFAULT -->
            @if(method_exists($permissions, 'hasPages') && $permissions->hasPages())
                <div class="p-4 border-t border-border-default overflow-x-auto">
                    {{ $permissions->links() }}
                </div>
            @endif
        </div>

        <!-- MODAL FORM (Tambah / Edit Permission) -->
        <div x-show="showModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" x-cloak>
            <div class="bg-surface-card rounded-2xl max-w-sm w-full p-6 space-y-4 border border-border-default shadow-xl" @click.away="showModal = false">
                <div class="flex justify-between items-center border-b border-border-default pb-3">
                    <h3 class="text-base font-bold text-text-default" x-text="editMode ? 'Edit Permission' : 'Tambah Permission Baru'"></h3>
                    <button @click="showModal = false" class="text-text-muted hover:text-text-default font-bold text-base cursor-pointer">&times;</button>
                </div>

                <form :action="editMode ? '/admin/permissions/' + form.id : '{{ route('admin.permissions.store') }}'" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1.5">Nama Permission Key</label>
                        <input type="text" name="name" x-model="form.name" required placeholder="cth: edit-booking / delete-gor" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-border-default outline-none bg-surface-card text-text-default">
                        <span class="text-[10px] text-text-muted italic mt-1 block">Gunakan format huruf kecil tanpa spasi (kebab-case).</span>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-border-default">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-bold bg-surface-page rounded-xl border border-border-default hover:bg-border-default transition-colors cursor-pointer">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold bg-action-primary hover:bg-action-primary-hover text-white rounded-xl shadow-xs transition-colors cursor-pointer" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Permission'"></button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts.admin>
