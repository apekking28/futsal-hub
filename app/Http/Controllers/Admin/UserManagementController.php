<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    /**
     * Tampilkan Daftar User Staff (Filter, Search, & Pagination)
     */
    public function index(Request $request)
    {
        $query = User::with(['gor', 'roles']);

        // Filter Pencarian (Nama, Email, No HP)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter Berdasarkan Role
        if ($request->filled('role')) {
            $roleName = $request->role;
            $query->whereHas('roles', function ($q) use ($roleName) {
                $q->where('name', $roleName);
            });
        }

        // Pagination 10 item per halaman + query string filter
        $users = $query->latest()->paginate(10)->withQueryString();
        $gors = Gor::where('is_active', true)->get();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'gors', 'roles'));
    }

    /**
     * Tambah Pengguna / Staff Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
            'gor_id' => 'nullable|required_if:role,admin-gor,kasir|exists:gors,id',
            'phone_number' => 'nullable|string|max:20',
        ], [
            'gor_id.required_if' => 'GOR lokasi wajib dipilih untuk role Admin GOR atau Kasir.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gor_id' => $request->role === 'super-admin' ? null : $request->gor_id,
            'phone_number' => $request->phone_number,
        ]);

        // Assign Role via Spatie
        $user->assignRole($request->role);

        return back()->with('toast_success', 'Pengguna baru berhasil ditambahkan!');
    }

    /**
     * Update Data Pengguna
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|exists:roles,name',
            'gor_id' => 'nullable|required_if:role,admin-gor,kasir|exists:gors,id',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'gor_id' => $request->role === 'super-admin' ? null : $request->gor_id,
            'phone_number' => $request->phone_number,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Sync Role Spatie
        $user->syncRoles([$request->role]);

        return back()->with('toast_success', 'Data pengguna berhasil diperbarui!');
    }

    /**
     * Hapus Pengguna
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Hindari Super Admin menghapus dirinya sendiri
        if ($user->id === Auth::user()->id) {
            return back()->with('toast_error', 'Gagal! Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete();

        return back()->with('toast_success', 'Pengguna berhasil dihapus!');
    }
}
