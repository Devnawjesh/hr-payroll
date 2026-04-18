<?php

namespace App\Modules\Settings\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BrandingAssetService
{
    public function store(?UploadedFile $file, string $prefix): ?string
    {
        if ($file === null || ! $file->isValid()) {
            return null;
        }

        $uploadDir = public_path('assets/uploads/settings');
        if (! File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        $extension = Str::lower($file->getClientOriginalExtension());
        $filename = $prefix.'_'.time().'_'.Str::random(8).'.'.$extension;
        $file->move($uploadDir, $filename);

        return 'assets/uploads/settings/'.$filename;
    }

    public function deleteByRelativePath(?string $path): void
    {
        if ($path && str_starts_with($path, 'assets/uploads/settings/')) {
            File::delete(public_path($path));
        }
    }
}
