<?php

namespace App\Modules\Users\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Modules\Users\Repositories\PermissionRepository;
use App\Modules\Users\Repositories\RoleRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RolePermissionService
{
    public function __construct(
        private readonly RoleRepository $roleRepository,
        private readonly PermissionRepository $permissionRepository
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function createRole(array $payload): Role
    {
         return $this->roleRepository->create([
            'name' => $payload['name'],
            'slug' => Str::slug($payload['name']),
            'description' => $payload['description'] ?? null,
            'is_system' => false,
        ]);
    }
    /**
     * @param array<string, mixed> $payload
     */
    public function updateRole(Role $role, array $payload): Role
    {
         $this->roleRepository->update($role, [
            'name' => $payload['name'],
            'slug' => Str::slug($payload['name']),
            'description' => $payload['description'] ?? null,
        ]);

        return $role->fresh();
    }

    /**
     * @param array<int, int|string> $permissionIds
     */
    public function syncRolePermissions(Role $role, array $permissionIds, int $actorId): void
    {
        DB::transaction(function () use ($role, $permissionIds, $actorId): void {
            $syncPayload = [];
            $timestamp = now();

            foreach ($permissionIds as $permissionId) {
                  $syncPayload[(int) $permissionId] = [
                    'granted_by' => $actorId,
                    'granted_at' => $timestamp,
                ];
            }

            $this->roleRepository->syncPermissions($role, $syncPayload);
        });
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function createPermission(array $payload): Permission
    {
        return $this->permissionRepository->create([
            'group_name' => Str::slug($payload['group_name'], '_'),
            'name' => $payload['name'],
            'slug' => $payload['slug'] ?? $this->makePermissionSlug($payload['group_name'], $payload['name']),
            'description' => $payload['description'] ?? null,
        ]);
    }

    /**
     * @param array<string, mixed> $payload
     */

    public function updatePermission(Permission $permission, array $payload): Permission
    {
        $this->permissionRepository->update($permission, [
            'group_name' => Str::slug($payload['group_name'], '_'),
            'name' => $payload['name'],
            'slug' => $payload['slug'] ?? $this->makePermissionSlug($payload['group_name'], $payload['name']),
            'description' => $payload['description'] ?? null,
        ]);

        return $permission->fresh();
    }
    /// Generate a unique slug for the permission based on its group and name.
    private function makePermissionSlug(string $group, string $name): string
    {
        $normalizedGroup = Str::slug($group, '.');
        $normalizedName = Str::slug($name, '-');

        return $normalizedGroup.'.'.$normalizedName;
    }
}
