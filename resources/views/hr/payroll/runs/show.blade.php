@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title d-flex justify-content-between align-items-center">
        <h1><i class="icon-docs"></i> {{ __('Payroll Run') }}</h1>
        <div class="d-flex gap-2">
            @if($run->status === 'draft' && ($canFinalizePayroll ?? false))
                <form method="POST" action="{{ route('payroll.runs.finalize', $run) }}" onsubmit="return confirm('Finalize this payroll run? After final submission, salary calculations will be locked for this run.');">
                    @csrf
                    <button type="submit" class="btn btn-custom"><i class="icon-check"></i> {{ __('Final Submit') }}</button>
                </form>
            @endif
            <a href="{{ route('payroll.runs.index') }}" class="btn btn-custom-default"><i class="icon-arrow-left"></i> {{ __('Back') }}</a>
        </div>
    </div>
    @include('partials.flash')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    <div class="row g-2 mb-3">
                        <div class="col-md-3"><strong>{{ __('Period:') }}</strong> {{ $run->period_label ?: '-' }}</div>
                        <div class="col-md-3"><strong>{{ __('Range:') }}</strong> {{ $run->period_start }} to {{ $run->period_end }}</div>
                        <div class="col-md-2"><strong>{{ __('Gross:') }}</strong> {{ number_format((float)$run->gross_total, 2) }}</div>
                        <div class="col-md-2"><strong>{{ __('Deductions:') }}</strong> {{ number_format((float)$run->deduction_total, 2) }}</div>
                        <div class="col-md-2"><strong>{{ __('Net:') }}</strong> {{ number_format((float)$run->net_total, 2) }}</div>
                        <div class="col-md-3"><strong>{{ __('Status:') }}</strong> <span class="badge bg-secondary">{{ __(ucfirst($run->status)) }}</span></div>
                        <div class="col-md-3"><strong>{{ __('Finalized By:') }}</strong> {{ $run->processor?->name ?: '-' }}</div>
                    </div>

                    @if($run->status === 'draft')
                        <div class="alert alert-info">
                            {{ __('Review all payroll items below. Final submission will mark this run as processed and post provident fund transactions.') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead><tr><th>{{ __('Employee') }}</th><th>{{ __('Department') }}</th><th>{{ __('Basic') }}</th><th>{{ __('Allowances') }}</th><th>{{ __('Bonus') }}</th><th>{{ __('Loan') }}</th><th>{{ __('Other Ded.') }}</th><th>{{ __('PF Employee') }}</th><th>{{ __('PF Employer') }}</th><th>{{ __('Tax') }}</th><th>{{ __('Net') }}</th><th>{{ __('Status') }}</th><th>{{ __('Actions') }}</th></tr></thead>
                            <tbody>
                                @forelse($run->items as $item)
                                    <tr>
                                        <td>{{ trim($item->employee?->first_name.' '.$item->employee?->last_name) }} <small class="text-muted">({{ $item->employee?->employee_code }})</small></td>
                                        <td>{{ $item->employee?->department?->name ?: '-' }}</td>
                                        <td>{{ number_format((float)$item->basic_salary, 2) }}</td>
                                        <td>{{ number_format((float)$item->allowance_total, 2) }}</td>
                                        <td>{{ number_format((float)$item->bonus_total, 2) }}</td>
                                        <td>{{ number_format((float)$item->loan_deduction, 2) }}</td>
                                        <td>{{ number_format((float)$item->other_deduction, 2) }}</td>
                                        <td>{{ number_format((float)$item->provident_fund_deduction, 2) }}</td>
                                        <td>{{ number_format((float)$item->employer_pf_contribution, 2) }}</td>
                                        <td>{{ number_format((float)$item->tax_deduction, 2) }}</td>
                                        <td>{{ number_format((float)$item->net_payable, 2) }}</td>
                                        <td><span class="badge bg-secondary">{{ __(ucfirst($item->payment_status)) }}</span></td>
                                        <td class="action-buttons"><a href="{{ route('payroll.items.show', $item) }}" title="{{ __('Payslip') }}"><i class="icon-doc"></i></a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="13" class="text-center">{{ __('No payroll items found.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
