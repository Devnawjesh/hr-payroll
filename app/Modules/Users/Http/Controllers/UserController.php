<?php

namespace App\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Users\Http\Requests\ApproveUserRequest;
use App\Modules\Users\Http\Requests\StoreUserRequest;
use App\Modules\Users\Http\Requests\UpdateUserRequest;
use App\Modules\Users\Repositories\RoleRepository;
use App\Modules\Users\Repositories\UserRepository;
use App\Modules\Users\Services\UserManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    // UserController constructor.
    public function __construct(
        private readonly UserManagementService $userManagementService,
        private readonly UserRepository $userRepository,
        private readonly RoleRepository $roleRepository
    ) {}

    // Users list
    public function index(Request $request): View
    {
        $filters = [
            'per_page' => max(10, min(100, (int) $request->input('per_page', 20))),
            'q' => trim((string) $request->input('q')),
            'status' => (string) $request->input('status', ''),
            'role_id' => (int) $request->input('role_id', 0),
        ];

        $users = $this->userRepository->paginate($filters);

        return view('hr.users.index', [
            'users' => $users,
            'roles' => $this->roleRepository->listForSelect(),
            'filters' => $filters,
        ]);
    }
    // User create form

    public function create(): View
    {
        return view('hr.users.create', [
            'roles' => $this->roleRepository->listForSelect(),
            'statuses' => ['active', 'pending_approval', 'inactive'],
            'mode' => 'create',
        ]);
    }

    // Store new user
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->userManagementService->createUser($request->validated(), (int) $request->user()->id);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // User edit form
    public function edit(User $user): View
    {
        $this->userRepository->loadRoles($user);

        return view('hr.users.create', [
            'user' => $user,
            'roles' => $this->roleRepository->listForSelect(),
            'statuses' => ['pending_approval', 'active', 'inactive', 'rejected'],
            'mode' => 'edit',
        ]);
    }

        // Update user
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->userManagementService->updateUser($user, $request->validated(), (int) $request->user()->id);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // User approval form
    public function approval(User $user): View
    {
        $this->userRepository->loadRoles($user);

        return view('hr.users.approval', [
            'user' => $user,
            'roles' => $this->roleRepository->listForSelect(),
        ]);
    }

    // Approve or reject user signup request
    public function approveOrReject(ApproveUserRequest $request, User $user): RedirectResponse
    {
        $this->userManagementService->approveOrReject($user, $request->validated(), (int) $request->user()->id);

        return redirect()->route('users.index')->with('success', 'Signup request processed successfully.');
    }
}
