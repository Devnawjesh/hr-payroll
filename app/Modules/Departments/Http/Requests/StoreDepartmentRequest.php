<?php

namespace App\Modules\Departments\Http\Requests;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDepartmentRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:120', Rule::unique(Department::class, 'name')->whereNull('deleted_at')],
            'code' => ['nullable', 'string', 'max:30', Rule::unique(Department::class, 'code')->whereNull('deleted_at')],
            'head_employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
