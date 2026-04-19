<?php

namespace App\Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApproveUserRequest extends FormRequest
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
            'decision' => ['required', Rule::in(['approve', 'reject'])],
            'role_ids' => ['required_if:decision,approve', 'array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
            'rejected_reason' => ['required_if:decision,reject', 'nullable', 'string', 'max:255'],
        ];
    }
}
