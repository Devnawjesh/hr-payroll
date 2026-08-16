<?php

namespace App\Modules\Projects\Repositories;

use App\Models\Employee;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository
{
    /**
     * @param array<string, mixed> $filters
     */
    public function paginate(array $filters, ?User $user = null): LengthAwarePaginator
    {
        $q = trim((string) ($filters['q'] ?? ''));
        $teamId = (int) ($filters['team_id'] ?? 0);
        $status = (string) ($filters['status'] ?? '');
        $perPage = max(10, min(100, (int) ($filters['per_page'] ?? 20)));

        return Project::query()
            ->with(['team:id,name,code', 'manager:id,employee_code,first_name,last_name'])
            ->withCount(['tasks', 'members'])
            ->when(! $this->canViewAll($user), fn ($query) => $this->scopeToUser($query, $user))
            ->when($q !== '', function ($query) use ($q): void {
                $query->where(function ($inner) use ($q): void {
                    $inner->where('name', 'like', "%{$q}%")
                        ->orWhere('project_code', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->when($teamId > 0, fn ($query) => $query->where('team_id', $teamId))
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function canAccess(Project $project, ?User $user): bool
    {
        if ($this->canViewAll($user)) {
            return true;
        }

        $employeeId = (int) ($user?->employee?->id ?? 0);
        if ($employeeId <= 0) {
            return false;
        }

        $project->loadMissing('team');

        return (int) $project->manager_employee_id === $employeeId
            || $project->members()->where('employees.id', $employeeId)->exists()
            || $project->team?->lead_employee_id === $employeeId
            || ($project->team !== null && $project->team->members()->where('employees.id', $employeeId)->exists());
    }

    public function create(array $attributes): Project
    {
        return Project::query()->create($attributes);
    }

    public function update(Project $project, array $attributes): void
    {
        $project->update($attributes);
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }

    /** @return Collection<int, Team> */
    public function listTeams(): Collection
    {
        return Team::query()
            ->with('members:id,employee_code,first_name,last_name,department_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
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

    public function withMembersAndTasks(Project $project): Project
    {
        return $project->load([
            'team:id,name,code,lead_employee_id',
            'team.lead:id,employee_code,first_name,last_name',
            'team.members:id,employee_code,first_name,last_name,department_id',
            'team.members.department:id,name',
            'manager:id,employee_code,first_name,last_name',
            'members:id,employee_code,first_name,last_name,department_id',
            'members.department:id,name',
            'tasks:id,project_id,title,status,priority,assigned_to_employee_id,due_date,progress_percent',
            'tasks.assignee:id,employee_code,first_name,last_name',
        ]);
    }

    private function canViewAll(?User $user): bool
    {
        return $user?->hasPermission('project.view-all') ?? false;
    }

    private function scopeToUser($query, ?User $user): void
    {
        $employeeId = (int) ($user?->employee?->id ?? 0);
        if ($employeeId <= 0) {
            $query->whereRaw('1 = 0');
            return;
        }

        $query->where(function ($inner) use ($employeeId): void {
            $inner->where('manager_employee_id', $employeeId)
                ->orWhereHas('members', fn ($memberQuery) => $memberQuery->where('employees.id', $employeeId))
                ->orWhereHas('team', fn ($teamQuery) => $teamQuery
                    ->where('lead_employee_id', $employeeId)
                    ->orWhereHas('members', fn ($teamMemberQuery) => $teamMemberQuery->where('employees.id', $employeeId)));
        });
    }
}
