<?php

namespace App\Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($this->input('decision') !== 'approve') {
                return;
            }

            $user = $this->route('user');
            if (! $user || $user->employee()->doesntExist()) {
                $validator->errors()->add('decision', __('Create and link an employee profile before approving this user.'));
            }
        });
    }
}
