<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DistrictManagementController extends Controller
{
    /**
     * Tampilkan Daftar Wilayah / District (Filter, Search, & Pagination)
     */
    public function index(Request $request)
    {
        $query = District::withCount('gors');

        // Filter Pencarian (Nama District atau Slug)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Pagination 10 item per halaman + query string filter
        $districts = $query->latest()->paginate(10)->withQueryString();

        return view('admin.districts.index', compact('districts'));
    }

    /**
     * Simpan District / Wilayah Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:districts,name',
        ], [
            'name.unique' => 'Nama wilayah/kecamatan ini sudah terdaftar.',
        ]);

        $slug = Str::slug($request->name);
        $count = 1;
        while (District::where('slug', $slug)->exists()) {
            $slug = Str::slug($request->name) . '-' . $count++;
        }

        District::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return back()->with('toast_success', 'Wilayah / District baru berhasil ditambahkan!');
    }

    /**
     * Update Data District / Wilayah
     */
    public function update(Request $request, $id)
    {
        $district = District::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:districts,name,' . $district->id,
        ], [
            'name.unique' => 'Nama wilayah/kecamatan ini sudah terdaftar.',
        ]);

        if ($district->name !== $request->name) {
            $slug = Str::slug($request->name);
            $count = 1;
            while (District::where('slug', $slug)->where('id', '!=', $district->id)->exists()) {
                $slug = Str::slug($request->name) . '-' . $count++;
            }
            $district->slug = $slug;
        }

        $district->update([
            'name' => $request->name,
        ]);

        return back()->with('toast_success', 'Data Wilayah ' . $district->name . ' berhasil diperbarui!');
    }

    /**
     * Hapus District / Wilayah
     */
    public function destroy($id)
    {
        $district = District::withCount('gors')->findOrFail($id);

        if ($district->gors_count > 0) {
            return back()->with('toast_error', 'Gagal menghapus! Wilayah ini masih terikat dengan data GOR.');
        }

        $district->delete();

        return back()->with('toast_success', 'Wilayah / District berhasil dihapus!');
    }
}
