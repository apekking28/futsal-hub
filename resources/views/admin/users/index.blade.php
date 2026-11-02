<x-layouts.admin headerTitle="Manajemen User & RBAC">
    <div class="space-y-6" x-data="{
        showModal: false,
        editMode: false,
        form: { id: null, name: '', email: '', password: '', role: 'admin-gor', gor_id: '', phone_number: '' }
    }">

        <!-- Header Actions -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold">Daftar Pengguna / Staff System</h2>
                <p class="text-xs text-text-muted">Kelola akun Super Admin, Admin GOR, Kasir, serta penugasan wilayah operasional.</p>
            </div>
            <button @click="showModal = true; editMode = false; form = { id: null, name: '', email: '', password: '', role: 'admin-gor', gor_id: '{{ $gors->first()?->id }}', phone_number: '' }"
                    class="px-4 py-2 bg-action-primary hover:bg-action-primary-hover text-white font-bold text-xs rounded-lg transition-colors">
                + Tambah Pengguna
            </button>
        </div>

        <!-- Data Table -->
        <div class="bg-surface-card border border-border-default rounded-xl overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-page border-b border-border-default text-xs font-bold uppercase text-text-muted">
                        <th class="p-4">Nama & Email</th>
                        <th class="p-4">Role Access</th>
                        <th class="p-4">Lokasi Penugasan GOR</th>
                        <th class="p-4">No. HP</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-default text-sm">
                    @forelse($users as $user)
                        <tr class="hover:bg-surface-page/50">
                            <td class="p-4">
                                <p class="font-bold text-text-default">{{ $user->name }}</p>
                                <p class="text-xs text-text-muted">{{ $user->email }}</p>
                            </td>
                            <td class="p-4">
                                @foreach($user->roles as $role)
                                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded bg-surface-page border border-border-default">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="p-4 font-semibold text-xs">
                                @if($user->gor)
                                    <span class="text-action-primary font-bold">{{ $user->gor->name }}</span>
                                @else
                                    <span class="text-text-muted italic">Semua GOR (Global)</span>
                                @endif
                            </td>
                            <td class="p-4 font-mono text-xs">{{ $user->phone_number ?? '-' }}</td>
                            <td class="p-4 text-right space-x-2">
                                <button @click="showModal = true; editMode = true; form = { id: {{ $user->id }}, name: '{{ addslashes($user->name) }}', email: '{{ $user->email }}', password: '', role: '{{ $user->roles->first()?->name }}', gor_id: '{{ $user->gor_id }}', phone_number: '{{ $user->phone_number }}' }"
                                        class="px-2.5 py-1 text-xs font-bold bg-surface-page border border-border-default rounded hover:bg-border-default">
                                    Edit
                                </button>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 text-xs font-bold bg-status-danger-bg text-status-danger-text rounded hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-xs text-text-muted">Belum ada pengguna terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modal Form (Tambah / Edit User) -->
        <div x-show="showModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" x-cloak>
            <div class="bg-surface-card rounded-2xl max-w-md w-full p-6 space-y-4 border border-border-default shadow-lg" @click.away="showModal = false">
                <h3 class="text-lg font-bold" x-text="editMode ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru'"></h3>

                <form :action="editMode ? '/admin/users/' + form.id : '{{ route('admin.users.store') }}'" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Nama Lengkap</label>
                        <input type="text" name="name" x-model="form.name" required placeholder="cth: Ahmad Staff" class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">Email Log In</label>
                        <input type="email" name="email" x-model="form.email" required placeholder="staff@futsalhub.id" class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">
                            Kata Sandi / Password <span x-show="editMode" class="text-[10px] font-normal text-text-muted">(Kosongkan jika tidak ingin mengubah)</span>
                        </label>
                        <input type="password" name="password" x-model="form.password" :required="!editMode" placeholder="••••••••" class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-text-muted mb-1">Role / Peran</label>
                            <select name="role" x-model="form.role" required class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ strtoupper($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-text-muted mb-1">Lokasi Penugasan GOR</label>
                            <select name="gor_id" x-model="form.gor_id" :disabled="form.role === 'super-admin'" class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none disabled:opacity-50">
                                <option value="">-- Tanpa GOR Spesifik --</option>
                                @foreach($gors as $gor)
                                    <option value="{{ $gor->id }}">{{ $gor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-text-muted mb-1">No. WhatsApp / HP</label>
                        <input type="text" name="phone_number" x-model="form.phone_number" placeholder="08xxxxxxxxxx" class="w-full px-3 py-2 text-xs rounded-lg border border-border-default outline-none">
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-bold bg-surface-page rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold bg-action-primary text-white rounded-lg" x-text="editMode ? 'Simpan Perubahan' : 'Tambah User'"></button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts.admin>
