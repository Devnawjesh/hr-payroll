<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Cached permission slugs for current request lifecycle.
     *
     * @var array<int, string>|null
     */
    protected ?array $resolvedPermissionSlugs = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'account_status',
        'approved_by',
        'approved_at',
        'rejected_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'approved_at' => 'datetime',
        ];
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_users')
            ->withPivot(['assigned_by', 'assigned_at'])
            ->withTimestamps();
    }

    public function hasRole(string $slug): bool
    {
        return $this->roles()->where('slug', $slug)->exists();
    }

    public function hasPermission(string $permissionSlug): bool
    {
        return in_array($permissionSlug, $this->permissionSlugs(), true);
    }

    /**
     * @param array<int, string> $permissionSlugs
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        $available = $this->permissionSlugs();
        foreach ($permissionSlugs as $permissionSlug) {
            if (in_array($permissionSlug, $available, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<int, string>
     */
    public function permissionSlugs(): array
    {
        if ($this->resolvedPermissionSlugs !== null) {
            return $this->resolvedPermissionSlugs;
        }

        $slugs = $this->roles()
            ->with('permissions:id,slug')
            ->get()
            ->flatMap(fn (Role $role) => $role->permissions->pluck('slug'))
            ->unique()
            ->values()
            ->all();

        $this->resolvedPermissionSlugs = $slugs;

        return $this->resolvedPermissionSlugs;
    }

    public function isActive(): bool
    {
        return $this->account_status === 'active';
    }
}
