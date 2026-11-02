<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\Gor;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FieldManagementController extends Controller
{
    /**
     * Tampilkan Daftar Lapangan beserta Foto Galeri
     */
    public function index()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        $fieldsQuery = Field::with(['gor', 'images']);

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
            'type'           => 'required|string|max:100',
            'price_per_hour' => 'required|numeric|min:0',
            'is_active'      => 'required|boolean',
            'description'    => 'nullable|string',
            'images.*'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $gor = Gor::findOrFail($request->gor_id);

        // Slug Unik
        $baseSlug = Str::slug($gor->name . ' ' . $request->name);
        $slug = $baseSlug;
        $count = 1;
        while (Field::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        $field = Field::create([
            'gor_id'         => $request->gor_id,
            'name'           => $request->name,
            'slug'           => $slug,
            'type'           => strtolower($request->type),
            'price_per_hour' => $request->price_per_hour,
            'is_active'      => $request->is_active,
            'description'    => $request->description,
        ]);

        // Proses Unggah Foto (jika ada)
        if ($request->hasFile('images')) {
            $this->uploadImages($field, $request->file('images'));
        }

        return back()->with('toast_success', 'Lapangan baru berhasil ditambahkan!');
    }

    /**
     * Update Data Lapangan & Foto Tambahan
     */
    public function update(Request $request, $id)
    {
        $field = Field::findOrFail($id);

        $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|string|max:100',
            'price_per_hour' => 'required|numeric|min:0',
            'is_active'      => 'required|boolean',
            'description'    => 'nullable|string',
            'images.*'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Update Slug jika nama berubah
        if ($field->name !== $request->name) {
            $baseSlug = Str::slug($field->gor->name . ' ' . $request->name);
            $slug = $baseSlug;
            $count = 1;
            while (Field::where('slug', $slug)->where('id', '!=', $field->id)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }
            $field->slug = $slug;
        }

        $field->update([
            'name'           => $request->name,
            'type'           => strtolower($request->type),
            'price_per_hour' => $request->price_per_hour,
            'is_active'      => $request->is_active,
            'description'    => $request->description,
        ]);

        if ($request->hasFile('images')) {
            $this->uploadImages($field, $request->file('images'));
        }

        return back()->with('toast_success', 'Data lapangan berhasil diperbarui!');
    }

    /**
     * Set Foto Utama (Cover)
     */
    public function setCover($fieldId, $imageId)
    {
        $field = Field::findOrFail($fieldId);

        // Reset semua is_cover di lapangan ini ke 0
        $field->images()->update(['is_cover' => false]);

        // Set gambar terpilih menjadi cover
        $image = $field->images()->findOrFail($imageId);
        $image->update(['is_cover' => true]);

        return back()->with('toast_success', 'Foto cover berhasil diperbarui!');
    }

    /**
     * Hapus Gambar Spesiﬁk
     */
    public function deleteImage($imageId)
    {
        $image = Image::findOrFail($imageId);

        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        if ($image->thumb_path && Storage::disk('public')->exists($image->thumb_path)) {
            Storage::disk('public')->delete($image->thumb_path);
        }

        $image->delete();

        return back()->with('toast_success', 'Foto berhasil dihapus!');
    }

    /**
     * Hapus Lapangan
     */
    public function destroy($id)
    {
        $field = Field::with('images')->findOrFail($id);

        if ($field->bookings()->whereIn('booking_status', ['reserved', 'confirmed'])->exists()) {
            return back()->with('toast_error', 'Gagal menghapus! Masih ada booking aktif di lapangan ini.');
        }

        // Hapus file gambar dari storage
        foreach ($field->images as $img) {
            if (Storage::disk('public')->exists($img->path)) {
                Storage::disk('public')->delete($img->path);
            }
            if ($img->thumb_path && Storage::disk('public')->exists($img->thumb_path)) {
                Storage::disk('public')->delete($img->thumb_path);
            }
            $img->delete();
        }

        $field->delete();

        return back()->with('toast_success', 'Lapangan berhasil dihapus!');
    }

    /**
     * Helper Upload File Gambar
     */
    private function uploadImages(Field $field, array $files)
    {
        $existingCount = $field->images()->count();
        $isFirst = $existingCount === 0;

        foreach ($files as $index => $file) {
            if ($existingCount >= 8) break; // Maksimal 8 foto per lapangan (Addendum)

            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('fields', $filename, 'public');

            $field->images()->create([
                'path'       => $path,
                'thumb_path' => $path, // Bisa diintegrasikan dengan Intervention Image untuk resize 480px jika ada
                'alt_text'   => $field->name . ' - foto ' . ($existingCount + 1),
                'is_cover'   => $isFirst && $index === 0,
                'sort_order' => $existingCount + 1,
            ]);

            $existingCount++;
        }
    }
}
