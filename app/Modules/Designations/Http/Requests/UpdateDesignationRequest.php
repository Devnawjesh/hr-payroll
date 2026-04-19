<?php

namespace App\Modules\Designations\Http\Requests;

use App\Models\Designation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDesignationRequest extends FormRequest
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
        /** @var Designation $designation */
        $designation = $this->route('designation');

        return [
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique(Designation::class, 'name')
                    ->ignore($designation->id)
                    ->where(fn ($query) => $query->where('department_id', $this->input('department_id'))->whereNull('deleted_at')),
            ],
            'code' => ['nullable', 'string', 'max:30'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
