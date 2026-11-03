<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleManagementController extends Controller
{
    /**
     * Tampilkan Daftar Role & Grouped Permissions (Filter, Search, & Pagination)
     */
    public function index(Request $request)
    {
        $query = Role::with('permissions')->withCount('users');

        // Filter Pencarian (Nama Role)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Pagination 10 item per halaman + membawa query string filter
        $roles = $query->latest()->paginate(10)->withQueryString();
        $allPermissions = Permission::all();

        // Kelompokkan Permission berdasarkan Prefix Modul (contoh: 'fields.create' -> Group: 'fields')
        $groupedPermissions = $allPermissions->groupBy(function ($perm) {
            $parts = explode('.', $perm->name);

            return count($parts) > 1 ? $parts[0] : 'Lainnya';
        });

        return view('admin.roles.index', compact('roles', 'groupedPermissions'));
    }

    /**
     * Simpan Role Baru dengan Terikat Permission
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ], [
            'name.unique' => 'Nama role ini sudah ada.',
        ]);

        $role = Role::create([
            'name' => strtolower(trim($request->name)),
            'guard_name' => 'web',
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return back()->with('toast_success', 'Role baru berhasil ditambahkan!');
    }

    /**
     * Update Data Role & Sync Checklist Permission
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if (in_array($role->name, ['super-admin', 'admin-gor', 'kasir'])) {
            $request->validate([
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,name',
            ]);
        } else {
            $request->validate([
                'name' => 'required|string|max:50|unique:roles,name,'.$role->id,
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,name',
            ]);

            $role->name = strtolower(trim($request->name));
            $role->save();
        }

        // Sync Array Checklist Permission
        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        return back()->with('toast_success', 'Hak akses role '.$role->name.' berhasil diperbarui!');
    }

    /**
     * Hapus Role
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if (in_array($role->name, ['super-admin', 'admin-gor', 'kasir'])) {
            return back()->with('toast_error', 'Role bawaan sistem tidak boleh dihapus!');
        }

        if ($role->users()->count() > 0) {
            return back()->with('toast_error', 'Gagal! Masih ada user yang terikat pada role ini.');
        }

        $role->delete();

        return back()->with('toast_success', 'Role berhasil dihapus!');
    }
}
