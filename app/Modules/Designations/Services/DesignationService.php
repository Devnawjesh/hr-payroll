<?php

namespace App\Modules\Designations\Services;

use App\Models\Designation;
use App\Modules\Designations\Repositories\DesignationRepository;
use Illuminate\Support\Facades\DB;

class DesignationService
{
    public function __construct(private readonly DesignationRepository $designationRepository)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function createDesignation(array $payload): Designation
    {
        return DB::transaction(function () use ($payload): Designation {
            return $this->designationRepository->create([
                'department_id' => $payload['department_id'] ?? null,
                'name' => $payload['name'],
                'code' => $payload['code'] ?? null,
                'description' => $payload['description'] ?? null,
                    'is_active' => (bool) ($payload['is_active'] ?? true),
            ]);
        });
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function updateDesignation(Designation $designation, array $payload): Designation
    {
        return DB::transaction(function () use ($designation, $payload): Designation {
            $this->designationRepository->update($designation, [
                'department_id' => $payload['department_id'] ?? null,
                'name' => $payload['name'],
                'code' => $payload['code'] ?? null,
                    'description' => $payload['description'] ?? null,
                'is_active' => (bool) ($payload['is_active'] ?? true),
            ]);

            return $designation->fresh() ?? $designation;
        });
    }

    public function deleteDesignation(Designation $designation): void
    {
        DB::transaction(function () use ($designation): void {
            $this->designationRepository->delete($designation);
        });
    }
}
