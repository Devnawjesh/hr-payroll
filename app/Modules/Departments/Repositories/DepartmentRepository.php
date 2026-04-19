<?php

namespace App\Modules\Departments\Repositories;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DepartmentRepository
{
    /**
     * @param array<string, mixed> $filters
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $q = trim((string) ($filters['q'] ?? ''));
        $status = (string) ($filters['status'] ?? '');
        $perPage = max(10, min(100, (int) ($filters['per_page'] ?? 20)));

        return Department::query()
            ->with(['head:id,first_name,last_name,employee_code'])
            ->withCount(['employees', 'designations'])
            ->when($q !== '', function ($query) use ($q): void {
                $query->where(function ($inner) use ($q): void {
                    $inner
                        ->where('name', 'like', "%{$q}%")
                        ->orWhere('code', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->when($status !== '', fn ($query) => $query->where('is_active', $status === 'active'))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @return Collection<int, Employee>
     */
    public function listHeadCandidates(): Collection
    {
        return Employee::query()
            ->select(['id', 'employee_code', 'first_name', 'last_name'])
            ->where('employment_status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function create(array $attributes): Department
    {
        return Department::query()->create($attributes);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function update(Department $department, array $attributes): void
    {
        $department->update($attributes);
    }

    public function delete(Department $department): void
    {
        $department->delete();
    }
}
