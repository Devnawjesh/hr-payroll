<?php

namespace App\Modules\Departments\Http\Requests;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
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
        /** @var Department $department */
        $department = $this->route('department');

        return [
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique(Department::class, 'name')
                    ->ignore($department->id)
                    ->whereNull('deleted_at'),
            ],
            'code' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique(Department::class, 'code')
                    ->ignore($department->id)
                    ->whereNull('deleted_at'),
            ],
            'head_employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
