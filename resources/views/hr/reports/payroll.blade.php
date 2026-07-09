@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title d-flex justify-content-between align-items-center">
        <h1><i class="icon-wallet"></i> {{ __('Payroll Report') }}</h1>
        <a href="{{ route('reports.payroll.export', request()->query()) }}" class="btn btn-custom-default"><i class="icon-cloud-download"></i> {{ __('Export CSV') }}</a>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border mb-3">
                <div class="content_wrapper content-padded">
                    <div class="row g-3">
                        <div class="col-md-3"><strong>{{ __('Payslips:') }}</strong> {{ $summary['items'] }}</div>
                        <div class="col-md-3"><strong>{{ __('Gross:') }}</strong> {{ number_format((float) $summary['gross'], 2) }}</div>
                        <div class="col-md-3"><strong>{{ __('Deductions:') }}</strong> {{ number_format((float) $summary['deductions'], 2) }}</div>
                        <div class="col-md-3"><strong>{{ __('Net:') }}</strong> {{ number_format((float) $summary['net'], 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    <form method="GET" class="row g-2 mb-3">
                        <div class="col-md-3">
                            @if($canViewAllPayroll)
                                <select name="employee_id" class="form-control js-example-basic-single">
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
                                        <option>{{ __('My payroll records') }}</option>
                                    @endforelse
                                </select>
                            @endif
                        </div>
                        <div class="col-md-2"><select name="status" class="form-control"><option value="">{{ __('All Run Status') }}</option>@foreach(['draft','processed','approved','paid'] as $status)<option value="{{ $status }}" {{ $filters['status']===$status?'selected':'' }}>{{ __(ucfirst($status)) }}</option>@endforeach</select></div>
                        <div class="col-md-2"><input type="text" name="from_date" class="form-control datetimepicker" value="{{ $filters['from_date'] }}" placeholder="{{ __('From date') }}"></div>
                        <div class="col-md-2"><input type="text" name="to_date" class="form-control datetimepicker" value="{{ $filters['to_date'] }}" placeholder="{{ __('To date') }}"></div>
                        <div class="col-md-1"><select name="per_page" class="form-control">@foreach([10,20,50,100] as $size)<option value="{{ $size }}" {{ (int)$filters['per_page']===$size?'selected':'' }}>{{ $size }}</option>@endforeach</select></div>
                        <div class="col-md-2 d-flex gap-2"><button class="btn btn-custom" type="submit"><i class="icon-magnifier"></i></button><a href="{{ route('reports.payroll') }}" class="btn btn-custom-default"><i class="icon-refresh"></i></a></div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead><tr><th>{{ __('Period') }}</th><th>{{ __('Employee') }}</th><th>{{ __('Basic') }}</th><th>{{ __('Allowances') }}</th><th>{{ __('Bonus') }}</th><th>{{ __('Employee PF') }}</th><th>{{ __('Employer PF') }}</th><th>{{ __('Deductions') }}</th><th>{{ __('Net') }}</th><th>{{ __('Run Status') }}</th><th>{{ __('Payment') }}</th></tr></thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td>{{ $item->payrollRun?->period_label ?: (($item->payrollRun?->period_start ?? '').' to '.($item->payrollRun?->period_end ?? '')) }}</td>
                                        <td>{{ trim(($item->employee?->first_name ?? '').' '.($item->employee?->last_name ?? '')) }} <small class="text-muted">({{ $item->employee?->employee_code }})</small></td>
                                        <td>{{ number_format((float) $item->basic_salary, 2) }}</td>
                                        <td>{{ number_format((float) $item->allowance_total, 2) }}</td>
                                        <td>{{ number_format((float) $item->bonus_total, 2) }}</td>
                                        <td>{{ number_format((float) $item->provident_fund_deduction, 2) }}</td>
                                        <td>{{ number_format((float) $item->employer_pf_contribution, 2) }}</td>
                                        <td>{{ number_format((float) $item->total_deduction, 2) }}</td>
                                        <td>{{ number_format((float) $item->net_payable, 2) }}</td>
                                        <td><span class="badge bg-secondary">{{ __(ucfirst($item->payrollRun?->status ?? '-')) }}</span></td>
                                        <td>{{ __(ucfirst($item->payment_status)) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="11" class="text-center">{{ __('No payroll records found.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $items->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
