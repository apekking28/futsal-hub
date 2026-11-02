<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\Gor;
use App\Models\Image;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FieldManagementController extends Controller
{
    protected ImageUploadService $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

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

        if ($request->hasFile('images')) {
            // Upload & konversi ke WebP (Max 8 foto)
            $this->imageService->uploadAndOptimize($field, $request->file('images'), 8);
        }

        return back()->with('toast_success', 'Lapangan baru berhasil ditambahkan!');
    }

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
            $this->imageService->uploadAndOptimize($field, $request->file('images'), 8);
        }

        return back()->with('toast_success', 'Data lapangan berhasil diperbarui!');
    }

    public function setCover($fieldId, $imageId)
    {
        $field = Field::findOrFail($fieldId);
        $field->images()->update(['is_cover' => false]);

        $image = $field->images()->findOrFail($imageId);
        $image->update(['is_cover' => true]);

        return back()->with('toast_success', 'Foto cover berhasil diperbarui!');
    }

    public function deleteImage($imageId)
    {
        $image = Image::findOrFail($imageId);
        $this->imageService->deleteImage($image);

        return back()->with('toast_success', 'Foto berhasil dihapus!');
    }

    public function destroy($id)
    {
        $field = Field::with('images')->findOrFail($id);

        if ($field->bookings()->whereIn('booking_status', ['reserved', 'confirmed'])->exists()) {
            return back()->with('toast_error', 'Gagal menghapus! Masih ada booking aktif di lapangan ini.');
        }

        foreach ($field->images as $img) {
            $this->imageService->deleteImage($img);
        }

        $field->delete();

        return back()->with('toast_success', 'Lapangan berhasil dihapus!');
    }
}
