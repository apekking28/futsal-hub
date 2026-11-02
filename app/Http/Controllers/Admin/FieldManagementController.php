<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\Gor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FieldManagementController extends Controller
{
    /**
     * Tampilkan Daftar Lapangan
     */
    public function index()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // Super Admin bisa melihat semua, Admin GOR hanya melihat milik GOR-nya
        $fieldsQuery = Field::with('gor');

        if ($user && $user->hasRole('admin-gor') && $user->gor_id) {
            $fieldsQuery->where('gor_id', $user->gor_id);
        }

        $fields = $fieldsQuery->latest()->get();
        $gors = ($user && $user->hasRole('super-admin'))
            ? Gor::all()
            : Gor::where('id', $user?->gor_id)->get();

        return view('admin.fields.index', compact('fields', 'gors'));
    }

    /**
     * Simpan Lapangan Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'gor_id'         => 'required|exists:gors,id',
            'name'           => 'required|string|max:255',
            'type'           => 'required|string|max:100', // Sintetis, Vinyl, Semen, dll
            'price_per_hour' => 'required|numeric|min:0',
            'is_active'      => 'required|boolean',
        ]);

        Field::create($request->all());

        return back()->with('toast_success', 'Lapangan baru berhasil ditambahkan!');
    }

    /**
     * Update Data Lapangan
     */
    public function update(Request $request, $id)
    {
        $field = Field::findOrFail($id);

        $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|string|max:100',
            'price_per_hour' => 'required|numeric|min:0',
            'is_active'      => 'required|boolean',
        ]);

        $field->update($request->only(['name', 'type', 'price_per_hour', 'is_active']));

        return back()->with('toast_success', 'Data lapangan berhasil diperbarui!');
    }

    /**
     * Hapus Lapangan
     */
    public function destroy($id)
    {
        $field = Field::findOrFail($id);

        // Cek jika ada booking aktif
        if ($field->bookings()->whereIn('booking_status', ['reserved', 'confirmed'])->exists()) {
            return back()->with('toast_error', 'Gagal menghapus! Masih ada booking aktif di lapangan ini.');
        }

        $field->delete();

        return back()->with('toast_success', 'Lapangan berhasil dihapus!');
    }
}
