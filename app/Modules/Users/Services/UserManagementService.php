<?php

namespace App\Modules\Users\Services;

use App\Models\User;
use App\Modules\Users\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */

    public function createUser(array $payload, int $actorId): User
    {
        return DB::transaction(function () use ($payload, $actorId): User {
             $status = $payload['account_status'] ?? 'active';

            $user = $this->userRepository->create([
                'name' => $payload['name'],
                'email' => $payload['email'],
                'phone' => $payload['phone'] ?? null,
                'password' => Hash::make($payload['password']),
                'account_status' => $status,
                'approved_by' => $status === 'active' ? $actorId : null,
                'approved_at' => $status === 'active' ? now() : null,
                'rejected_reason' => null,
            ]);

            $this->syncRoles($user, $payload['role_ids'] ?? [], $actorId);

            return $user;
        });
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function updateUser(User $user, array $payload, int $actorId): User
    {
        return DB::transaction(function () use ($user, $payload, $actorId): User {
            $status = $payload['account_status'] ?? $user->account_status;

            $attributes = [
                'name' => $payload['name'],
                'email' => $payload['email'],
                'phone' => $payload['phone'] ?? null,
                'account_status' => $status,
            ];

            if (! empty($payload['password'])) {
                $attributes['password'] = Hash::make($payload['password']);
            }
            // If status changed to active and user was not previously approved, set approval fields
            if ($status === 'active' && $user->approved_at === null) {
                $attributes['approved_by'] = $actorId;
                $attributes['approved_at'] = now();
                $attributes['rejected_reason'] = null;
            }

            if ($status === 'rejected') {
                $attributes['rejected_reason'] = $payload['rejected_reason'] ?? $user->rejected_reason;
            }

            $this->userRepository->update($user, $attributes);

            if (array_key_exists('role_ids', $payload)) {
                $this->syncRoles($user, $payload['role_ids'] ?? [], $actorId);
            }

            return $this->userRepository->freshWithRoles($user);
        });
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function approveOrReject(User $user, array $payload, int $actorId): User
    {
        return DB::transaction(function () use ($user, $payload, $actorId): User {
            $decision = $payload['decision'];
            
            // Update user status based on decision
            if ($decision === 'approve') {
                $this->userRepository->update($user, [
                    'account_status' => 'active',
                    'approved_by' => $actorId,
                    'approved_at' => now(),
                    'rejected_reason' => null,
                ]);
            } else {
                $this->userRepository->update($user, [
                    'account_status' => 'rejected',
                    'rejected_reason' => $payload['rejected_reason'] ?? 'Rejected by admin',
                ]);
            }

            if ($decision === 'approve') {
                $this->syncRoles($user, $payload['role_ids'] ?? [], $actorId);
            }

            return $this->userRepository->freshWithRoles($user);
        });
    }

    /**
     * @param array<int, int|string> $roleIds
     */
    public function syncRoles(User $user, array $roleIds, int $actorId): void
    {
        $syncPayload = [];
        $timestamp = now();
        foreach ($roleIds as $roleId) {
            $syncPayload[(int) $roleId] = [
                'assigned_by' => $actorId,
                'assigned_at' => $timestamp,
            ];
        }

        $this->userRepository->syncRoles($user, $syncPayload);
    }
}
