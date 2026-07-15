<?php

namespace App\Modules\Employees\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Modules\Employees\Http\Requests\StoreEmployeeRequest;
use App\Modules\Employees\Http\Requests\UpdateEmployeeRequest;
use App\Modules\Employees\Repositories\EmployeeRepository;
use App\Modules\Employees\Services\EmployeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
          private readonly EmployeeService $employeeService
    ) {
    }
    /// Display a listing of the resource.
    public function index(Request $request): View
    {
        $filters = [
            'q' => trim((string) $request->input('q')),
            'department_id' => (int) $request->input('department_id', 0),
            'designation_id' => (int) $request->input('designation_id', 0),
            'employment_status' => (string) $request->input('employment_status', ''),
            'employment_type' => (string) $request->input('employment_type', ''),
            'per_page' => max(10, min(100, (int) $request->input('per_page', 20))),
        ];

        return view('hr.employees.index', [
            'employees' => $this->employeeRepository->paginate($filters, $request->user()),
            'departments' => $this->employeeRepository->listDepartments($request->user()),
             'designations' => $this->employeeRepository->listDesignations($request->user()),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('hr.employees.form', [
            'mode' => 'create',
            'departments' => $this->employeeRepository->listDepartments(request()->user()),
             'designations' => $this->employeeRepository->listDesignations(request()->user()),
             'salaryGrades' => $this->employeeRepository->listSalaryGrades(),
            'managers' => $this->employeeRepository->listManagers(),
            'users' => $this->employeeRepository->listUsersForLinking(),
        ]);
    }


    // Store a newly created resource in storage.
    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $this->employeeService->createEmployee(
            $request->validated(),
            $request->file('avatar')
        );

        return redirect()->route('employees.index')->with('success', __('Employee created successfully.'));
    }

    public function show(Employee $employee): View
    {
        abort_if(! $this->employeeRepository->canAccess($employee, request()->user()), 403);

        return view('hr.employees.show', [
            'employee' => $this->employeeRepository->withDetails($employee),
        ]);
    }

    public function organizationStructure(Request $request): View
    {
        $authEmployee = $request->user()?->employee;

        return view('hr.employees.organization_structure', [
            'employees' => $this->employeeRepository->listForOrganizationStructure(),
            'authEmployee' => $authEmployee,
            'supervisorChain' => $authEmployee ? $this->employeeRepository->supervisorChain($authEmployee) : collect(),
            'mySubordinates' => $authEmployee
                ? $authEmployee->subordinates()->with(['department:id,name', 'designation:id,name'])->orderBy('first_name')->orderBy('last_name')->get([
                    'id',
                    'employee_code',
                    'first_name',
                    'last_name',
                    'department_id',
                    'designation_id',
                    'reports_to_id',
                ])
                : collect(),
        ]);
    }
    // Show the form for editing the specified resource.
    public function edit(Employee $employee): View
    {
        abort_if(! $this->employeeRepository->canAccess($employee, request()->user()), 403);

        return view('hr.employees.form', [
             'mode' => 'edit',
             'employee' => $employee->load(['addresses', 'bankAccounts', 'emergencyContacts', 'documents']),
             'departments' => $this->employeeRepository->listDepartments(request()->user()),
            'designations' => $this->employeeRepository->listDesignations(request()->user()),
            'salaryGrades' => $this->employeeRepository->listSalaryGrades(),
            'managers' => $this->employeeRepository->listManagers($employee->id),
            'users' => $this->employeeRepository->listUsersForLinking($employee->user_id),
        ]);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        abort_if(! $this->employeeRepository->canAccess($employee, $request->user()), 403);

        $this->employeeService->updateEmployee(
            $employee,
            $request->validated(),
            $request->file('avatar')
        );

        return redirect()->route('employees.index')->with('success', __('Employee updated successfully.'));
    }
    
    // Remove the specified resource from storage.
    public function destroy(Employee $employee): RedirectResponse
    {
        abort_if(! $this->employeeRepository->canAccess($employee, request()->user()), 403);

        $this->employeeService->deleteEmployee($employee);

        return redirect()->route('employees.index')->with('success', __('Employee deleted successfully.'));
    }
}
