@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title">
        <h1><i class="icon-user"></i> {{ $mode === 'edit' ? __('Edit Employee') : __('Add Employee') }}</h1>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    <form method="POST" action="{{ $mode === 'edit' ? route('employees.update', $employee) : route('employees.store') }}" enctype="multipart/form-data">
                        @csrf
                        @if($mode === 'edit') @method('PUT') @endif
                        @php($employeeModel = $employee ?? null)
                        @php($addresses = old('addresses', $employeeModel?->addresses?->map->only(['address_type','line_1','line_2','city','state','postal_code','country','is_primary'])->toArray() ?? []))
                        @php($banks = old('bank_accounts', $employeeModel?->bankAccounts?->map->only(['bank_name','branch_name','account_holder_name','account_number','routing_number','account_type','is_primary'])->toArray() ?? []))
                        @php($contacts = old('emergency_contacts', $employeeModel?->emergencyContacts?->map->only(['name','relationship','phone','email','address','is_primary'])->toArray() ?? []))
                        @php($documents = old('documents', $employeeModel?->documents?->map->only(['document_type','title','file_path','issued_date','expiry_date'])->toArray() ?? []))
                        @php($dateOfBirthValue = old('date_of_birth', !empty($employee->date_of_birth ?? null) ? \Illuminate\Support\Carbon::parse($employee->date_of_birth)->format('m-d') : ''))

                        <div class="employee-form-wizard" data-employee-wizard>
                            <div class="employee-stepper" aria-label="{{ __('Employee form steps') }}">
                                <button type="button" class="employee-stepper-item is-active" data-step-jump="0">
                                    <span>1</span>
                                    {{ __('Employee Info') }}
                                </button>
                                <button type="button" class="employee-stepper-item" data-step-jump="1">
                                    <span>2</span>
                                    {{ __('Details & Documents') }}
                                </button>
                            </div>

                            <div class="employee-form-step is-active" data-step-panel="0">
                                <div class="employee-step-heading">
                                    <h5>{{ __('Employee Info') }}</h5>
                                </div>

                        <div class="row">
                            <div class="col-md-12 form-group mb-4">
                                <label>{{ __('Profile Picture') }}</label>
                                <div class="employee-avatar-card">
                                    <div class="employee-avatar-preview" id="employee_avatar_preview">
                                        @if(!empty($employee->avatar_path ?? null))
                                            <img src="{{ asset($employee->avatar_path) }}" alt="{{ __('Employee Avatar') }}">
                                        @else
                                            <i class="icon-user employee-avatar-icon"></i>
                                        @endif
                                    </div>
                                    <div class="employee-avatar-actions">
                                        <input type="file" name="avatar" id="user_image" accept=".jpg,.jpeg,.png,.webp">
                                        <label for="user_image" class="btn btn-custom btn-sm mb-2">
                                            <i class="icon-picture"></i> {{ __('Upload Photo') }}
                                        </label>
                                        <small id="avatar_file_name" class="text-muted d-block">{{ __('No file chosen') }}</small>
                                        <small class="text-muted d-block mt-1">{{ __('JPG, PNG, WEBP. Max 2MB.') }}</small>
                                        @if(!empty($employee->avatar_path ?? null))
                                            <label class="employee-avatar-remove mt-2">
                                                <input type="checkbox" name="remove_avatar" value="1">
                                                <span>{{ __('Remove current photo') }}</span>
                                            </label>
                                        @endif
                                    </div>
                                </div>
                            </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label>{{ __('Employee Code (auto if blank)') }}</label>
                                        <input type="text" name="employee_code" class="form-control" value="{{ old('employee_code', $employee->employee_code ?? '') }}" placeholder="{{ __('Leave blank for auto generated code') }}">
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                        <label>{{ __('First Name') }} <span class="required-marker">*</span></label>
                                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $employee->first_name ?? '') }}" placeholder="{{ __('Enter first name') }}" required>
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                        <label>{{ __('Last Name') }} <span class="required-marker">*</span></label>
                                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $employee->last_name ?? '') }}" placeholder="{{ __('Enter last name') }}" required>
                                    </div>

                            <div class="col-md-4 form-group mb-3">
                                <label>{{ __('User Account (optional)') }}</label>
                                <select name="user_id" class="form-control">
                                    <option value="">{{ __('No linked user') }}</option>
                                    @php($selectedUser = old('user_id', $employee->user_id ?? request('user_id')))
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ (string)$selectedUser === (string)$user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label>{{ __('Gender') }} <span class="required-marker">*</span></label>
                                    <select name="gender" class="form-control" required>
                                        <option value="">{{ __('Select Gender') }}</option>
                                        @foreach(['male','female','other'] as $gender)
                                            <option value="{{ $gender }}" {{ old('gender', $employee->gender ?? '') === $gender ? 'selected' : '' }}>{{ __(ucfirst($gender)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label>{{ __('Date of Birth') }} <span class="required-marker">*</span></label>
                                    <input type="text" name="date_of_birth" class="form-control month-day-picker" value="{{ $dateOfBirthValue }}" placeholder="{{ __('MM-DD') }}" maxlength="5" pattern="(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])" autocomplete="off" required>
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label>{{ __('Blood Group') }}</label>
                                    @php($selectedBloodGroup = old('blood_group', $employee->blood_group ?? ''))
                                    <select name="blood_group" class="form-control">
                                        <option value="">{{ __('Select Blood Group') }}</option>
                                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bloodGroup)
                                            <option value="{{ $bloodGroup }}" {{ $selectedBloodGroup === $bloodGroup ? 'selected' : '' }}>{{ $bloodGroup }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            <div class="col-md-4 form-group mb-3">
                                <label>{{ __('Phone') }} <span class="required-marker">*</span></label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone ?? '') }}" placeholder="{{ __('Enter phone number') }}" required>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label>{{ __('Alternate Phone') }}</label>
                                <input type="text" name="alternate_phone" class="form-control" value="{{ old('alternate_phone', $employee->alternate_phone ?? '') }}" placeholder="{{ __('Enter alternate phone number') }}">
                            </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label>{{ __('Work Email') }}</label>
                                    <input type="email" name="work_email" class="form-control" value="{{ old('work_email', $employee->work_email ?? '') }}" placeholder="{{ __('Enter work email') }}">
                                </div>

                            <div class="col-md-4 form-group mb-3">
                                <label>{{ __('Employment Type') }} <span class="required-marker">*</span></label>
                                @php($selectedType = old('employment_type', $employee->employment_type ?? 'full_time'))
                                <select name="employment_type" class="form-control" required>
                                    @foreach(['full_time','part_time','contract','intern'] as $type)
                                        <option value="{{ $type }}" {{ $selectedType === $type ? 'selected' : '' }}>{{ __(ucfirst(str_replace('_',' ', $type))) }}</option>
                                    @endforeach
                                </select>
                            </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label>{{ __('Employment Status') }} <span class="required-marker">*</span></label>
                                    @php($selectedStatus = old('employment_status', $employee->employment_status ?? 'active'))
                                    <select name="employment_status" class="form-control" required>
                                        @foreach(['active','inactive','on_leave','on_notice','resigned','terminated'] as $status)
                                            <option value="{{ $status }}" {{ $selectedStatus === $status ? 'selected' : '' }}>{{ __(ucfirst(str_replace('_',' ', $status))) }}</option>
                                        @endforeach
                                    </select>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label>{{ __('Date of Joining') }} <span class="required-marker">*</span></label>
                        <input type="text" name="date_of_joining" class="form-control datetimepicker" value="{{ old('date_of_joining', $employee->date_of_joining ?? '') }}" placeholder="{{ __('YYYY-MM-DD') }}" required>
                    </div>

                            <div class="col-md-4 form-group mb-3">
                                <label>{{ __('Department') }} <span class="required-marker">*</span></label>
                                <select name="department_id" class="form-control" required>
                                    <option value="">{{ __('Select Department') }}</option>
                                    @php($selectedDepartment = old('department_id', $employee->department_id ?? null))
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ (string)$selectedDepartment === (string)$department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-4 form-group mb-3">
                                <label>{{ __('Designation') }} <span class="required-marker">*</span></label>
                                <select name="designation_id" class="form-control" required>
                                    <option value="">{{ __('Select Designation') }}</option>
                                    @php($selectedDesignation = old('designation_id', $employee->designation_id ?? null))
                                    @foreach($designations as $designation)
                                        <option value="{{ $designation->id }}" {{ (string)$selectedDesignation === (string)$designation->id ? 'selected' : '' }}>{{ $designation->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label>{{ __('Salary Grade') }}</label>
                                <select name="salary_grade_id" class="form-control">
                                    <option value="">{{ __('Select Grade') }}</option>
                                    @php($selectedGrade = old('salary_grade_id', $employee->salary_grade_id ?? null))
                                    @foreach($salaryGrades as $grade)
                                        <option value="{{ $grade->id }}" {{ (string)$selectedGrade === (string)$grade->id ? 'selected' : '' }}>{{ $grade->grade_name }} ({{ $grade->grade_code }})</option>
                                    @endforeach
                                </select>
                            </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label>{{ __('Reports To') }}</label>
                                        <select name="reports_to_id" class="form-control">
                                            <option value="">{{ __('No Manager') }}</option>
                                            @php($selectedManager = old('reports_to_id', $employee->reports_to_id ?? null))
                                            @foreach($managers as $manager)
                                                <option value="{{ $manager->id }}" {{ (string)$selectedManager === (string)$manager->id ? 'selected' : '' }}>{{ trim($manager->first_name.' '.$manager->last_name) }} ({{ $manager->employee_code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                            <div class="col-md-4 form-group mb-3">
                                <label>{{ __('Marital Status') }}</label>
                                <select name="marital_status" class="form-control">
                                    <option value="">{{ __('Select') }}</option>
                                    @foreach(['single','married','divorced','widowed'] as $marital)
                                        <option value="{{ $marital }}" {{ old('marital_status', $employee->marital_status ?? '') === $marital ? 'selected' : '' }}>{{ __(ucfirst($marital)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label>{{ __('Marriage Date') }}</label>
                                <input type="text" name="marriage_date" class="form-control month-day-picker" value="{{ old('marriage_date', $employee->marriage_date ?? '') }}" placeholder="{{ __('MM-DD') }}" maxlength="5" pattern="(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])" autocomplete="off">
                            </div>

                            <div class="col-md-4 form-group mb-3">
                                <label>{{ __('NID Number') }}</label>
                                <input type="text" name="nid_number" class="form-control" value="{{ old('nid_number', $employee->nid_number ?? '') }}" placeholder="{{ __('Enter NID number') }}">
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label>{{ __('Passport Number') }}</label>
                                <input type="text" name="passport_number" class="form-control" value="{{ old('passport_number', $employee->passport_number ?? '') }}" placeholder="{{ __('Enter passport number') }}">
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label>{{ __('Tax ID') }}</label>
                                <input type="text" name="tax_id" class="form-control" value="{{ old('tax_id', $employee->tax_id ?? '') }}" placeholder="{{ __('Enter tax ID') }}">
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label>{{ __('Notes') }}</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="{{ __('Write additional notes') }}">{{ old('notes', $employee->notes ?? '') }}</textarea>
                            </div>
                        </div>
                            </div>

                            <div class="employee-form-step" data-step-panel="1">
                                <div class="employee-step-heading">
                                    <h5>{{ __('Details & Documents') }}</h5>
                                </div>
                        <div class="employee-detail-stack">
                            <section class="employee-detail-section">
                                <div class="employee-detail-header">
                                    <h5 class="table_banner_title">{{ __('Addresses') }}</h5>
                                    <button type="button" class="btn btn-custom-default btn-sm" data-add-row="addresses"><i class="icon-plus"></i> {{ __('Add Address') }}</button>
                                </div>
                                <div id="addresses-container" class="employee-detail-rows"></div>
                            </section>

                            <section class="employee-detail-section">
                                <div class="employee-detail-header">
                                    <h5 class="table_banner_title">{{ __('Bank Accounts') }}</h5>
                                    <button type="button" class="btn btn-custom-default btn-sm" data-add-row="banks"><i class="icon-plus"></i> {{ __('Add Bank Account') }}</button>
                                </div>
                                <div id="banks-container" class="employee-detail-rows"></div>
                            </section>

                            <section class="employee-detail-section">
                                <div class="employee-detail-header">
                                    <h5 class="table_banner_title">{{ __('Emergency Contacts') }}</h5>
                                    <button type="button" class="btn btn-custom-default btn-sm" data-add-row="contacts"><i class="icon-plus"></i> {{ __('Add Contact') }}</button>
                                </div>
                                <div id="contacts-container" class="employee-detail-rows"></div>
                            </section>

                            <section class="employee-detail-section">
                                <div class="employee-detail-header">
                                    <h5 class="table_banner_title">{{ __('Documents') }}</h5>
                                    <button type="button" class="btn btn-custom-default btn-sm" data-add-row="documents"><i class="icon-plus"></i> {{ __('Add Document') }}</button>
                                </div>
                                <div id="documents-container" class="employee-detail-rows"></div>
                            </section>
                        </div>
                            </div>
                        </div>

                        <div class="employee-form-actions">
                            <button class="btn btn-custom-default" type="button" data-step-prev>
                                <i class="icon-arrow-left"></i> {{ __('Previous') }}
                            </button>
                            <button class="btn btn-custom" type="button" data-step-next>
                                {{ __('Next') }} <i class="icon-arrow-right"></i>
                            </button>
                            <button class="btn btn-custom" type="submit">
                                <i class="{{ $mode === 'edit' ? 'icon-check' : 'icon-plus' }}"></i>
                                {{ $mode === 'edit' ? __('Update Employee') : __('Create Employee') }}
                            </button>
                            <a href="{{ route('employees.index') }}" class="btn btn-custom-default"><i class="icon-arrow-left"></i> {{ __('Back') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .employee-form-wizard {
        display: grid;
        gap: 20px;
    }

    .required-marker {
        color: #d9534f;
        font-weight: 700;
    }

    .employee-stepper {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 4px;
    }

    .employee-stepper-item {
        min-height: 48px;
        padding: 10px 14px;
        border: 1px solid #dbe5f1;
        border-radius: 8px;
        background: #f8fbff;
        color: #1d3048;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        text-align: left;
    }

    .employee-stepper-item span {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #e6edf6;
        color: #1d3048;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        font-size: 13px;
    }

    .employee-stepper-item.is-active {
        border-color: #37a6a5;
        background: #eefafa;
        color: #13706f;
    }

    .employee-stepper-item.is-active span {
        background: #37a6a5;
        color: #fff;
    }

    .employee-form-step {
        display: none;
    }

    .employee-form-step.is-active {
        display: block;
    }

    .employee-step-heading {
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e3eaf3;
    }

    .employee-step-heading h5 {
        margin: 0;
        color: #1d3048;
        font-size: 16px;
        font-weight: 600;
    }

    .employee-detail-stack {
        display: grid;
        gap: 18px;
        margin: 0 0 20px;
    }

    .employee-detail-section {
        padding: 16px;
        border: 1px solid #dbe5f1;
        border-radius: 8px;
        background: #fff;
    }

    .employee-detail-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }

    .employee-detail-header .table_banner_title {
        margin: 0;
        color: #1d3048;
        font-size: 15px;
        font-weight: 600;
        line-height: 1.3;
    }

    .employee-detail-header .btn {
        margin: 0;
        white-space: nowrap;
    }

    .employee-detail-rows {
        display: grid;
        gap: 12px;
    }

    .profile-row-card {
        padding: 14px;
        border: 1px solid #dbe5f1;
        border-radius: 8px;
        background: #f8fbff;
    }

    .profile-row-card .row {
        --bs-gutter-x: 10px;
        --bs-gutter-y: 10px;
        align-items: center;
    }

    .profile-row-card .form-control {
        min-height: 40px;
    }

    .profile-row-card .checkbox {
        display: flex;
        align-items: center;
        min-height: 40px;
    }

    .profile-row-card .checkbox label {
        margin-bottom: 0;
        color: #1d3048;
        font-weight: 600;
    }

    .profile-row-remove {
        width: 40px;
        min-width: 40px;
        height: 40px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .employee-form-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 20px;
    }

    @media (max-width: 767.98px) {
        .employee-stepper {
            grid-template-columns: 1fr;
        }

        .employee-detail-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .employee-detail-header .btn,
        .employee-form-actions .btn {
            width: 100%;
        }

        .employee-form-actions {
            align-items: stretch;
            flex-direction: column;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        var input = document.getElementById('user_image');
        var nameEl = document.getElementById('avatar_file_name');
        var preview = document.getElementById('employee_avatar_preview');
        if (!input || !nameEl || !preview) {
            return;
        }

        input.addEventListener('change', function () {
            if (!input.files || input.files.length === 0) {
                nameEl.textContent = @json(__('No file chosen'));
                return;
            }

            var file = input.files[0];
            nameEl.textContent = file.name;

            var reader = new FileReader();
            reader.onload = function (e) {
                preview.innerHTML = '<img src="' + e.target.result + '" alt="' + @json(__('Employee Avatar')) + '">';
            };
            reader.readAsDataURL(file);

            var removeCheckbox = document.querySelector('input[name="remove_avatar"]');
            if (removeCheckbox) {
                removeCheckbox.checked = false;
            }
        });

        var data = {
            addresses: @json($addresses),
            banks: @json($banks),
            contacts: @json($contacts),
            documents: @json($documents),
        };

        if (!Array.isArray(data.addresses) || data.addresses.length === 0) {
            data.addresses = [{ address_type: 'Present', is_primary: true }];
        }

        if (window.jQuery && $.fn.datepicker) {
            $('.month-day-picker').datepicker({
                format: 'mm-dd',
                autoclose: true,
                clearBtn: true,
                todayHighlight: false
            });
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function boolChecked(value) {
            return (value === true || value === 1 || value === '1') ? 'checked' : '';
        }

        function rowHtml(type, i, row) {
            row = row || {};

            if (type === 'addresses') {
                return `<div class="profile-row-card" data-row>
                    <div class="row g-2">
                        <div class="col-md-2"><input name="addresses[${i}][address_type]" class="form-control" placeholder="{{ __('Type *') }}" value="${escapeHtml(row.address_type)}" required></div>
                        <div class="col-md-4"><input name="addresses[${i}][line_1]" class="form-control" placeholder="{{ __('Address line 1 *') }}" value="${escapeHtml(row.line_1)}" required></div>
                        <div class="col-md-3"><input name="addresses[${i}][line_2]" class="form-control" placeholder="{{ __('Address line 2') }}" value="${escapeHtml(row.line_2)}"></div>
                        <div class="col-md-2"><input name="addresses[${i}][city]" class="form-control" placeholder="{{ __('City *') }}" value="${escapeHtml(row.city)}" required></div>
                        <div class="col-md-1"><button type="button" class="btn btn-custom-default btn-sm profile-row-remove" data-remove-row><i class="icon-trash"></i></button></div>
                        <div class="col-md-2"><input name="addresses[${i}][state]" class="form-control" placeholder="{{ __('State') }}" value="${escapeHtml(row.state)}"></div>
                        <div class="col-md-2"><input name="addresses[${i}][postal_code]" class="form-control" placeholder="{{ __('Postal') }}" value="${escapeHtml(row.postal_code)}"></div>
                        <div class="col-md-3"><input name="addresses[${i}][country]" class="form-control" placeholder="{{ __('Country *') }}" value="${escapeHtml(row.country)}" required></div>
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="checkbox checkbox-default mb-0">
                                <input id="addresses_primary_${i}" type="checkbox" name="addresses[${i}][is_primary]" value="1" ${boolChecked(row.is_primary)}>
                                <label for="addresses_primary_${i}">{{ __('Primary') }}</label>
                            </div>
                        </div>
                    </div>
                </div>`;
            }

            if (type === 'banks') {
                return `<div class="profile-row-card" data-row>
                    <div class="row g-2">
                        <div class="col-md-3"><input name="bank_accounts[${i}][bank_name]" class="form-control" placeholder="{{ __('Bank name') }}" value="${escapeHtml(row.bank_name)}"></div>
                        <div class="col-md-3"><input name="bank_accounts[${i}][branch_name]" class="form-control" placeholder="{{ __('Branch name') }}" value="${escapeHtml(row.branch_name)}"></div>
                        <div class="col-md-3"><input name="bank_accounts[${i}][account_holder_name]" class="form-control" placeholder="{{ __('Account holder') }}" value="${escapeHtml(row.account_holder_name)}"></div>
                        <div class="col-md-2"><input name="bank_accounts[${i}][account_number]" class="form-control" placeholder="{{ __('Account no') }}" value="${escapeHtml(row.account_number)}"></div>
                        <div class="col-md-1"><button type="button" class="btn btn-custom-default btn-sm profile-row-remove" data-remove-row><i class="icon-trash"></i></button></div>
                        <div class="col-md-2"><input name="bank_accounts[${i}][routing_number]" class="form-control" placeholder="{{ __('Routing') }}" value="${escapeHtml(row.routing_number)}"></div>
                        <div class="col-md-2"><input name="bank_accounts[${i}][account_type]" class="form-control" placeholder="{{ __('Type') }}" value="${escapeHtml(row.account_type)}"></div>
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="checkbox checkbox-default mb-0">
                                <input id="banks_primary_${i}" type="checkbox" name="bank_accounts[${i}][is_primary]" value="1" ${boolChecked(row.is_primary)}>
                                <label for="banks_primary_${i}">{{ __('Primary') }}</label>
                            </div>
                        </div>
                    </div>
                </div>`;
            }

            if (type === 'contacts') {
                return `<div class="profile-row-card" data-row>
                    <div class="row g-2">
                        <div class="col-md-3"><input name="emergency_contacts[${i}][name]" class="form-control" placeholder="{{ __('Name') }}" value="${escapeHtml(row.name)}"></div>
                        <div class="col-md-2"><input name="emergency_contacts[${i}][relationship]" class="form-control" placeholder="{{ __('Relationship') }}" value="${escapeHtml(row.relationship)}"></div>
                        <div class="col-md-2"><input name="emergency_contacts[${i}][phone]" class="form-control" placeholder="{{ __('Phone') }}" value="${escapeHtml(row.phone)}"></div>
                        <div class="col-md-3"><input name="emergency_contacts[${i}][email]" class="form-control" placeholder="{{ __('Email') }}" value="${escapeHtml(row.email)}"></div>
                        <div class="col-md-1"><button type="button" class="btn btn-custom-default btn-sm profile-row-remove" data-remove-row><i class="icon-trash"></i></button></div>
                        <div class="col-md-5"><input name="emergency_contacts[${i}][address]" class="form-control" placeholder="{{ __('Address') }}" value="${escapeHtml(row.address)}"></div>
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="checkbox checkbox-default mb-0">
                                <input id="contacts_primary_${i}" type="checkbox" name="emergency_contacts[${i}][is_primary]" value="1" ${boolChecked(row.is_primary)}>
                                <label for="contacts_primary_${i}">{{ __('Primary') }}</label>
                            </div>
                        </div>
                    </div>
                </div>`;
            }

            if (type === 'documents') {
                return `<div class="profile-row-card" data-row>
                    <div class="row g-2">
                        <div class="col-md-2"><input name="documents[${i}][document_type]" class="form-control" placeholder="{{ __('Type') }}" value="${escapeHtml(row.document_type)}"></div>
                        <div class="col-md-3"><input name="documents[${i}][title]" class="form-control" placeholder="{{ __('Title') }}" value="${escapeHtml(row.title)}"></div>
                        <div class="col-md-3"><input type="file" name="documents[${i}][file]" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx,.xls,.xlsx"></div>
                        <div class="col-md-3"><input name="documents[${i}][file_path]" class="form-control" placeholder="{{ __('SharePoint or file URL') }}" value="${escapeHtml(row.file_path)}"></div>
                        <div class="col-md-1"><button type="button" class="btn btn-custom-default btn-sm profile-row-remove" data-remove-row><i class="icon-trash"></i></button></div>
                        <div class="col-md-2"><input name="documents[${i}][issued_date]" class="form-control datetimepicker" placeholder="{{ __('Issued') }}" value="${escapeHtml(row.issued_date)}"></div>
                        <div class="col-md-2"><input name="documents[${i}][expiry_date]" class="form-control datetimepicker" placeholder="{{ __('Expiry') }}" value="${escapeHtml(row.expiry_date)}"></div>
                    </div>
                </div>`;
            }

            return '';
        }

        var refs = {
            addresses: {container: document.getElementById('addresses-container'), key: 'addresses'},
            banks: {container: document.getElementById('banks-container'), key: 'banks'},
            contacts: {container: document.getElementById('contacts-container'), key: 'contacts'},
            documents: {container: document.getElementById('documents-container'), key: 'documents'},
        };

        function render(type) {
            var ref = refs[type];
            if (!ref || !ref.container) return;

            var rows = Array.isArray(data[ref.key]) ? data[ref.key] : [];
            if (type === 'addresses' && rows.length === 0) {
                rows.push({ address_type: 'Present', is_primary: true });
                data[ref.key] = rows;
            }
            ref.container.innerHTML = rows.map(function (row, i) {
                return rowHtml(type, i, row);
            }).join('');

            if (window.jQuery && $.fn.datepicker) {
                $('.datetimepicker').datepicker({ format: 'yyyy-mm-dd' });
            }
        }

        Object.keys(refs).forEach(render);

        document.querySelectorAll('[data-add-row]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var type = btn.getAttribute('data-add-row');
                var key = refs[type].key;
                if (!Array.isArray(data[key])) data[key] = [];
                data[key].push({});
                render(type);
            });
        });

        document.addEventListener('click', function (event) {
            var removeBtn = event.target.closest('[data-remove-row]');
            if (!removeBtn) return;

            var card = removeBtn.closest('[data-row]');
            if (!card) return;

            var container = card.parentElement;
            var type = Object.keys(refs).find(function (key) { return refs[key].container === container; });
            if (!type) return;

            var index = Array.prototype.indexOf.call(container.children, card);
            var dataKey = refs[type].key;
            if (Array.isArray(data[dataKey]) && index > -1) {
                data[dataKey].splice(index, 1);
                render(type);
            }
        });

        var wizard = document.querySelector('[data-employee-wizard]');
        var form = wizard ? wizard.closest('form') : null;
        var panels = wizard ? Array.prototype.slice.call(wizard.querySelectorAll('[data-step-panel]')) : [];
        var stepButtons = wizard ? Array.prototype.slice.call(wizard.querySelectorAll('[data-step-jump]')) : [];
        var prevButton = document.querySelector('[data-step-prev]');
        var nextButton = document.querySelector('[data-step-next]');
        var submitButton = form ? form.querySelector('button[type="submit"]') : null;
        var currentStep = 0;

        function validateStep(index) {
            var panel = panels[index];
            if (!panel || !form) return true;

            var fields = Array.prototype.slice.call(panel.querySelectorAll('input, select, textarea'));
            for (var i = 0; i < fields.length; i++) {
                var field = fields[i];
                if (field.disabled || field.type === 'hidden') continue;
                if (!field.checkValidity()) {
                    field.reportValidity();
                    field.focus({ preventScroll: true });
                    field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }
            }

            return true;
        }

        function showStep(index) {
            if (!panels.length) return;
            currentStep = Math.max(0, Math.min(index, panels.length - 1));

            panels.forEach(function (panel, i) {
                panel.classList.toggle('is-active', i === currentStep);
            });

            stepButtons.forEach(function (button, i) {
                button.classList.toggle('is-active', i === currentStep);
            });

            if (prevButton) {
                prevButton.style.display = currentStep === 0 ? 'none' : '';
            }
            if (nextButton) {
                nextButton.style.display = currentStep === panels.length - 1 ? 'none' : '';
            }
            if (submitButton) {
                submitButton.style.display = currentStep === panels.length - 1 ? '' : 'none';
            }
        }

        stepButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var targetStep = parseInt(button.getAttribute('data-step-jump'), 10);
                if (Number.isNaN(targetStep) || targetStep === currentStep) return;
                if (targetStep > currentStep && !validateStep(currentStep)) return;
                showStep(targetStep);
            });
        });

        if (prevButton) {
            prevButton.addEventListener('click', function () {
                showStep(currentStep - 1);
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', function () {
                if (!validateStep(currentStep)) return;
                showStep(currentStep + 1);
            });
        }

        if (form) {
            form.addEventListener('submit', function (event) {
                if (validateStep(currentStep)) {
                    return;
                }

                event.preventDefault();
            });
        }

        showStep(0);
    })();
</script>
@endpush
