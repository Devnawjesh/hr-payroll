@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title"><h1><i class="icon-minus"></i> {{ __('Employee Deductions') }}</h1></div>
    @include('partials.flash')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    @if($canManageDeductions ?? false)
                        <form method="POST" action="{{ route('payroll.deductions.store') }}" class="row g-2 mb-4">
                            @csrf
                            <div class="col-md-3">
                                <label>{{ __('Employee') }}</label>
                                <select name="employee_id" class="form-control js-example-basic-single" title="{{ __('Select the employee this deduction belongs to') }}" required><option value="">{{ __('Select Employee') }}</option>@foreach($employees as $employee)<option value="{{ $employee->id }}">{{ trim($employee->first_name.' '.$employee->last_name) }} ({{ $employee->employee_code }})</option>@endforeach</select>
                            </div>
                            <div class="col-md-3">
                                <label>{{ __('Deduction Name') }}</label>
                                <input type="text" name="deduction_type" class="form-control" placeholder="{{ __('Welfare Fund, Penalty, Advance Adjustment') }}" title="{{ __('Enter a clear deduction name for payroll and payslip records') }}" required>
                            </div>
                            <div class="col-md-2">
                                <label>{{ __('Calculation Method') }}</label>
                                <select name="calculation_type" class="form-control" title="{{ __('Choose whether payroll deducts a fixed amount or a percent of basic salary') }}"><option value="fixed">{{ __('Fixed Amount') }}</option><option value="percent">{{ __('Percent of Basic Salary') }}</option></select>
                            </div>
                            <div class="col-md-2">
                                <label>{{ __('Amount / Percent') }}</label>
                                <input type="number" step="0.01" min="0" name="amount" class="form-control" placeholder="{{ __('Example: 500 or 5') }}" title="{{ __('For fixed amount enter money amount; for percent enter the percentage number') }}" required>
                            </div>
                            <div class="col-md-2">
                                <label>{{ __('Deduction Frequency') }}</label>
                                <select name="frequency" class="form-control" title="{{ __('Choose which payroll run should include this deduction') }}"><option value="monthly">{{ __('Every Monthly Payroll') }}</option><option value="weekly">{{ __('Every Weekly Payroll') }}</option><option value="one_time">{{ __('One Payroll Only') }}</option></select>
                            </div>
                            <div class="col-md-2">
                                <label>{{ __('Start Date') }}</label>
                                <input type="text" name="effective_from" class="form-control datetimepicker" value="{{ now()->toDateString() }}" placeholder="{{ __('Deduct from date') }}" title="{{ __('Payroll can deduct this record from this date') }}" required>
                            </div>
                            <div class="col-md-2">
                                <label>{{ __('End Date') }}</label>
                                <input type="text" name="effective_to" class="form-control datetimepicker" placeholder="{{ __('Optional stop date') }}" title="{{ __('Leave empty for an ongoing deduction') }}">
                            </div>
                            <div class="col-md-2">
                                <label>{{ __('Record Status') }}</label>
                                <select name="is_active" class="form-control" title="{{ __('Only active deductions are included in payroll') }}"><option value="1">{{ __('Active') }}</option><option value="0">{{ __('Inactive') }}</option></select>
                            </div>
                            <div class="col-md-3">
                                <label>{{ __('Reason / Policy') }}</label>
                                <input type="text" name="reason" class="form-control" placeholder="{{ __('Reason shown in payslip breakdown') }}" title="{{ __('Optional reason visible in the deduction breakdown') }}">
                            </div>
                            <div class="col-md-3">
                                <label>{{ __('Internal Notes') }}</label>
                                <input type="text" name="comments" class="form-control" placeholder="{{ __('Optional payroll note') }}" title="{{ __('Internal note for payroll users') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end"><button class="btn btn-custom w-100" type="submit"><i class="icon-plus"></i> {{ __('Add Deduction') }}</button></div>
                        </form>
                    @endif

                    <form method="GET" class="row g-2 mb-3">
                        <div class="col-md-3">
                            @if($canViewAllDeductions ?? false)
                                <select name="employee_id" class="form-control">
                                    <option value="0">{{ __('All Employees') }}</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ (int)$filters['employee_id']===$employee->id?'selected':'' }}>{{ trim($employee->first_name.' '.$employee->last_name) }} ({{ $employee->employee_code }})</option>
                                    @endforeach
                                </select>
                            @else
                                <select name="employee_id" class="form-control" disabled>
                                    @forelse($employees as $employee)
                                        <option>{{ trim($employee->first_name.' '.$employee->last_name) }} ({{ $employee->employee_code }})</option>
                                    @empty
                                        <option>{{ __('My deduction records') }}</option>
                                    @endforelse
                                </select>
                            @endif
                        </div>
                        <div class="col-md-2"><select name="status" class="form-control"><option value="">{{ __('All Status') }}</option><option value="active" {{ $filters['status']==='active'?'selected':'' }}>{{ __('Active') }}</option><option value="inactive" {{ $filters['status']==='inactive'?'selected':'' }}>{{ __('Inactive') }}</option></select></div>
                        <div class="col-md-2"><select name="per_page" class="form-control">@foreach([10,20,50,100] as $size)<option value="{{ $size }}" {{ (int)$filters['per_page']===$size?'selected':'' }}>{{ $size }} / page</option>@endforeach</select></div>
                        <div class="col-md-5 d-flex gap-2"><button class="btn btn-custom" type="submit"><i class="icon-magnifier"></i> {{ __('Filter') }}</button><a href="{{ route('payroll.deductions.index') }}" class="btn btn-custom-default"><i class="icon-refresh"></i> {{ __('Reset') }}</a></div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead><tr><th>{{ __('Employee') }}</th><th>{{ __('Deduction Name') }}</th><th>{{ __('Calculation Method') }}</th><th>{{ __('Amount / Percent') }}</th><th>{{ __('Frequency') }}</th><th>{{ __('Effective Period') }}</th><th>{{ __('Status') }}</th><th>{{ __('Actions') }}</th></tr></thead>
                            <tbody>
                                @forelse($deductions as $deduction)
                                    <tr>
                                        <td>{{ trim($deduction->employee?->first_name.' '.$deduction->employee?->last_name) }} <small class="text-muted">({{ $deduction->employee?->employee_code }})</small></td>
                                        <td>{{ $deduction->deduction_type }}</td>
                                        <td>{{ $deduction->calculation_type === 'percent' ? __('Percent of Basic Salary') : __('Fixed Amount') }}</td>
                                        <td>{{ $deduction->calculation_type === 'percent' ? number_format((float)$deduction->amount, 2).'%' : number_format((float)$deduction->amount, 2) }}</td>
                                        <td>
                                            @if($deduction->frequency === 'monthly')
                                                {{ __('Every Monthly Payroll') }}
                                            @elseif($deduction->frequency === 'weekly')
                                                {{ __('Every Weekly Payroll') }}
                                            @else
                                                {{ __('One Payroll Only') }}
                                            @endif
                                        </td>
                                        <td>{{ $deduction->effective_from }} - {{ $deduction->effective_to ?: 'Open' }}</td>
                                        <td><span class="badge {{ $deduction->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $deduction->is_active ? __('Active') : __('Inactive') }}</span></td>
                                        <td class="action-buttons">
                                            @if($canManageDeductions ?? false)
                                                <form method="POST" action="{{ route('payroll.deductions.destroy', $deduction) }}" onsubmit="return confirm('Delete this deduction?');">@csrf @method('DELETE')<button type="submit"><i class="icon-trash"></i></button></form>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center">{{ __('No deductions found.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $deductions->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
