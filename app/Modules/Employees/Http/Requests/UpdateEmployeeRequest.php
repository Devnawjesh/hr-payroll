<?php

namespace App\Modules\Employees\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        /** @var Employee $employee */
        $employee = $this->route('employee');

        return [
            'user_id' => ['nullable', 'integer', 'exists:users,id', Rule::unique(Employee::class, 'user_id')->ignore($employee->id)],
            'employee_code' => ['nullable', 'string', 'max:50', Rule::unique(Employee::class, 'employee_code')->ignore($employee->id)],
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'date_of_birth' => ['required', 'regex:/^(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/'],
            'blood_group' => ['nullable', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
            'nid_number' => ['nullable', 'string', 'max:64', Rule::unique(Employee::class, 'nid_number')->ignore($employee->id)],
            'passport_number' => ['nullable', 'string', 'max:64', Rule::unique(Employee::class, 'passport_number')->ignore($employee->id)],
            'tax_id' => ['nullable', 'string', 'max:64', Rule::unique(Employee::class, 'tax_id')->ignore($employee->id)],
            'phone' => ['required', 'string', 'max:30'],
            'alternate_phone' => ['nullable', 'string', 'max:30'],
            'work_email' => ['nullable', 'email', 'max:255', Rule::unique(Employee::class, 'work_email')->ignore($employee->id)],
            'personal_email' => ['nullable', 'email', 'max:255'],
            'marital_status' => ['nullable', Rule::in(['single', 'married', 'divorced', 'widowed'])],
            'marriage_date' => ['nullable', 'regex:/^(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/'],
            'date_of_joining' => ['required', 'date'],

            
            'probation_end_date' => ['nullable', 'date', 'after_or_equal:date_of_joining'],
            'termination_date' => ['nullable', 'date', 'after_or_equal:date_of_joining'],
            'employment_type' => ['required', Rule::in(['full_time', 'part_time', 'contract', 'intern'])],
            'employment_status' => ['required', Rule::in(['active', 'inactive', 'on_leave', 'on_notice', 'resigned', 'terminated'])],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
             'designation_id' => ['required', 'integer', 'exists:designations,id'],
            'salary_grade_id' => ['nullable', 'integer', 'exists:salary_grades,id'],
            'reports_to_id' => ['nullable', 'integer', 'exists:employees,id', Rule::notIn([$employee->id])],
            'notes' => ['nullable', 'string', 'max:5000'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],

            'addresses' => ['required', 'array', 'min:1'],
            'addresses.*.address_type' => ['required', 'string', 'max:30'],
            'addresses.*.line_1' => ['required', 'string', 'max:255'],
            'addresses.*.line_2' => ['nullable', 'string', 'max:255'],
            'addresses.*.city' => ['required', 'string', 'max:120'],
            'addresses.*.state' => ['nullable', 'string', 'max:120'],
            'addresses.*.postal_code' => ['nullable', 'string', 'max:30'],
            'addresses.*.country' => ['required', 'string', 'max:100'],
            'addresses.*.is_primary' => ['nullable', 'boolean'],

            'bank_accounts' => ['nullable', 'array'],
            'bank_accounts.*.bank_name' => ['nullable', 'string', 'max:255'],
            'bank_accounts.*.branch_name' => ['nullable', 'string', 'max:255'],
            'bank_accounts.*.account_holder_name' => ['nullable', 'string', 'max:255'],
            'bank_accounts.*.account_number' => ['nullable', 'string', 'max:255'],
            'bank_accounts.*.routing_number' => ['nullable', 'string', 'max:255'],
            'bank_accounts.*.account_type' => ['nullable', 'string', 'max:30'],
            'bank_accounts.*.is_primary' => ['nullable', 'boolean'],

            'emergency_contacts' => ['nullable', 'array'],
            'emergency_contacts.*.name' => ['nullable', 'string', 'max:255'],
            'emergency_contacts.*.relationship' => ['nullable', 'string', 'max:50'],
            'emergency_contacts.*.phone' => ['nullable', 'string', 'max:30'],
            'emergency_contacts.*.email' => ['nullable', 'email', 'max:255'],
            'emergency_contacts.*.address' => ['nullable', 'string', 'max:255'],
            'emergency_contacts.*.is_primary' => ['nullable', 'boolean'],

            'documents' => ['nullable', 'array'],
            'documents.*.document_type' => ['nullable', 'string', 'max:60'],
            'documents.*.title' => ['nullable', 'string', 'max:255'],
            'documents.*.file_path' => ['nullable', 'string', 'max:2048'],
            'documents.*.file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp,doc,docx,xls,xlsx', 'max:10240'],
            'documents.*.issued_date' => ['nullable', 'date'],
            'documents.*.expiry_date' => ['nullable', 'date'],
        ];
    }
}
