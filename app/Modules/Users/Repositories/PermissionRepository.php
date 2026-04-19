<?php

namespace App\Modules\Users\Repositories;

use App\Models\Permission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PermissionRepository
{
    /**
     * @param array<string, mixed> $filters
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $q = trim((string) ($filters['q'] ?? ''));
        $groupName = trim((string) ($filters['group_name'] ?? ''));
        $perPage = max(10, min(100, (int) ($filters['per_page'] ?? 25)));

        return Permission::query()
            ->when($q !== '', function ($query) use ($q): void {
                $query->where(function ($inner) use ($q): void {
                    $inner->where('name', 'like', "%{$q}%")
                        ->orWhere('slug', 'like', "%{$q}%");
                });
            })
            ->when($groupName !== '', fn ($query) => $query->where('group_name', $groupName))
            ->orderBy('group_name')
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @return array<int, string>
     */


    public function listGroups(): array
    {
        return Permission::query()
            ->select('group_name')
            ->distinct()
            ->orderBy('group_name')
            ->pluck('group_name')
            ->all();
    }

    /**
     * @return Collection<string, Collection<int, Permission>>
     */

    
    public function groupedByName(): Collection
    {
        /** @var Collection<string, Collection<int, Permission>> $grouped */
        $grouped = Permission::query()
            ->orderBy('group_name')
            ->orderBy('name')
            ->get()
            ->groupBy('group_name');

        return $grouped;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function create(array $attributes): Permission
    {
        return Permission::query()->create($attributes);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    
    public function update(Permission $permission, array $attributes): void
    {
        $permission->update($attributes);
    }
}
