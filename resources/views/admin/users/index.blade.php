<x-layouts.admin headerTitle="Manajemen User & RBAC">
    <div class="space-y-6" x-data="{
        showModal: false,
        editMode: false,
        form: { id: null, name: '', email: '', password: '', role: 'admin-gor', gor_id: '', phone_number: '' }
    }">

        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-text-default">Daftar Pengguna / Staff System</h2>
                <p class="text-xs text-text-muted">Kelola akun Super Admin, Admin GOR, Kasir, serta penugasan wilayah operasional.</p>
            </div>
            <button @click="showModal = true; editMode = false; form = { id: null, name: '', email: '', password: '', role: 'admin-gor', gor_id: '{{ $gors->first()?->id }}', phone_number: '' }"
                    class="w-full sm:w-auto px-4 py-2 bg-action-primary hover:bg-action-primary-hover text-white font-bold text-xs rounded-xl transition-colors shadow-xs text-center cursor-pointer">
                + Tambah Pengguna
            </button>
        </div>

        <!-- Filter & Search Bar -->
        <div class="flex flex-col sm:flex-row justify-between gap-3">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row flex-1 gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / Email / HP..." class="w-full sm:max-w-xs px-3.5 py-2 text-xs rounded-xl border border-border-default outline-none focus:ring-2 focus:ring-action-primary bg-surface-card text-text-default">
                <select name="role" onchange="this.form.submit()" class="w-full sm:w-auto px-3.5 py-2 text-xs rounded-xl border border-border-default outline-none bg-surface-card font-semibold text-text-default">
                    <option value="">Semua Role Access</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->name }}" {{ request('role') === $r->name ? 'selected' : '' }}>
                            {{ strtoupper($r->name) }}
                        </option>
                    @endforeach
                </select>
                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-xs font-bold text-status-danger-text hover:underline flex items-center">
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>

        <!-- TABEL STABIL RESPONSIF -->
        <div class="bg-surface-card border border-border-default rounded-xl shadow-xs overflow-hidden space-y-4">
            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left border-collapse min-w-[950px] table-fixed">
                    <thead>
                        <tr class="bg-surface-page border-b border-border-default text-xs font-bold uppercase text-text-muted sticky top-0 z-10">
                            <!-- 1. Header Nomor 'No' -->
                            <th class="p-4 w-14 text-center">No</th>
                            <th class="p-4 w-52">Nama & Email</th>
                            <th class="p-4 w-36">Role Access</th>
                            <th class="p-4 w-48">Lokasi Penugasan GOR</th>
                            <th class="p-4 w-32">No. HP</th>
                            <th class="p-4 w-44">Riwayat Waktu</th>
                            <th class="p-4 w-36 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-default text-sm">
                        @forelse($users as $user)
                            <tr class="hover:bg-surface-page/50 transition-colors">
                                <!-- 2. Nomor Urut Dinamis -->
                                <td class="p-4 text-center font-bold text-xs text-text-muted">
                                    {{ $loop->iteration + $users->firstItem() - 1 }}
                                </td>

                                <!-- Nama & Email -->
                                <td class="p-4">
                                    <p class="font-bold text-text-default text-xs truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-text-muted truncate">{{ $user->email }}</p>
                                </td>

                                <!-- Role -->
                                <td class="p-4 whitespace-nowrap">
                                    @foreach($user->roles as $role)
                                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded bg-surface-page border border-border-default text-text-default">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>

                                <!-- GOR -->
                                <td class="p-4 font-semibold text-xs whitespace-nowrap">
                                    @if($user->gor)
                                        <span class="text-action-primary font-bold truncate block" title="{{ $user->gor->name }}">
                                            {{ $user->gor->name }}
                                        </span>
                                    @else
                                        <span class="text-text-muted italic">Semua GOR (Global)</span>
                                    @endif
                                </td>

                                <!-- Telepon -->
                                <td class="p-4 font-mono text-xs whitespace-nowrap">{{ $user->phone_number ?? '-' }}</td>

                                <!-- Tanggal Dibuat & Di-update -->
                                <td class="p-4 space-y-1 text-xs">
                                    <div>
                                        <span class="text-[10px] font-semibold text-text-muted block">Dibuat:</span>
                                        <span class="font-medium text-text-default">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-semibold text-text-muted block">Di-update:</span>
                                        <span class="font-medium text-text-default">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </td>

                                <!-- Aksi -->
                                <td class="p-4 text-right space-x-1.5 whitespace-nowrap">
                                    <button @click="showModal = true; editMode = true; form = { id: {{ $user->id }}, name: '{{ addslashes($user->name) }}', email: '{{ $user->email }}', password: '', role: '{{ $user->roles->first()?->name }}', gor_id: '{{ $user->gor_id }}', phone_number: '{{ $user->phone_number }}' }"
                                            class="px-2.5 py-1 text-xs font-bold bg-surface-page border border-border-default rounded-lg hover:bg-border-default text-text-default transition-colors cursor-pointer">
                                        Edit
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
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
                                <td colspan="7" class="p-8 text-center text-xs text-text-muted">Belum ada pengguna terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- 3. PAGINATION ANGKA LARAVEL DEFAULT -->
            @if(method_exists($users, 'hasPages') && $users->hasPages())
                <div class="p-4 border-t border-border-default overflow-x-auto">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Form (Tambah / Edit User) -->
        <div x-show="showModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" x-cloak>
            <div class="bg-surface-card rounded-2xl max-w-md w-full p-6 space-y-4 border border-border-default shadow-lg max-h-[90vh] overflow-y-auto" @click.away="showModal = false">
                <div class="flex justify-between items-center border-b border-border-default pb-2">
                    <h3 class="text-base font-bold text-text-default" x-text="editMode ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru'"></h3>
                    <button @click="showModal = false" class="text-text-muted hover:text-text-default font-bold text-base cursor-pointer">&times;</button>
                </div>

                <form :action="editMode ? '/admin/users/' + form.id : '{{ route('admin.users.store') }}'" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Nama Lengkap</label>
                        <input type="text" name="name" x-model="form.name" required placeholder="cth: Ahmad Staff" class="w-full px-3 py-2 text-xs rounded-xl border border-border-default outline-none bg-surface-card text-text-default">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Email Log In</label>
                        <input type="email" name="email" x-model="form.email" required placeholder="staff@futsalhub.id" class="w-full px-3 py-2 text-xs rounded-xl border border-border-default outline-none bg-surface-card text-text-default">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">
                            Kata Sandi / Password <span x-show="editMode" class="text-[10px] font-normal text-text-muted">(Kosongkan jika tidak ingin mengubah)</span>
                        </label>
                        <input type="password" name="password" x-model="form.password" :required="!editMode" placeholder="••••••••" class="w-full px-3 py-2 text-xs rounded-xl border border-border-default outline-none bg-surface-card text-text-default">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-text-muted mb-1">Role / Peran</label>
                            <select name="role" x-model="form.role" required class="w-full px-3 py-2 text-xs rounded-xl border border-border-default outline-none bg-surface-card text-text-default">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ strtoupper($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-text-muted mb-1">Lokasi Penugasan GOR</label>
                            <select name="gor_id" x-model="form.gor_id" :disabled="form.role === 'super-admin'" class="w-full px-3 py-2 text-xs rounded-xl border border-border-default outline-none bg-surface-card text-text-default disabled:opacity-50">
                                <option value="">-- Tanpa GOR Spesifik --</option>
                                @foreach($gors as $gor)
                                    <option value="{{ $gor->id }}">{{ $gor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">No. WhatsApp / HP</label>
                        <input type="text" name="phone_number" x-model="form.phone_number" placeholder="08xxxxxxxxxx" class="w-full px-3 py-2 text-xs rounded-xl border border-border-default outline-none bg-surface-card text-text-default">
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-border-default">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-bold bg-surface-page rounded-xl border border-border-default hover:bg-border-default text-text-default transition-colors cursor-pointer">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold bg-action-primary text-white rounded-xl hover:bg-action-primary-hover shadow-xs transition-colors cursor-pointer" x-text="editMode ? 'Simpan Perubahan' : 'Tambah User'"></button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts.admin>
