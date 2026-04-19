<?php

namespace App\Modules\Users\Repositories;

use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository
{
    /**
     * @param array<string, mixed> $filters
     */

    public function paginate(array $filters): LengthAwarePaginator
    {
        $q = trim((string) ($filters['q'] ?? ''));
        $perPage = max(10, min(100, (int) ($filters['per_page'] ?? 20)));

        return Role::query()
            ->withCount(['users', 'permissions'])
            ->when($q !== '', fn ($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }
    
     /**
      * @return Collection<int, Role>
      */
    public function listForSelect(): Collection
    {
        return Role::query()->orderBy('name')->get(['id', 'name']);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function create(array $attributes): Role
    {
        return Role::query()->create($attributes);
    }

    /**
     * @param array<string, mixed> $attributes
     */


    public function update(Role $role, array $attributes): void
    {
        $role->update($attributes);
    }

    /**
     * @param array<int, array<string, mixed>> $syncPayload
     */


    public function syncPermissions(Role $role, array $syncPayload): void
    {
        $role->permissions()->sync($syncPayload);
    }
    /**
     * Load only permission IDs for the given role.
     */
    public function loadPermissionIds(Role $role): Role
    {
        $role->load('permissions:id');

        return $role;
    }
}
