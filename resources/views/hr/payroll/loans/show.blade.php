@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title d-flex justify-content-between align-items-center">
        <h1><i class="icon-credit-card"></i> {{ __('Loan Detail') }}</h1>
        <a href="{{ route('payroll.loans.index') }}" class="btn btn-custom-default"><i class="icon-arrow-left"></i> {{ __('Back') }}</a>
    </div>

    @include('partials.flash')

    @php($paidTotal = (float) $loan->installments->sum('paid_amount'))
    @php($remainingTotal = max(0, (float) $loan->principal_amount - $paidTotal))
    @php($hasPaidInstallments = $loan->installments->contains(fn($installment) => $installment->status === 'paid'))
    @php($authUser = auth()->user())
    @php($canManageLoans = $authUser?->hasAnyPermission(['payroll.manage-loan', 'loan.update', 'employee_loan.update']) ?? false)
    @php($canSupervisorApprove = $authUser?->hasAnyPermission(['payroll.manage-loan', 'loan.approve-supervisor', 'employee_loan.approve-supervisor']) ?? false)
    @php($canFinalApprove = $authUser?->hasAnyPermission(['payroll.manage-loan', 'loan.approve-final', 'employee_loan.approve-final']) ?? false)
    @php($canReject = $authUser?->hasAnyPermission(['payroll.manage-loan', 'loan.reject', 'employee_loan.reject']) ?? false)
    @php($canMarkInstallmentPaid = $authUser?->hasAnyPermission(['payroll.manage-loan', 'loan_installment.mark-paid']) ?? false)
    @php($statusLabels = ['pending_supervisor' => 'Pending Supervisor', 'pending_final' => 'Pending Final', 'active' => 'Active', 'paused' => 'Paused', 'closed' => 'Closed', 'rejected' => 'Rejected'])

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3"><strong>{{ __('Employee:') }}</strong><br>{{ trim($loan->employee?->first_name.' '.$loan->employee?->last_name) }} ({{ $loan->employee?->employee_code }})</div>
                        <div class="col-md-3"><strong>{{ __('Department:') }}</strong><br>{{ $loan->employee?->department?->name ?: '-' }}</div>
                        <div class="col-md-2"><strong>{{ __('Reference:') }}</strong><br>{{ $loan->loan_reference }}</div>
                        <div class="col-md-2"><strong>{{ __('Status:') }}</strong><br><span class="badge bg-secondary">{{ isset($statusLabels[$loan->status]) ? __($statusLabels[$loan->status]) : __(ucfirst($loan->status)) }}</span></div>
                        <div class="col-md-2"><strong>{{ __('Issued:') }}</strong><br>{{ $loan->issued_date }}</div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-3"><strong>{{ __('Principal Amount:') }}</strong><br>{{ number_format((float) $loan->principal_amount, 2) }}</div>
                        <div class="col-md-3"><strong>{{ __('Paid:') }}</strong><br>{{ number_format($paidTotal, 2) }}</div>
                        <div class="col-md-3"><strong>{{ __('Remaining:') }}</strong><br>{{ number_format($remainingTotal, 2) }}</div>
                        <div class="col-md-3"><strong>{{ __('Installment Amount:') }}</strong><br>{{ $loan->installment_count }} x {{ number_format((float) $loan->installment_amount, 2) }}</div>
                    </div>

                    @if(in_array($loan->status, ['pending_supervisor', 'pending_final'], true))
                        <h5 class="table_banner_title mb-2">{{ __('Loan Approval') }}</h5>
                        <div class="row g-2 mb-4">
                            @if($loan->status === 'pending_supervisor' && $canSupervisorApprove)
                                <div class="col-md-4">
                                    <form method="POST" action="{{ route('payroll.loans.approve', $loan) }}" class="d-flex gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="step" value="supervisor">
                                        <input type="text" name="remarks" class="form-control" placeholder="{{ __('Supervisor note') }}">
                                        <button class="btn btn-custom" type="submit"><i class="icon-check"></i> {{ __('Supervisor Approve') }}</button>
                                    </form>
                                </div>
                            @endif
                            @if($canFinalApprove)
                                <div class="col-md-4">
                                    <form method="POST" action="{{ route('payroll.loans.approve', $loan) }}" class="d-flex gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="step" value="final">
                                        <input type="text" name="remarks" class="form-control" placeholder="{{ __('Final approval note') }}">
                                        <button class="btn btn-custom" type="submit"><i class="icon-check"></i> {{ __('Final Approve') }}</button>
                                    </form>
                                </div>
                            @endif
                            @if($canReject)
                                <div class="col-md-4">
                                    <form method="POST" action="{{ route('payroll.loans.reject', $loan) }}" class="d-flex gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="text" name="remarks" class="form-control" placeholder="{{ __('Reject reason') }}">
                                        <button class="btn btn-custom-default" type="submit"><i class="icon-close"></i> {{ __('Reject') }}</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($canManageLoans && in_array($loan->status, ['active', 'paused', 'closed'], true))
                        <h5 class="table_banner_title mb-2">{{ __('Update Loan Status') }}</h5>
                        <form method="POST" action="{{ route('payroll.loans.status', $loan) }}" class="row g-2 mb-4">
                            @csrf
                            @method('PATCH')
                            <div class="col-md-3">
                                <select name="status" class="form-control" required>
                                    @foreach(['active', 'paused', 'closed'] as $status)
                                        <option value="{{ $status }}" {{ $loan->status === $status ? 'selected' : '' }}>{{ __(ucfirst($status)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6"><input type="text" name="remarks" class="form-control" value="{{ $loan->remarks }}" placeholder="{{ __('Remarks') }}"></div>
                            <div class="col-md-3"><button class="btn btn-custom" type="submit"><i class="icon-check"></i> {{ __('Update Status') }}</button></div>
                        </form>
                    @endif

                    @if($canManageLoans && in_array($loan->status, ['active', 'paused', 'closed'], true) && ! $hasPaidInstallments)
                        <h5 class="table_banner_title mb-2">{{ __('Reschedule Loan') }}</h5>
                        <form method="POST" action="{{ route('payroll.loans.reschedule', $loan) }}" class="row g-2 mb-4">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="employee_id" value="{{ $loan->employee_id }}">
                            <div class="col-md-2"><input type="text" name="loan_reference" class="form-control" value="{{ old('loan_reference', $loan->loan_reference) }}" required></div>
                            <div class="col-md-2"><input type="number" step="0.01" min="0" name="principal_amount" class="form-control loan-principal-amount" value="{{ old('principal_amount', $loan->principal_amount) }}" placeholder="{{ __('Principal Amount') }}" required></div>
                            <div class="col-md-1"><input type="number" step="0.01" min="0" max="100" name="interest_rate_percent" class="form-control" value="{{ old('interest_rate_percent', $loan->interest_rate_percent) }}"></div>
                            <div class="col-md-1"><input type="number" min="1" name="installment_count" class="form-control loan-installment-count" value="{{ old('installment_count', $loan->installment_count) }}" required></div>
                            <div class="col-md-2"><input type="number" step="0.01" min="0" name="installment_amount" class="form-control loan-installment-amount" value="{{ old('installment_amount', $loan->installment_amount) }}" readonly></div>
                            <div class="col-md-2"><input type="text" name="issued_date" class="form-control datetimepicker" value="{{ old('issued_date', $loan->issued_date) }}" required></div>
                            <div class="col-md-2"><input type="text" name="first_installment_date" class="form-control datetimepicker" value="{{ old('first_installment_date', $loan->first_installment_date) }}"></div>
                            <div class="col-md-2"><select name="status" class="form-control">@foreach(['active','paused','closed'] as $status)<option value="{{ $status }}" {{ old('status', $loan->status)===$status?'selected':'' }}>{{ __(ucfirst($status)) }}</option>@endforeach</select></div>
                            <div class="col-md-7"><input type="text" name="remarks" class="form-control" value="{{ old('remarks', $loan->remarks) }}" placeholder="{{ __('Remarks') }}"></div>
                            <div class="col-md-3"><button class="btn btn-custom" type="submit"><i class="icon-refresh"></i> {{ __('Reschedule') }}</button></div>
                        </form>
                    @elseif($canManageLoans && in_array($loan->status, ['active', 'paused', 'closed'], true) && $hasPaidInstallments)
                        <div class="alert alert-info">{{ __('This loan has paid installments. Rescheduling is locked to protect payroll history.') }}</div>
                    @endif

                    <h5 class="table_banner_title mb-2">{{ __('Installment Schedule') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Due Date') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Paid Amount') }}</th>
                                    <th>{{ __('Paid Date') }}</th>
                                    <th>{{ __('Paid By') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loan->installments as $installment)
                                    <tr>
                                        <td>{{ $installment->installment_no }}</td>
                                        <td>{{ $installment->due_date }}</td>
                                        <td>{{ number_format((float) $installment->amount, 2) }}</td>
                                        <td>{{ number_format((float) $installment->paid_amount, 2) }}</td>
                                        <td>{{ $installment->paid_date ?: '-' }}</td>
                                        <td>
                                            @if($installment->payrollItem?->payrollRun)
                                                Payroll: {{ $installment->payrollItem->payrollRun->period_label ?: $installment->payrollItem->payrollRun->period_start.' to '.$installment->payrollItem->payrollRun->period_end }}
                                            @elseif($installment->status === 'paid')
                                                Manual
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td><span class="badge bg-secondary">{{ __(ucfirst($installment->status)) }}</span></td>
                                        <td>
                                            @if($canMarkInstallmentPaid && $installment->status !== 'paid')
                                                <form method="POST" action="{{ route('payroll.loan-installments.paid', $installment) }}" class="d-flex gap-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="text" name="paid_date" class="form-control datetimepicker form-control-compact-date" value="{{ now()->toDateString() }}">
                                                    <button class="btn btn-custom btn-sm" type="submit"><i class="icon-check"></i> {{ __('Paid') }}</button>
                                                </form>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center">{{ __('No installments found.') }}</td></tr>
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

@push('scripts')
<script>
    (function () {
        function calculateInstallment(form) {
            var principal = parseFloat((form.querySelector('.loan-principal-amount') || {}).value || 0);
            var interest = parseFloat((form.querySelector('[name="interest_rate_percent"]') || {}).value || 0);
            var count = parseInt((form.querySelector('.loan-installment-count') || {}).value || 0, 10);
            var amount = form.querySelector('.loan-installment-amount');

            if (!amount || count <= 0) {
                return;
            }

            amount.value = ((principal + ((principal * interest) / 100)) / count).toFixed(2);
        }

        document.querySelectorAll('form').forEach(function (form) {
            if (!form.querySelector('.loan-installment-amount')) {
                return;
            }

            ['input', 'change'].forEach(function (eventName) {
                form.addEventListener(eventName, function (event) {
                    if (event.target.matches('.loan-principal-amount, .loan-installment-count, [name="interest_rate_percent"]')) {
                        calculateInstallment(form);
                    }
                });
            });

            calculateInstallment(form);
        });
    })();
</script>
@endpush
