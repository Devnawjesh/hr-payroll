<?php

namespace App\Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingsRequest extends FormRequest
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
            'app_name' => ['required', 'string', 'max:150'],
            'company_name' => ['required', 'string', 'max:180'],
            'company_email' => ['nullable', 'email', 'max:180'],
            'company_phone' => ['nullable', 'string', 'max:60'],
            'company_address' => ['nullable', 'string', 'max:1000'],
            'currency_prefix' => ['nullable', 'string', 'max:20'],
            'employee_code_prefix' => ['nullable', 'string', 'max:30'],
            'invoice_prefix' => ['nullable', 'string', 'max:30'],
            'date_format' => ['required', 'string', 'max:40'],
            'time_zone' => ['required', 'timezone'],

            'mail_mailer' => ['required', 'string', Rule::in(['smtp'])],
            'mail_host' => ['nullable', 'string', 'max:255'],
            'mail_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_encryption' => ['nullable', 'string', Rule::in(['tls', 'ssl', 'starttls'])],
            'mail_from_address' => ['nullable', 'email', 'max:180'],
            'mail_from_name' => ['nullable', 'string', 'max:180'],

            'company_logo' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg,webp', 'max:4096'],
            'company_favicon' => ['nullable', 'file', 'mimes:png,ico,jpg,jpeg,svg,webp', 'max:2048'],
        ];
    }
}
