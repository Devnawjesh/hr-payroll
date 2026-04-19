<?php

namespace App\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Modules\Users\Http\Requests\StoreRoleRequest;
use App\Modules\Users\Http\Requests\SyncRolePermissionsRequest;
use App\Modules\Users\Http\Requests\UpdateRoleRequest;
use App\Modules\Users\Repositories\PermissionRepository;
use App\Modules\Users\Repositories\RoleRepository;
use App\Modules\Users\Services\RolePermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    /***
     * RoleController constructor.
     */
    public function __construct(
        private readonly RolePermissionService $rolePermissionService,
        private readonly RoleRepository $roleRepository,
        private readonly PermissionRepository $permissionRepository
    ) {}
    // Roles list
    public function index(Request $request): View
    {
        $filters = [
            'q' => trim((string) $request->input('q')),
            'per_page' => max(10, min(100, (int) $request->input('per_page', 20))),
        ];
        $roles = $this->roleRepository->paginate($filters);

        return view('hr.roles.index', [
            'roles' => $roles,
            'filters' => $filters,
        ]);
    }

    // Role create form
    public function create(): View
    {
        return view('hr.roles.form', ['mode' => 'create']);
    }

    // Store new role
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $this->rolePermissionService->createRole($request->validated());

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

        // Role edit form
    public function edit(Role $role): View
    {
        return view('hr.roles.form', [
            'mode' => 'edit',
            'role' => $role,
        ]);
    }

        // Update role
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $this->rolePermissionService->updateRole($role, $request->validated());

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }
    // Role permissions management form
    public function permissions(Role $role): View
    {
        $this->roleRepository->loadPermissionIds($role);
        $permissionsByGroup = $this->permissionRepository->groupedByName();

        return view('hr.roles.permissions', [
            'role' => $role,
            'permissionsByGroup' => $permissionsByGroup,
            'selectedPermissionIds' => $role->permissions->pluck('id')->all(),
        ]);
    }

    // Sync role permissions
    public function syncPermissions(SyncRolePermissionsRequest $request, Role $role): RedirectResponse
    {
        $payload = $request->validated();


        $this->rolePermissionService->syncRolePermissions(
             $role,
            $payload['permission_ids'] ?? [],
            (int) $request->user()->id
        );

        return redirect()->route('roles.permissions', $role)->with('success', 'Role permissions updated successfully.');
    }
}
