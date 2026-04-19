<?php

namespace App\Modules\Designations\Repositories;

use App\Models\Department;
use App\Models\Designation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DesignationRepository
{
    /**
     * @param array<string, mixed> $filters
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $q = trim((string) ($filters['q'] ?? ''));
        $status = (string) ($filters['status'] ?? '');
        $departmentId = (int) ($filters['department_id'] ?? 0);
        $perPage = max(10, min(100, (int) ($filters['per_page'] ?? 20)));

        return Designation::query()
            ->with('department:id,name,code')
            ->withCount('employees')
            ->when($q !== '', function ($query) use ($q): void {
                $query->where(function ($inner) use ($q): void {
                    $inner
                        ->where('name', 'like', "%{$q}%")
                        ->orWhere('code', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->when($status !== '', fn ($query) => $query->where('is_active', $status === 'active'))
                ->when($departmentId > 0, fn ($query) => $query->where('department_id', $departmentId))
                ->orderBy('name')
                ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @return Collection<int, Department>
     */
    public function listDepartments(): Collection
    {
        return Department::query()
            ->select(['id', 'name', 'code'])
                ->where('is_active', true)
                ->orderBy('name')
            ->get();
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function create(array $attributes): Designation
    {
        return Designation::query()->create($attributes);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function update(Designation $designation, array $attributes): void
    {
        $designation->update($attributes);
    }

    public function delete(Designation $designation): void
    {
        $designation->delete();
    }
}
