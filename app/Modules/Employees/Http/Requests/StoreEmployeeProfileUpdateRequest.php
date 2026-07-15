<?php

namespace App\Modules\Employees\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->employee !== null;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $employeeId = (int) ($this->user()?->employee?->id ?? 0);

        return [
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['nullable', 'string', 'max:120'],
            'gender' => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'nid_number' => ['nullable', 'string', 'max:64', Rule::unique(Employee::class, 'nid_number')->ignore($employeeId)],
            'passport_number' => ['nullable', 'string', 'max:64', Rule::unique(Employee::class, 'passport_number')->ignore($employeeId)],
            'tax_id' => ['nullable', 'string', 'max:64', Rule::unique(Employee::class, 'tax_id')->ignore($employeeId)],
            'phone' => ['nullable', 'string', 'max:30'],
            'alternate_phone' => ['nullable', 'string', 'max:30'],
            'marital_status' => ['nullable', 'in:single,married,divorced,widowed'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'addresses' => ['nullable', 'array'],
            'addresses.*.address_type' => ['nullable', 'string', 'max:30'],
            'addresses.*.line_1' => ['nullable', 'string', 'max:255'],
            'addresses.*.line_2' => ['nullable', 'string', 'max:255'],
            'addresses.*.city' => ['nullable', 'string', 'max:120'],
            'addresses.*.state' => ['nullable', 'string', 'max:120'],
            'addresses.*.postal_code' => ['nullable', 'string', 'max:30'],
            'addresses.*.country' => ['nullable', 'string', 'max:100'],
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
            'documents.*.issued_date' => ['nullable', 'date'],
            'documents.*.expiry_date' => ['nullable', 'date'],
        ];
    }
}
