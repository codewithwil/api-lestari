<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait Base64FileHandler
{
    protected function storeBase64Image(string $base64, string $folder, array $allowedTypes = ['jpeg', 'jpg', 'png', 'svg']): string|false
    {
        if (preg_match('/^data:image\/([a-zA-Z0-9\+]+);base64,/', $base64, $matches)) {
            $extension = $matches[1];

            if ($extension === 'svg+xml') {
                $extension = 'svg';
            }

            if (!in_array($extension, $allowedTypes)) {
                return false;
            }

            $base64Data = substr($base64, strpos($base64, ',') + 1);
            $decoded = base64_decode($base64Data);

            if ($decoded === false) {
                return false;
            }

            $filename = $folder . '/' . uniqid() . '.' . $extension;
            Storage::disk('public')->put($filename, $decoded);
            return $filename;
        }

        return false;
    }

    protected function convertToBase64(string $path): ?string
    {
        if (!file_exists($path)) return null;

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $mime = $ext === 'svg' ? 'image/svg+xml' : "image/{$ext}";
        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }

    protected function handleUpdatedImage(string $newBase64, ?string $oldPath, string $folder): ?string
    {
        if (!empty($newBase64) && str_starts_with($newBase64, 'data:image')) {
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            return $this->storeBase64Image($newBase64, $folder);
        }

        return $oldPath;
    }

}
