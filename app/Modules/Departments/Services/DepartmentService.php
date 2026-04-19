<?php

namespace App\Modules\Departments\Services;

use App\Models\Department;
use App\Modules\Departments\Repositories\DepartmentRepository;
use Illuminate\Support\Facades\DB;

class DepartmentService
{
    public function __construct(private readonly DepartmentRepository $departmentRepository)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function createDepartment(array $payload): Department
    {
        return DB::transaction(function () use ($payload): Department {
            return $this->departmentRepository->create([
                'name' => $payload['name'],
                'code' => $payload['code'] ?? null,
                'head_employee_id' => $payload['head_employee_id'] ?? null,
                'description' => $payload['description'] ?? null,
                'is_active' => (bool) ($payload['is_active'] ?? true),
            ]);
        });
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function updateDepartment(Department $department, array $payload): Department
    {
        return DB::transaction(function () use ($department, $payload): Department {
            $this->departmentRepository->update($department, [
                'name' => $payload['name'],
                'code' => $payload['code'] ?? null,
                'head_employee_id' => $payload['head_employee_id'] ?? null,
                'description' => $payload['description'] ?? null,
                'is_active' => (bool) ($payload['is_active'] ?? true),
            ]);

            return $department->fresh() ?? $department;
        });
    }

    public function deleteDepartment(Department $department): void
    {
        DB::transaction(function () use ($department): void {
            $this->departmentRepository->delete($department);
        });
    }
}
