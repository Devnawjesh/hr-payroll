@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title"><h1><i class="icon-shield"></i> {{ __('Provident Fund Setup') }}</h1></div>
    @include('partials.flash')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    @if($canManageProvidentFund ?? false)
                        <form method="POST" action="{{ route('payroll.provident-funds.store') }}" class="row g-2 mb-4">
                            @csrf
                            <div class="col-md-3">
                                <label>{{ __('Employee') }}</label>
                                <select name="employee_id" class="form-control js-example-basic-single" title="{{ __('Select the employee this provident fund setup belongs to') }}" required><option value="">{{ __('Select Employee') }}</option>@foreach($employees as $employee)<option value="{{ $employee->id }}">{{ trim($employee->first_name.' '.$employee->last_name) }} ({{ $employee->employee_code }})</option>@endforeach</select>
                            </div>
                            <div class="col-md-2">
                                <label>{{ __('Employee PF %') }}</label>
                                <input type="number" step="0.01" min="0" max="100" name="employee_contribution_percent" class="form-control" placeholder="{{ __('Deduct from salary') }}" title="{{ __('Percent deducted from employee basic salary') }}" required>
                            </div>
                            <div class="col-md-2">
                                <label>{{ __('Employer PF %') }}</label>
                                <input type="number" step="0.01" min="0" max="100" name="employer_contribution_percent" class="form-control" placeholder="{{ __('Company contribution') }}" title="{{ __('Percent contributed by the company without reducing net salary') }}" required>
                            </div>
                            <div class="col-md-2">
                                <label>{{ __('Opening PF Balance') }}</label>
                                <input type="number" step="0.01" min="0" name="opening_balance" class="form-control" value="0" placeholder="{{ __('Existing balance') }}" title="{{ __('Starting PF balance before payroll transactions') }}">
                            </div>
                            <div class="col-md-2">
                                <label>{{ __('Effective From') }}</label>
                                <input type="text" name="effective_from" class="form-control datetimepicker" value="{{ now()->toDateString() }}" placeholder="{{ __('Start date') }}" title="{{ __('Payroll uses this setup from this date') }}">
                            </div>
                            <div class="col-md-1 d-flex align-items-end"><button class="btn btn-custom w-100" type="submit"><i class="icon-check"></i> {{ __('Save') }}</button></div>
                        </form>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead><tr><th>{{ __('Employee') }}</th><th>{{ __('Employee PF %') }}</th><th>{{ __('Employer PF %') }}</th><th>{{ __('Opening PF Balance') }}</th><th>{{ __('Effective From') }}</th></tr></thead>
                            <tbody>
                                @forelse($funds as $fund)
                                    <tr>
                                        <td>{{ trim($fund->employee?->first_name.' '.$fund->employee?->last_name) }} <small class="text-muted">({{ $fund->employee?->employee_code }})</small></td>
                                        <td>{{ number_format((float)$fund->employee_contribution_percent, 2) }}</td>
                                        <td>{{ number_format((float)$fund->employer_contribution_percent, 2) }}</td>
                                        <td>{{ number_format((float)$fund->opening_balance, 2) }}</td>
                                        <td>{{ $fund->effective_from ?: '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">{{ __('No provident fund setup found.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $funds->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
