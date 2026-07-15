<?php

namespace App\Modules\Employees\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class EmployeeAssetService
{
    public function storeAvatar(?UploadedFile $file): ?string
    {
        if ($file === null || ! $file->isValid()) {
            return null;
        }

        $uploadDir = public_path('assets/uploads/employees');
        if (! File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        $extension = Str::lower($file->getClientOriginalExtension());
        $filename = 'employee_'.time().'_'.Str::random(8).'.'.$extension;
        $file->move($uploadDir, $filename);

        return 'assets/uploads/employees/'.$filename;
    }

    public function deleteAvatar(?string $path): void
    {
        if ($path && str_starts_with($path, 'assets/uploads/employees/')) {
            File::delete(public_path($path));
        }
    }

    public function storeDocument(?UploadedFile $file): ?string
    {
        if ($file === null || ! $file->isValid()) {
            return null;
        }

        $extension = Str::lower($file->getClientOriginalExtension());
        $filename = 'employee_document_'.time().'_'.Str::random(8).'.'.$extension;
        $path = $file->storeAs('employee-documents', $filename, 'public');

        return $path ? 'storage/'.$path : null;
    }
}
