@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title d-flex justify-content-between align-items-center">
        <h1><i class="icon-doc"></i> {{ __('Employee Salary Detail') }}</h1>
        <a href="{{ route('payroll.salary-template-assignments.index') }}" class="btn btn-custom-default"><i class="icon-arrow-left"></i> {{ __('Back') }}</a>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    @php
                        $allowances = (float) $assignment->house_rent + (float) $assignment->medical_allowance + (float) $assignment->conveyance_allowance + (float) $assignment->other_allowance;
                        $today = now()->toDateString();
                        $status = $assignment->effective_from > $today ? 'Future' : (($assignment->effective_to && $assignment->effective_to < $today) ? 'Expired' : 'Active');
                        $statusClass = $status === 'Active' ? 'bg-success' : ($status === 'Future' ? 'bg-info' : 'bg-secondary');
                    @endphp

                    <div class="row g-2 mb-3">
                        <div class="col-md-4"><strong>{{ __('Employee:') }}</strong> {{ trim($assignment->first_name.' '.$assignment->last_name) }} ({{ $assignment->employee_code }})</div>
                        <div class="col-md-4"><strong>{{ __('Department:') }}</strong> {{ $assignment->department_name ?: '-' }}</div>
                        <div class="col-md-4"><strong>{{ __('Designation:') }}</strong> {{ $assignment->designation_name ?: '-' }}</div>
                        <div class="col-md-4"><strong>{{ __('Salary Grade:') }}</strong> {{ $assignment->grade_name ? $assignment->grade_name.' ('.$assignment->grade_code.')' : '-' }}</div>
                        <div class="col-md-4"><strong>{{ __('Grade Range:') }}</strong> {{ $assignment->min_salary !== null ? number_format((float) $assignment->min_salary, 2) : '-' }} - {{ $assignment->max_salary !== null ? number_format((float) $assignment->max_salary, 2) : '-' }}</div>
                        <div class="col-md-4"><strong>{{ __('Status:') }}</strong> <span class="badge {{ $statusClass }}">{{ __($status) }}</span></div>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th>{{ __('Salary Structure') }}</th><td>{{ $assignment->template_name }} ({{ $assignment->template_code }})</td></tr>
                                <tr><th>{{ __('Pay Frequency') }}</th><td>{{ __(ucfirst($assignment->pay_frequency ?: 'template')) }}</td></tr>
                                <tr><th>{{ __('Basic Salary') }}</th><td>{{ number_format((float) $assignment->basic_salary, 2) }}</td></tr>
                                <tr><th>{{ __('House Rent') }}</th><td>{{ number_format((float) $assignment->house_rent, 2) }}</td></tr>
                                <tr><th>{{ __('Medical Allowance') }}</th><td>{{ number_format((float) $assignment->medical_allowance, 2) }}</td></tr>
                                <tr><th>{{ __('Conveyance Allowance') }}</th><td>{{ number_format((float) $assignment->conveyance_allowance, 2) }}</td></tr>
                                <tr><th>{{ __('Other Allowance') }}</th><td>{{ number_format((float) $assignment->other_allowance, 2) }}</td></tr>
                                <tr><th>{{ __('Allowance Total') }}</th><td>{{ number_format($allowances, 2) }}</td></tr>
                                <tr><th>{{ __('Gross Salary') }}</th><td><strong>{{ number_format((float) $assignment->gross_salary, 2) }}</strong></td></tr>
                                <tr><th>{{ __('Provident Fund %') }}</th><td>{{ number_format((float) $assignment->provident_fund_percent, 2) }}</td></tr>
                                <tr><th>{{ __('Tax %') }}</th><td>{{ number_format((float) $assignment->tax_percent, 2) }}</td></tr>
                                <tr><th>{{ __('CTC Amount') }}</th><td>{{ $assignment->ctc_amount !== null ? number_format((float) $assignment->ctc_amount, 2) : '-' }}</td></tr>
                                <tr><th>{{ __('Effective From') }}</th><td>{{ $assignment->effective_from }}</td></tr>
                                <tr><th>{{ __('Effective To') }}</th><td>{{ $assignment->effective_to ?: __('Current') }}</td></tr>
                                <tr><th>{{ __('Notes') }}</th><td>{{ $assignment->notes ?: '-' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
