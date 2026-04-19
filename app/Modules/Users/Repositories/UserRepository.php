<?php

namespace App\Modules\Users\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository
{
    /**
     * @param array<string, mixed> $filters
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $perPage = max(10, min(100, (int) ($filters['per_page'] ?? 20)));
        $queryText = trim((string) ($filters['q'] ?? ''));
        $status = (string) ($filters['status'] ?? '');
        $roleId = (int) ($filters['role_id'] ?? 0);

        return User::query()
            ->with(['roles:id,name', 'approvedBy:id,name'])
            ->when($queryText !== '', function ($query) use ($queryText): void {
                $query->where(function ($innerQuery) use ($queryText): void {
                    $innerQuery
                        ->where('name', 'like', "%{$queryText}%")
                        ->orWhere('email', 'like', "%{$queryText}%")
                        ->orWhere('phone', 'like', "%{$queryText}%");
                });
            })
            ->when($status !== '', fn ($query) => $query->where('account_status', $status))
            ->when($roleId > 0, fn ($query) => $query->whereHas('roles', fn ($q) => $q->where('roles.id', $roleId)))
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @param array<string, mixed> $attributes
     */

    public function create(array $attributes): User
    {
        return User::query()->create($attributes);
    }

    /**
     * @param array<string, mixed> $attributes
     */

    public function update(User $user, array $attributes): void
    {
        $user->fill($attributes);
        $user->save();
    }

    /**
     * @param array<int, array<string, mixed>> $syncPayload
     */

    public function syncRoles(User $user, array $syncPayload): void
    {
        $user->roles()->sync($syncPayload);
    }

    /**
     * Load roles for the given user.
     */
    public function loadRoles(User $user): User
    {
        $user->load('roles:id,name');

        return $user;
    }

    public function freshWithRoles(User $user): User
    {
        return $user->fresh(['roles']) ?? $user;
    }
}
