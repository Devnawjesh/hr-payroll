<?php

namespace App\Modules\Designations\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Modules\Designations\Http\Requests\StoreDesignationRequest;
use App\Modules\Designations\Http\Requests\UpdateDesignationRequest;
use App\Modules\Designations\Repositories\DesignationRepository;
use App\Modules\Designations\Services\DesignationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignationController extends Controller
{
    public function __construct(
        private readonly DesignationRepository $designationRepository,
        private readonly DesignationService $designationService
    ) {
    }
    // Designations list
    public function index(Request $request): View
    {
        $filters = [
            'q' => trim((string) $request->input('q')),
            'status' => (string) $request->input('status', ''),
            'department_id' => (int) $request->input('department_id', 0),
            'per_page' => max(10, min(100, (int) $request->input('per_page', 20))),
        ];

        return view('hr.designations.index', [
            'designations' => $this->designationRepository->paginate($filters),
            'departments' => $this->designationRepository->listDepartments(),
            'filters' => $filters,
        ]);
    }


    // Create designation form
    public function create(): View
    {
        return view('hr.designations.form', [
            'mode' => 'create',
            'departments' => $this->designationRepository->listDepartments(),
        ]);
    }


    // Store new designation
    public function store(StoreDesignationRequest $request): RedirectResponse
    {
        $this->designationService->createDesignation($request->validated());

        return redirect()->route('designations.index')->with('success', 'Designation created successfully.');
    }
    // Edit designation form
    public function edit(Designation $designation): View
    {
        return view('hr.designations.form', [
            'mode' => 'edit',
            'designation' => $designation,
            'departments' => $this->designationRepository->listDepartments(),
        ]);
    }

    
    // Update designation
    public function update(UpdateDesignationRequest $request, Designation $designation): RedirectResponse
    {
        $this->designationService->updateDesignation($designation, $request->validated());

        return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
    }
    // Delete designation
    public function destroy(Designation $designation): RedirectResponse
    {
        $this->designationService->deleteDesignation($designation);

        return redirect()->route('designations.index')->with('success', 'Designation deleted successfully.');
    }
}
