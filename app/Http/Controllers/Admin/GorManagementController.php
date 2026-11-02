<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Gor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GorManagementController extends Controller
{
    /**
     * Tampilkan Daftar GOR (Khusus Super Admin)
     */
    public function index()
    {
        $gors = Gor::with(['district', 'fields'])->latest()->get();
        $districts = District::all();

        return view('admin.gors.index', compact('gors', 'districts'));
    }

    /**
     * Simpan GOR Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'district_id'  => 'required|exists:districts,id',
            'name'         => 'required|string|max:150',
            'address'      => 'required|string',
            'phone_number' => 'required|string|max:20',
            'is_active'    => 'required|boolean',
        ]);

        Gor::create([
            'district_id'  => $request->district_id,
            'name'         => $request->name,
            'slug'         => Str::slug($request->name),
            'address'      => $request->address,
            'phone_number' => $request->phone_number,
            'is_active'    => $request->is_active,
        ]);

        return back()->with('toast_success', 'GOR baru berhasil ditambahkan!');
    }

    /**
     * Update Data GOR
     */
    public function update(Request $request, $id)
    {
        $gor = Gor::findOrFail($id);

        $request->validate([
            'district_id'  => 'required|exists:districts,id',
            'name'         => 'required|string|max:150',
            'address'      => 'required|string',
            'phone_number' => 'required|string|max:20',
            'is_active'    => 'required|boolean',
        ]);

        $gor->update([
            'district_id'  => $request->district_id,
            'name'         => $request->name,
            'slug'         => Str::slug($request->name),
            'address'      => $request->address,
            'phone_number' => $request->phone_number,
            'is_active'    => $request->is_active,
        ]);

        return back()->with('toast_success', 'Data GOR berhasil diperbarui!');
    }

    /**
     * Hapus GOR
     */
    public function destroy($id)
    {
        $gor = Gor::findOrFail($id);

        // Cek jika GOR masih memiliki transaksi booking aktif
        $hasActiveBookings = $gor->fields()->whereHas('bookings', function ($q) {
            $q->whereIn('booking_status', ['reserved', 'confirmed']);
        })->exists();

        if ($hasActiveBookings) {
            return back()->with('toast_error', 'Gagal menghapus! GOR ini masih memiliki transaksi booking aktif.');
        }

        $gor->delete();

        return back()->with('toast_success', 'Data GOR berhasil dihapus!');
    }
}
