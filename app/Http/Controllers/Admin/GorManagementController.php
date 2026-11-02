<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Gor;
use App\Models\Image;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GorManagementController extends Controller
{
    protected ImageUploadService $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $gors = Gor::with(['district', 'fields', 'images'])->latest()->get();
        $districts = District::all();

        return view('admin.gors.index', compact('gors', 'districts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'district_id'         => 'required|exists:districts,id',
            'name'                => 'required|string|max:150',
            'address'             => 'required|string',
            'description'         => 'nullable|string',
            'maps_url'            => 'nullable|url|max:500',
            'phone_number'        => 'required|string|max:20',
            'open_time'           => 'required',
            'close_time'          => 'required',
            'bank_name'           => 'nullable|string|max:50',
            'bank_account_number' => 'nullable|string|max:30',
            'bank_account_holder' => 'nullable|string|max:100',
            'is_active'           => 'required|boolean',
            'images.*'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $slug = Str::slug($request->name);
        $count = 1;
        while (Gor::where('slug', $slug)->exists()) {
            $slug = Str::slug($request->name) . '-' . $count++;
        }

        $gor = Gor::create([
            'district_id'         => $request->district_id,
            'name'                => $request->name,
            'slug'                => $slug,
            'address'             => $request->address,
            'description'         => $request->description,
            'maps_url'            => $request->maps_url,
            'phone_number'        => $request->phone_number,
            'open_time'           => $request->open_time,
            'close_time'          => $request->close_time,
            'bank_name'           => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_holder' => $request->bank_account_holder,
            'is_active'           => $request->is_active,
        ]);

        if ($request->hasFile('images')) {
            // Panggil Service Upload WebP (Max 5 foto untuk GOR)
            $this->imageService->uploadAndOptimize($gor, $request->file('images'), 5);
        }

        return back()->with('toast_success', 'GOR baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $gor = Gor::findOrFail($id);

        $request->validate([
            'district_id'         => 'required|exists:districts,id',
            'name'                => 'required|string|max:150',
            'address'             => 'required|string',
            'description'         => 'nullable|string',
            'maps_url'            => 'nullable|url|max:500',
            'phone_number'        => 'required|string|max:20',
            'open_time'           => 'required',
            'close_time'          => 'required',
            'bank_name'           => 'nullable|string|max:50',
            'bank_account_number' => 'nullable|string|max:30',
            'bank_account_holder' => 'nullable|string|max:100',
            'is_active'           => 'required|boolean',
            'images.*'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($gor->name !== $request->name) {
            $slug = Str::slug($request->name);
            $count = 1;
            while (Gor::where('slug', $slug)->where('id', '!=', $gor->id)->exists()) {
                $slug = Str::slug($request->name) . '-' . $count++;
            }
            $gor->slug = $slug;
        }

        $gor->update([
            'district_id'         => $request->district_id,
            'name'                => $request->name,
            'address'             => $request->address,
            'description'         => $request->description,
            'maps_url'            => $request->maps_url,
            'phone_number'        => $request->phone_number,
            'open_time'           => $request->open_time,
            'close_time'          => $request->close_time,
            'bank_name'           => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_holder' => $request->bank_account_holder,
            'is_active'           => $request->is_active,
        ]);

        if ($request->hasFile('images')) {
            $this->imageService->uploadAndOptimize($gor, $request->file('images'), 5);
        }

        return back()->with('toast_success', 'Data GOR berhasil diperbarui!');
    }

    public function storeImages(Request $request, $id)
    {
        $request->validate([
            'images'   => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $gor = Gor::findOrFail($id);

        if ($gor->images()->count() >= 5) {
            return back()->with('toast_error', 'Gagal! Foto GOR sudah mencapai batas maksimal (5 foto).');
        }

        $this->imageService->uploadAndOptimize($gor, $request->file('images'), 5);

        return back()->with('toast_success', 'Foto galeri GOR berhasil diunggah!');
    }

    public function setCover($gorId, $imageId)
    {
        $gor = Gor::findOrFail($gorId);
        $gor->images()->update(['is_cover' => false]);

        $image = $gor->images()->findOrFail($imageId);
        $image->update(['is_cover' => true]);

        return back()->with('toast_success', 'Foto cover GOR berhasil diperbarui!');
    }

    public function deleteImage($imageId)
    {
        $image = Image::findOrFail($imageId);
        $this->imageService->deleteImage($image);

        return back()->with('toast_success', 'Foto berhasil dihapus!');
    }

    public function destroy($id)
    {
        $gor = Gor::with('images')->findOrFail($id);

        $hasActiveBookings = $gor->fields()->whereHas('bookings', function ($q) {
            $q->whereIn('booking_status', ['reserved', 'confirmed']);
        })->exists();

        if ($hasActiveBookings) {
            return back()->with('toast_error', 'Gagal menghapus! GOR ini masih memiliki transaksi booking aktif.');
        }

        foreach ($gor->images as $img) {
            $this->imageService->deleteImage($img);
        }

        $gor->delete();

        return back()->with('toast_success', 'Data GOR berhasil dihapus!');
    }
}
