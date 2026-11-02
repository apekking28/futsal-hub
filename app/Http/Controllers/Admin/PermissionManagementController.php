<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionManagementController extends Controller
{
    /**
     * Tampilkan Daftar Permission (Khusus Super Admin)
     */
    public function index()
    {
        $permissions = Permission::with('roles')->latest()->get();

        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Simpan Permission Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:permissions,name',
        ], [
            'name.unique' => 'Nama permission ini sudah terdaftar.',
        ]);

        Permission::create([
            'name'       => strtolower(trim($request->name)),
            'guard_name' => 'web',
        ]);

        return back()->with('toast_success', 'Permission baru berhasil ditambahkan!');
    }

    /**
     * Update Data Permission
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update([
            'name' => strtolower(trim($request->name)),
        ]);

        return back()->with('toast_success', 'Nama permission berhasil diperbarui!');
    }

    /**
     * Hapus Permission
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        if ($permission->roles()->count() > 0) {
            return back()->with('toast_error', 'Gagal! Permission ini sedang terikat pada role aktif.');
        }

        $permission->delete();

        return back()->with('toast_success', 'Permission berhasil dihapus!');
    }
}
