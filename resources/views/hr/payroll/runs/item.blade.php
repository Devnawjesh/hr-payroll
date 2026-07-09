@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title d-flex justify-content-between align-items-center">
        <h1><i class="icon-doc"></i> {{ __('Payslip Detail') }}</h1>
        <a href="{{ route('payroll.runs.show', $item->payrollRun) }}" class="btn btn-custom-default"><i class="icon-arrow-left"></i> {{ __('Back') }}</a>
    </div>
    @include('partials.flash')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    <div class="row g-2 mb-3">
                        <div class="col-md-4"><strong>{{ __('Employee:') }}</strong> {{ trim($item->employee?->first_name.' '.$item->employee?->last_name) }} ({{ $item->employee?->employee_code }})</div>
                        <div class="col-md-4"><strong>{{ __('Department:') }}</strong> {{ $item->employee?->department?->name ?: '-' }}</div>
                        <div class="col-md-4"><strong>{{ __('Period:') }}</strong> {{ $item->payrollRun?->period_label ?: '-' }}</div>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th>{{ __('Basic Salary') }}</th><td>{{ number_format((float)$item->basic_salary, 2) }}</td></tr>
                                <tr><th>{{ __('Allowance Total') }}</th><td>{{ number_format((float)$item->allowance_total, 2) }}</td></tr>
                                <tr><th>{{ __('Bonus Total') }}</th><td>{{ number_format((float)$item->bonus_total, 2) }}</td></tr>
                                <tr><th>{{ __('Loan Deduction') }}</th><td>{{ number_format((float)$item->loan_deduction, 2) }}</td></tr>
                                <tr><th>{{ __('Other Deduction') }}</th><td>{{ number_format((float)$item->other_deduction, 2) }}</td></tr>
                                <tr><th>{{ __('Employee PF Deduction') }}</th><td>{{ number_format((float)$item->provident_fund_deduction, 2) }}</td></tr>
                                <tr><th>{{ __('Employer PF Contribution') }}</th><td>{{ number_format((float)$item->employer_pf_contribution, 2) }}</td></tr>
                                <tr><th>{{ __('Tax Deduction') }}</th><td>{{ number_format((float)$item->tax_deduction, 2) }}</td></tr>
                                <tr><th>{{ __('Total Deduction') }}</th><td>{{ number_format((float)$item->total_deduction, 2) }}</td></tr>
                                <tr><th>{{ __('Net Payable') }}</th><td><strong>{{ number_format((float)$item->net_payable, 2) }}</strong></td></tr>
                            </tbody>
                        </table>
                    </div>

                    <h5 class="table_banner_title mb-2">{{ __('Deduction Breakdown') }}</h5>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered">
                            <thead><tr><th>{{ __('Type') }}</th><th>{{ __('Amount') }}</th><th>{{ __('Reason') }}</th><th>{{ __('Comments') }}</th></tr></thead>
                            <tbody>
                                @forelse($item->deductions as $deduction)
                                    <tr><td>{{ $deduction->deduction_type }}</td><td>{{ number_format((float)$deduction->amount, 2) }}</td><td>{{ $deduction->reason ?: '-' }}</td><td>{{ $deduction->comments ?: '-' }}</td></tr>
                                @empty
                                    <tr><td colspan="4" class="text-center">{{ __('No deduction breakdown found.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($item->payrollRun?->status === 'draft')
                        <div class="alert alert-info">
                            {{ __('This payslip is part of a draft payroll run. Payment can be marked only after final submission.') }}
                        </div>
                    @elseif($item->payment_status !== 'paid')
                        <form method="POST" action="{{ route('payroll.items.paid', $item) }}" class="row g-2">
                            @csrf
                            @method('PATCH')
                            <div class="col-md-4"><input type="text" name="payment_reference" class="form-control" placeholder="{{ __('Payment reference') }}"></div>
                            <div class="col-md-2"><button class="btn btn-custom" type="submit"><i class="icon-check"></i> {{ __('Mark Paid') }}</button></div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
