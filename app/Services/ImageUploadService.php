<?php

namespace App\Services;

use App\Models\Image as ImageModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Upload & Optimasi Gambar ke Format WebP (Resized + Thumbnail)
     */
    public function uploadAndOptimize(Model $model, array $files, int $maxLimit = 8): void
    {
        $existingCount = $model->images()->count();
        $isFirst = $existingCount === 0;

        foreach ($files as $index => $file) {
            if ($existingCount >= $maxLimit) {
                break;
            }

            $folder = strtolower(class_basename($model)) . 's';
            $filename = Str::uuid() . '.webp';

            $path = "{$folder}/{$filename}";
            $thumbPath = "{$folder}/thumb_{$filename}";

            // 1. Simpan Gambar Utama (Max Width 1280px, WebP)
            $this->convertToWebp($file, storage_path("app/public/{$path}"), 1280, 80);

            // 2. Simpan Gambar Thumbnail (Max Width 480px, WebP)
            $this->convertToWebp($file, storage_path("app/public/{$thumbPath}"), 480, 75);

            // 3. Simpan Rekord ke Database Tabel Images
            $model->images()->create([
                'path'       => $path,
                'thumb_path' => $thumbPath,
                'alt_text'   => $model->name . ' - foto ' . ($existingCount + 1),
                'is_cover'   => $isFirst && $index === 0,
                'sort_order' => $existingCount + 1,
            ]);

            $existingCount++;
        }
    }

    /**
     * Hapus Gambar dari Disk & Database
     */
    public function deleteImage(ImageModel $image): void
    {
        if ($image->path && Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        if ($image->thumb_path && Storage::disk('public')->exists($image->thumb_path)) {
            Storage::disk('public')->delete($image->thumb_path);
        }

        $image->delete();
    }

    /**
     * Converter & Resizer Gambar ke format WebP via PHP GD Library (Auto EXIF Fix)
     */
    private function convertToWebp(UploadedFile $file, string $destinationPath, int $maxWidth, int $quality = 80): void
    {
        $dir = dirname($destinationPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $filePath = $file->getRealPath();
        list($origWidth, $origHeight, $type) = getimagesize($filePath);

        switch ($type) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($filePath);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($filePath);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            case IMAGETYPE_WEBP:
                $image = imagecreatefromwebp($filePath);
                break;
            default:
                throw new \InvalidArgumentException('Format gambar tidak didukung!');
        }

        // AUTO-FIX ORIENTASI EXIF (Kamera HP)
        if ($type === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            @$exif = exif_read_data($filePath);
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $image = imagerotate($image, 180, 0);
                        break;
                    case 6:
                        $image = imagerotate($image, -90, 0);
                        break;
                    case 8:
                        $image = imagerotate($image, 90, 0);
                        break;
                }
            }
        }

        // Ambil ulang dimensi lebar dan tinggi setelah rotasi EXIF
        $currentWidth  = imagesx($image);
        $currentHeight = imagesy($image);

        if ($currentWidth > $maxWidth) {
            $newWidth  = $maxWidth;
            $newHeight = (int)(($currentHeight / $currentWidth) * $maxWidth);
        } else {
            $newWidth  = $currentWidth;
            $newHeight = $currentHeight;
        }

        $canvas = imagecreatetruecolor($newWidth, $newHeight);

        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP) {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
        }

        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $newWidth, $newHeight, $currentWidth, $currentHeight);

        imagewebp($canvas, $destinationPath, $quality);

        imagedestroy($image);
        imagedestroy($canvas);
    }
}
