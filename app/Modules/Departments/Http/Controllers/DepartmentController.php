<?php

namespace App\Modules\Departments\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Modules\Departments\Http\Requests\StoreDepartmentRequest;
use App\Modules\Departments\Http\Requests\UpdateDepartmentRequest;
use App\Modules\Departments\Repositories\DepartmentRepository;
use App\Modules\Departments\Services\DepartmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function __construct(
        private readonly DepartmentRepository $departmentRepository,
        private readonly DepartmentService $departmentService
    ) {
    }

    public function index(Request $request): View
    {
        $filters = [
            'q' => trim((string) $request->input('q')),
            'status' => (string) $request->input('status', ''),
                'per_page' => max(10, min(100, (int) $request->input('per_page', 20))),
        ];

        return view('hr.departments.index', [
            'departments' => $this->departmentRepository->paginate($filters),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('hr.departments.form', [
            'mode' => 'create',
                'headCandidates' => $this->departmentRepository->listHeadCandidates(),
        ]);
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        $this->departmentService->createDepartment($request->validated());

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(Department $department): View
    {
        return view('hr.departments.form', [
            'mode' => 'edit',
            'department' => $department,
            'headCandidates' => $this->departmentRepository->listHeadCandidates(),
        ]);
    }

    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $this->departmentService->updateDepartment($department, $request->validated());

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $this->departmentService->deleteDepartment($department);

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
