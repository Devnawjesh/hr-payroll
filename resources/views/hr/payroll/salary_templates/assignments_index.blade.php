@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title d-flex justify-content-between align-items-center">
        <h1><i class="icon-list"></i> {{ __('Employee Salary Assignments') }}</h1>
        <div class="d-flex gap-2">
            @if(auth()->user()?->hasAnyPermission(['salary_template.assign', 'employee_salary.assign', 'payroll.manage-salary-templates']))
                <a href="{{ route('payroll.salary-template-assignments.create') }}" class="btn btn-custom"><i class="icon-user-follow"></i> {{ __('Assign Employee Salary') }}</a>
            @endif
            <a href="{{ route('payroll.salary-templates.index') }}" class="btn btn-custom-default"><i class="icon-arrow-left"></i> {{ __('Templates') }}</a>
        </div>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    <form method="GET" class="row g-2 mb-3">
                        <div class="col-md-3">
                            <input type="text" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="{{ __('Search employee or structure') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="employee_id" class="form-control">
                                <option value="0">{{ __('All Employees') }}</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ (int) $filters['employee_id'] === $employee->id ? 'selected' : '' }}>
                                        {{ trim($employee->first_name.' '.$employee->last_name) }} ({{ $employee->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="" {{ $filters['status'] === '' ? 'selected' : '' }}>{{ __('All Status') }}</option>
                                <option value="active" {{ $filters['status'] === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="future" {{ $filters['status'] === 'future' ? 'selected' : '' }}>{{ __('Future') }}</option>
                                <option value="expired" {{ $filters['status'] === 'expired' ? 'selected' : '' }}>{{ __('Expired') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="per_page" class="form-control">
                                @foreach([10,20,50,100] as $size)
                                    <option value="{{ $size }}" {{ (int) $filters['per_page'] === $size ? 'selected' : '' }}>{{ $size }} / page</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button class="btn btn-custom" type="submit"><i class="icon-magnifier"></i> {{ __('Filter') }}</button>
                            <a href="{{ route('payroll.salary-template-assignments.index') }}" class="btn btn-custom-default"><i class="icon-refresh"></i></a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Grade') }}</th>
                                    <th>{{ __('Structure') }}</th>
                                    <th>{{ __('Frequency') }}</th>
                                    <th>{{ __('Basic') }}</th>
                                    <th>{{ __('Allowances') }}</th>
                                    <th>{{ __('Gross') }}</th>
                                    <th>{{ __('Effective') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $assignment)
                                    @php
                                        $allowances = (float) $assignment->house_rent + (float) $assignment->medical_allowance + (float) $assignment->conveyance_allowance + (float) $assignment->other_allowance;
                                        $today = now()->toDateString();
                                        $status = $assignment->effective_from > $today ? 'Future' : (($assignment->effective_to && $assignment->effective_to < $today) ? 'Expired' : 'Active');
                                        $statusClass = $status === 'Active' ? 'bg-success' : ($status === 'Future' ? 'bg-info' : 'bg-secondary');
                                    @endphp
                                    <tr>
                                        <td>{{ trim($assignment->first_name.' '.$assignment->last_name) }} <small class="text-muted">({{ $assignment->employee_code }})</small></td>
                                        <td>{{ $assignment->grade_name ? $assignment->grade_name.' ('.$assignment->grade_code.')' : '-' }}</td>
                                        <td>{{ $assignment->template_name }} <small class="text-muted">({{ $assignment->template_code }})</small></td>
                                        <td>{{ __(ucfirst($assignment->pay_frequency ?: 'template')) }}</td>
                                        <td>{{ number_format((float) $assignment->basic_salary, 2) }}</td>
                                        <td>{{ number_format($allowances, 2) }}</td>
                                        <td>{{ number_format((float) $assignment->gross_salary, 2) }}</td>
                                        <td>{{ $assignment->effective_from }} - {{ $assignment->effective_to ?: __('Current') }}</td>
                                        <td><span class="badge {{ $statusClass }}">{{ __($status) }}</span></td>
                                        <td class="action-buttons">
                                            <a href="{{ route('payroll.salary-template-assignments.show', $assignment->id) }}" title="{{ __('Details') }}"><i class="icon-eye"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="10" class="text-center">{{ __('No employee salary assignments found.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $assignments->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
