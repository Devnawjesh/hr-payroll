@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title d-flex justify-content-between align-items-center">
        <h1><i class="icon-shield"></i> {{ __('Provident Fund Report') }}</h1>
        @if($canViewAllProvidentFund)
            <a href="{{ route('reports.provident-fund.export', request()->query()) }}" class="btn btn-custom-default"><i class="icon-cloud-download"></i> {{ __('Export CSV') }}</a>
        @endif
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border mb-3">
                <div class="content_wrapper content-padded">
                    <div class="row g-3">
                        <div class="col-md-3"><strong>{{ __('Employee Contribution:') }}</strong> {{ number_format((float) $summary['employee_contribution'], 2) }}</div>
                        <div class="col-md-3"><strong>{{ __('Employer Contribution:') }}</strong> {{ number_format((float) $summary['employer_contribution'], 2) }}</div>
                        <div class="col-md-3"><strong>{{ __('Withdrawals:') }}</strong> {{ number_format((float) $summary['withdrawal'], 2) }}</div>
                        <div class="col-md-3"><strong>{{ __('Adjustments:') }}</strong> {{ number_format((float) $summary['adjustment'], 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    <form method="GET" class="row g-2 mb-3">
                        <div class="col-md-2">
                            <input type="number" name="year" min="2000" max="2100" class="form-control" value="{{ $filters['year'] }}" placeholder="{{ __('Year') }}">
                        </div>
                        @if($canViewAllProvidentFund)
                            <div class="col-md-3">
                                <select name="department_id" class="form-control">
                                    <option value="0">{{ __('All Departments') }}</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ (int)$filters['department_id']===$department->id?'selected':'' }}>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="employee_id" class="form-control js-example-basic-single">
                                    <option value="0">{{ __('All Employees') }}</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ (int)$filters['employee_id']===$employee->id?'selected':'' }}>{{ trim($employee->first_name.' '.$employee->last_name) }} ({{ $employee->employee_code }})</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="col-md-6">
                                <select name="employee_id" class="form-control" disabled>
                                    @forelse($employees as $employee)
                                        <option>{{ trim($employee->first_name.' '.$employee->last_name) }} ({{ $employee->employee_code }})</option>
                                    @empty
                                        <option>{{ __('My provident fund records') }}</option>
                                    @endforelse
                                </select>
                            </div>
                        @endif
                        <div class="col-md-1"><select name="per_page" class="form-control">@foreach([10,20,50,100] as $size)<option value="{{ $size }}" {{ (int)$filters['per_page']===$size?'selected':'' }}>{{ $size }}</option>@endforeach</select></div>
                        <div class="col-md-3 d-flex gap-2"><button class="btn btn-custom" type="submit"><i class="icon-magnifier"></i> {{ __('Filter') }}</button><a href="{{ route('reports.provident-fund') }}" class="btn btn-custom-default"><i class="icon-refresh"></i> {{ __('Reset') }}</a></div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead><tr><th>{{ __('Date') }}</th><th>{{ __('Employee') }}</th><th>{{ __('Department') }}</th><th>{{ __('Type') }}</th><th>{{ __('Employee PF') }}</th><th>{{ __('Employer PF') }}</th><th>{{ __('Withdrawal') }}</th><th>{{ __('Adjustment') }}</th><th>{{ __('Balance After') }}</th><th>{{ __('Reference') }}</th></tr></thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transaction_date }}</td>
                                        <td>{{ trim(($transaction->employee?->first_name ?? '').' '.($transaction->employee?->last_name ?? '')) }} <small class="text-muted">({{ $transaction->employee?->employee_code }})</small></td>
                                        <td>{{ $transaction->employee?->department?->name ?? '-' }}</td>
                                        <td>{{ __(ucfirst($transaction->transaction_type)) }}</td>
                                        <td>{{ number_format((float) $transaction->employee_contribution, 2) }}</td>
                                        <td>{{ number_format((float) $transaction->employer_contribution, 2) }}</td>
                                        <td>{{ number_format((float) $transaction->withdrawal_amount, 2) }}</td>
                                        <td>{{ number_format((float) $transaction->adjustment_amount, 2) }}</td>
                                        <td>{{ $transaction->balance_after !== null ? number_format((float) $transaction->balance_after, 2) : '-' }}</td>
                                        <td>{{ $transaction->reference_no ?: '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="10" class="text-center">{{ __('No provident fund records found.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $transactions->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
