<?php

namespace App\Modules\Tasks\Repositories;

use App\Models\Employee;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository
{
    /**
     * @param array<string, mixed> $filters
     */
    public function paginate(array $filters, ?User $user = null): LengthAwarePaginator
    {
        $q = trim((string) ($filters['q'] ?? ''));
        $projectId = (int) ($filters['project_id'] ?? 0);
        $status = (string) ($filters['status'] ?? '');
        $assigneeId = (int) ($filters['assigned_to_employee_id'] ?? 0);
        $perPage = max(10, min(100, (int) ($filters['per_page'] ?? 20)));

        return Task::query()
            ->with([
                'project:id,name,project_code',
                'assignee:id,employee_code,first_name,last_name',
                'creator:id,employee_code,first_name,last_name',
            ])
            ->when(! $this->canViewAll($user), fn ($query) => $this->scopeToUser($query, $user))
            ->when($q !== '', fn ($query) => $query->where('title', 'like', "%{$q}%"))
            ->when($projectId > 0, fn ($query) => $query->where('project_id', $projectId))
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($assigneeId > 0, fn ($query) => $query->where('assigned_to_employee_id', $assigneeId))
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function canAccess(Task $task, ?User $user): bool
    {
        if ($this->canViewAll($user)) {
            return true;
        }

        $employeeId = (int) ($user?->employee?->id ?? 0);
        if ($employeeId <= 0) {
            return false;
        }

        $subordinateIds = $user?->employee?->subordinates()->pluck('id')->map(fn ($id) => (int) $id)->all() ?? [];
        $task->loadMissing('project');

        return in_array((int) $task->assigned_to_employee_id, array_merge([$employeeId], $subordinateIds), true)
            || (int) $task->created_by_employee_id === $employeeId
            || $task->project?->manager_employee_id === $employeeId
            || ($task->project !== null && $task->project->members()->where('employees.id', $employeeId)->exists());
    }

    public function create(array $attributes): Task
    {
        return Task::query()->create($attributes);
    }

    public function update(Task $task, array $attributes): void
    {
        $task->update($attributes);
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    /** @return Collection<int, Project> */
    public function listProjects(): Collection
    {
        return Project::query()->orderBy('name')->get(['id', 'name', 'project_code']);
    }

    /** @return Collection<int, Employee> */
    public function listActiveEmployees(): Collection
    {
        return Employee::query()
            ->where('employment_status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id', 'employee_code', 'first_name', 'last_name']);
    }

    public function withComments(Task $task): Task
    {
        return $task->load([
            'project:id,name,project_code',
            'assignee:id,employee_code,first_name,last_name',
            'creator:id,employee_code,first_name,last_name',
            'comments:id,task_id,employee_id,comment,created_at',
            'comments.employee:id,employee_code,first_name,last_name',
        ]);
    }

    private function canViewAll(?User $user): bool
    {
        return $user?->hasPermission('task.view-all') ?? false;
    }

    private function scopeToUser($query, ?User $user): void
    {
        $employeeId = (int) ($user?->employee?->id ?? 0);
        if ($employeeId <= 0) {
            $query->whereRaw('1 = 0');
            return;
        }

        $subordinateIds = $user?->employee?->subordinates()->pluck('id')->map(fn ($id) => (int) $id)->all() ?? [];
        $visibleEmployeeIds = array_values(array_unique(array_merge([$employeeId], $subordinateIds)));

        $query->where(function ($inner) use ($employeeId, $visibleEmployeeIds): void {
            $inner->whereIn('assigned_to_employee_id', $visibleEmployeeIds)
                ->orWhere('created_by_employee_id', $employeeId)
                ->orWhereHas('project', fn ($projectQuery) => $projectQuery
                    ->where('manager_employee_id', $employeeId)
                    ->orWhereHas('members', fn ($memberQuery) => $memberQuery->where('employees.id', $employeeId)));
        });
    }

    public function addComment(int $taskId, ?int $employeeId, string $comment): TaskComment
    {
        return TaskComment::query()->create([
            'task_id' => $taskId,
            'employee_id' => $employeeId,
            'comment' => $comment,
        ]);
    }
}
