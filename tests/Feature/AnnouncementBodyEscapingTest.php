<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AnnouncementBodyEscapingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('account_status')->default('active');
            $table->foreignId('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table): void {
            $table->id();
            $table->string('group_name')->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('role_users', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('role_id');
            $table->foreignId('user_id');
            $table->foreignId('assigned_by')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();
        });

        Schema::create('permission_roles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('permission_id');
            $table->foreignId('role_id');
            $table->foreignId('granted_by')->nullable();
            $table->timestamp('granted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('announcements', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('announcement_type', 30)->default('announcement');
            $table->longText('body');
            $table->string('audience_type', 40)->default('all');
            $table->json('audience_employee_ids')->nullable();
            $table->enum('priority', ['normal', 'high'])->default('normal');
            $table->string('attachment_path')->nullable();
            $table->timestamp('publish_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable();
            $table->foreignId('published_by')->nullable();
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employees', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique();
            $table->string('employee_code', 50)->unique();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('employment_status', 30)->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('employees');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('permission_roles');
        Schema::dropIfExists('role_users');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    public function test_announcement_body_is_escaped_for_approver_review(): void
    {
        $approver = User::factory()->create([
            'account_status' => 'active',
        ]);
        $approver->roles()->attach($this->roleWithPermissions('approver', ['announcement.approve']), [
            'assigned_at' => now(),
        ]);

        $payload = '<img src=x onerror="alert(1)">';
        $announcement = Announcement::query()->create([
            'title' => 'Policy Update',
            'announcement_type' => 'notice',
            'body' => $payload,
            'audience_type' => 'all',
            'priority' => 'normal',
            'is_active' => true,
            'approval_status' => 'pending',
            'created_by' => $approver->id,
        ]);

        $this->actingAs($approver)
            ->get(route('announcements.show', $announcement))
            ->assertOk()
            ->assertDontSee($payload, false)
            ->assertSee(e($payload), false);
    }

    /**
     * @param array<int, string> $permissionSlugs
     */
    private function roleWithPermissions(string $slug, array $permissionSlugs): Role
    {
        $role = Role::query()->create([
            'name' => str($slug)->headline()->toString(),
            'slug' => $slug,
        ]);

        $permissions = collect($permissionSlugs)->map(fn (string $permissionSlug): Permission => Permission::query()->firstOrCreate(
            ['slug' => $permissionSlug],
            [
                'group_name' => 'test',
                'name' => str($permissionSlug)->headline()->toString(),
            ]
        ));

        $role->permissions()->attach($permissions->pluck('id')->all(), ['granted_at' => now()]);

        return $role;
    }
}
