<?php

namespace App\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Modules\Users\Http\Requests\StorePermissionRequest;
use App\Modules\Users\Http\Requests\UpdatePermissionRequest;
use App\Modules\Users\Repositories\PermissionRepository;
use App\Modules\Users\Services\RolePermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function __construct(
        private readonly RolePermissionService $rolePermissionService,
        private readonly PermissionRepository $permissionRepository
    ) {}

    /**
     * Permissions list
     */

    public function index(Request $request): View
    {
        $filters = [
            'q' => trim((string) $request->input('q')),
            'group_name' => trim((string) $request->input('group_name')),
            'per_page' => max(10, min(100, (int) $request->input('per_page', 25))),
        ];

        $permissions = $this->permissionRepository->paginate($filters);

            return view('hr.permissions.index', [
            'permissions' => $permissions,
            'groups' => $this->permissionRepository->listGroups(),
            'filters' => $filters,
        ]);
    }

    // Permission create form
    public function create(): View
    {
        return view('hr.permissions.form', ['mode' => 'create']);
    }


    // Store new permission
    public function store(StorePermissionRequest $request): RedirectResponse
    {
        $this->rolePermissionService->createPermission($request->validated());
        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    // Permission edit form
    public function edit(Permission $permission): View
    {
        return view('hr.permissions.form', [
            'mode' => 'edit',
             'permission' => $permission,
        ]);
    }
    
     // Update permission
    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        $this->rolePermissionService->updatePermission($permission, $request->validated());

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }
}
