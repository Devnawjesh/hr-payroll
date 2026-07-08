@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title"><h1><i class="icon-user-follow"></i> {{ __('Assign Employee Salary') }}</h1></div>
    @include('partials.flash')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    <form method="POST" action="{{ route('payroll.salary-template-assignments.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label>{{ __('Employee') }}</label>
                                <select name="employee_id" id="employee_id" class="form-control js-example-basic-single" required>
                                    <option value="">{{ __('Select Employee') }}</option>
                                    @foreach($employees as $employee)
                                        <option
                                            value="{{ $employee->id }}"
                                            data-grade-name="{{ $employee->salaryGrade?->grade_name }}"
                                            data-grade-min="{{ $employee->salaryGrade?->min_salary }}"
                                            data-grade-max="{{ $employee->salaryGrade?->max_salary }}"
                                            {{ (int)old('employee_id')===$employee->id?'selected':'' }}
                                        >{{ trim($employee->first_name.' '.$employee->last_name) }} ({{ $employee->employee_code }})</option>
                                    @endforeach
                                </select>
                                <small id="salary_grade_help" class="text-muted d-block mt-1"></small>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label>{{ __('Salary Structure') }}</label>
                                <select name="salary_template_id" id="salary_template_id" class="form-control" required>
                                    <option value="">{{ __('Select Salary Structure') }}</option>
                                    @foreach($templates as $template)
                                        <option
                                            value="{{ $template->id }}"
                                            data-pay-frequency="{{ $template->pay_frequency }}"
                                            data-basic-salary="{{ $template->basic_salary }}"
                                            data-house-rent="{{ $template->house_rent }}"
                                            data-medical-allowance="{{ $template->medical_allowance }}"
                                            data-conveyance-allowance="{{ $template->conveyance_allowance }}"
                                            data-other-allowance="{{ $template->other_allowance }}"
                                            data-provident-fund-percent="{{ $template->provident_fund_percent }}"
                                            data-tax-percent="{{ $template->tax_percent }}"
                                            {{ (int)old('salary_template_id')===$template->id?'selected':'' }}
                                        >{{ $template->name }} ({{ $template->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group mb-3"><label>{{ __('Pay Frequency') }}</label><select name="pay_frequency" class="form-control"><option value="">{{ __('Use Template Frequency') }}</option><option value="monthly" {{ old('pay_frequency')==='monthly'?'selected':'' }}>{{ __('Monthly') }}</option><option value="weekly" {{ old('pay_frequency')==='weekly'?'selected':'' }}>{{ __('Weekly') }}</option></select></div>
                            <div class="col-md-3 form-group mb-3"><label>{{ __('Basic Salary') }}</label><input type="number" step="0.01" min="0" name="basic_salary" id="basic_salary" class="form-control" value="{{ old('basic_salary') }}" required></div>
                            <div class="col-md-3 form-group mb-3"><label>{{ __('House Rent') }}</label><input type="number" step="0.01" min="0" name="house_rent" id="house_rent" class="form-control" value="{{ old('house_rent', 0) }}"></div>
                            <div class="col-md-3 form-group mb-3"><label>{{ __('Medical Allowance') }}</label><input type="number" step="0.01" min="0" name="medical_allowance" id="medical_allowance" class="form-control" value="{{ old('medical_allowance', 0) }}"></div>
                            <div class="col-md-3 form-group mb-3"><label>{{ __('Conveyance Allowance') }}</label><input type="number" step="0.01" min="0" name="conveyance_allowance" id="conveyance_allowance" class="form-control" value="{{ old('conveyance_allowance', 0) }}"></div>
                            <div class="col-md-3 form-group mb-3"><label>{{ __('Other Allowance') }}</label><input type="number" step="0.01" min="0" name="other_allowance" id="other_allowance" class="form-control" value="{{ old('other_allowance', 0) }}"></div>
                            <div class="col-md-3 form-group mb-3"><label>{{ __('Provident Fund %') }}</label><input type="number" step="0.01" min="0" max="100" name="provident_fund_percent" id="provident_fund_percent" class="form-control" value="{{ old('provident_fund_percent', 0) }}"></div>
                            <div class="col-md-3 form-group mb-3"><label>{{ __('Tax %') }}</label><input type="number" step="0.01" min="0" max="100" name="tax_percent" id="tax_percent" class="form-control" value="{{ old('tax_percent', 0) }}"></div>
                            <div class="col-md-3 form-group mb-3"><label>{{ __('CTC Amount') }}</label><input type="number" step="0.01" min="0" name="ctc_amount" class="form-control" value="{{ old('ctc_amount') }}"></div>
                            <div class="col-md-4 form-group mb-3"><label>{{ __('Effective From') }}</label><input type="text" name="effective_from" class="form-control datetimepicker" value="{{ old('effective_from', now()->toDateString()) }}" required></div>
                            <div class="col-md-4 form-group mb-3"><label>{{ __('Effective To') }}</label><input type="text" name="effective_to" class="form-control datetimepicker" value="{{ old('effective_to') }}"></div>
                            <div class="col-md-12 form-group mb-3"><label>{{ __('Notes') }}</label><textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea></div>
                        </div>
                        <button class="btn btn-custom" type="submit"><i class="icon-check"></i> {{ __('Assign Employee Salary') }}</button>
                        <a href="{{ route('payroll.salary-templates.index') }}" class="btn btn-custom-default"><i class="icon-arrow-left"></i> {{ __('Back') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var select = document.getElementById('salary_template_id');
        var employeeSelect = document.getElementById('employee_id');
        var salaryGradeHelp = document.getElementById('salary_grade_help');
        if (!select) {
            return;
        }

        var fields = {
            basicSalary: document.getElementById('basic_salary'),
            houseRent: document.getElementById('house_rent'),
            medicalAllowance: document.getElementById('medical_allowance'),
            conveyanceAllowance: document.getElementById('conveyance_allowance'),
            otherAllowance: document.getElementById('other_allowance'),
            providentFundPercent: document.getElementById('provident_fund_percent'),
            taxPercent: document.getElementById('tax_percent')
        };

        function formatMoney(value) {
            var number = parseFloat(value || 0);
            return number.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function updateGradeHelp() {
            if (!employeeSelect || !salaryGradeHelp) {
                return;
            }

            var option = employeeSelect.options[employeeSelect.selectedIndex];
            if (!option || !option.value) {
                salaryGradeHelp.textContent = '';
                salaryGradeHelp.className = 'text-muted d-block mt-1';
                return;
            }

            var gradeName = option.dataset.gradeName || 'No grade';
            var gradeMin = option.dataset.gradeMin || '';
            var gradeMax = option.dataset.gradeMax || '';
            var basicSalary = parseFloat(fields.basicSalary.value || 0);
            var message = gradeName;

            if (gradeMin || gradeMax) {
                message += ' range: ' + (gradeMin ? formatMoney(gradeMin) : '-') + ' to ' + (gradeMax ? formatMoney(gradeMax) : '-');
            }

            if (gradeMax && basicSalary > parseFloat(gradeMax)) {
                message += '. Basic salary is above grade range.';
                salaryGradeHelp.className = 'text-danger d-block mt-1';
            } else if (gradeMin && basicSalary < parseFloat(gradeMin)) {
                message += '. Basic salary is below grade range.';
                salaryGradeHelp.className = 'text-warning d-block mt-1';
            } else {
                salaryGradeHelp.className = 'text-muted d-block mt-1';
            }

            salaryGradeHelp.textContent = message;
        }

        function fillFromTemplate() {
            var option = select.options[select.selectedIndex];
            if (!option || !option.value) {
                return;
            }

            fields.basicSalary.value = option.dataset.basicSalary || 0;
            fields.houseRent.value = option.dataset.houseRent || 0;
            fields.medicalAllowance.value = option.dataset.medicalAllowance || 0;
            fields.conveyanceAllowance.value = option.dataset.conveyanceAllowance || 0;
            fields.otherAllowance.value = option.dataset.otherAllowance || 0;
            fields.providentFundPercent.value = option.dataset.providentFundPercent || 0;
            fields.taxPercent.value = option.dataset.taxPercent || 0;
            updateGradeHelp();
        }

        select.addEventListener('change', fillFromTemplate);
        if (employeeSelect) {
            employeeSelect.addEventListener('change', updateGradeHelp);
        }
        if (fields.basicSalary) {
            fields.basicSalary.addEventListener('input', updateGradeHelp);
        }
        if (!fields.basicSalary.value) {
            fillFromTemplate();
        }
        updateGradeHelp();
    })();
</script>
@endpush
