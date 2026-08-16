<?php

namespace App\Modules\Teams\Repositories;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Team;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository
{
    /**
     * @param array<string, mixed> $filters
     */
    public function paginate(array $filters, ?User $user = null): LengthAwarePaginator
    {
        $q = trim((string) ($filters['q'] ?? ''));
        $departmentId = (int) ($filters['department_id'] ?? 0);
        $status = (string) ($filters['status'] ?? '');
        $perPage = max(10, min(100, (int) ($filters['per_page'] ?? 20)));

        return Team::query()
            ->with(['department:id,name', 'lead:id,employee_code,first_name,last_name'])
            ->withCount(['projects', 'members'])
            ->when(! $this->canViewAll($user), fn ($query) => $this->scopeToUser($query, $user))
            ->when($q !== '', function ($query) use ($q): void {
                $query->where(function ($inner) use ($q): void {
                    $inner->where('name', 'like', "%{$q}%")
                        ->orWhere('code', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->when($departmentId > 0, fn ($query) => $query->where('department_id', $departmentId))
            ->when($status !== '', fn ($query) => $query->where('is_active', $status === 'active'))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function canAccess(Team $team, ?User $user): bool
    {
        if ($this->canViewAll($user)) {
            return true;
        }

        $employeeId = (int) ($user?->employee?->id ?? 0);
        if ($employeeId <= 0) {
            return false;
        }

        return (int) $team->lead_employee_id === $employeeId
            || $team->members()->where('employees.id', $employeeId)->exists()
            || $team->department?->head_employee_id === $employeeId;
    }

    public function create(array $attributes): Team
    {
        return Team::query()->create($attributes);
    }

    public function update(Team $team, array $attributes): void
    {
        $team->update($attributes);
    }

    public function delete(Team $team): void
    {
        $team->delete();
    }

    /** @return Collection<int, Department> */
    public function listDepartments(): Collection
    {
        return Department::query()->orderBy('name')->get(['id', 'name']);
    }

    /** @return Collection<int, Employee> */
    public function listActiveEmployees(): Collection
    {
        return Employee::query()
            ->with('department:id,name')
            ->where('employment_status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id', 'employee_code', 'first_name', 'last_name', 'department_id']);
    }

    public function withMembers(Team $team): Team
    {
        return $team->load([
            'department:id,name',
            'lead:id,employee_code,first_name,last_name',
            'members:id,employee_code,first_name,last_name,department_id',
            'members.department:id,name',
        ]);
    }

    private function canViewAll(?User $user): bool
    {
        return $user?->hasPermission('team.view-all') ?? false;
    }

    private function scopeToUser($query, ?User $user): void
    {
        $employeeId = (int) ($user?->employee?->id ?? 0);
        if ($employeeId <= 0) {
            $query->whereRaw('1 = 0');
            return;
        }

        $query->where(function ($inner) use ($employeeId): void {
            $inner->where('lead_employee_id', $employeeId)
                ->orWhereHas('members', fn ($memberQuery) => $memberQuery->where('employees.id', $employeeId))
                ->orWhereHas('department', fn ($departmentQuery) => $departmentQuery->where('head_employee_id', $employeeId));
        });
    }
}
