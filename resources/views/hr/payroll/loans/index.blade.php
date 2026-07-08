@extends('layouts.backend')

@section('content')
@php
    $authUser = auth()->user();
    $canManageLoans = $authUser?->hasAnyPermission(['payroll.manage-loan', 'loan.create', 'employee_loan.create']) ?? false;
    $canApplyLoan = $authUser?->hasAnyPermission(['loan.apply', 'employee_loan.apply']) ?? false;
    $statusOptions = ['pending_supervisor' => 'Pending Supervisor', 'pending_final' => 'Pending Final', 'active' => 'Active', 'paused' => 'Paused', 'closed' => 'Closed', 'rejected' => 'Rejected'];
@endphp
<div class="wrapper-page">
    <div class="page-title"><h1><i class="icon-credit-card"></i> {{ __('Employee Loans') }}</h1></div>
    @include('partials.flash')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    <form method="POST" action="{{ route('payroll.loans.store') }}" class="row g-2 mb-4">
                        @csrf
                        @if($canManageLoans)
                            <div class="col-md-3"><select name="employee_id" class="form-control js-example-basic-single" required><option value="">{{ __('Employee') }}</option>@foreach($employees as $employee)<option value="{{ $employee->id }}">{{ trim($employee->first_name.' '.$employee->last_name) }} ({{ $employee->employee_code }})</option>@endforeach</select></div>
                        @else
                            <div class="col-md-3"><input type="text" class="form-control" value="{{ trim(($authUser?->employee?->first_name ?? '').' '.($authUser?->employee?->last_name ?? '')) }}" readonly></div>
                        @endif
                        <div class="col-md-2"><input type="text" name="loan_reference" class="form-control" placeholder="{{ __('Reference') }}" required></div>
                        <div class="col-md-2"><input type="number" step="0.01" min="0" name="principal_amount" class="form-control loan-principal-amount" placeholder="{{ __('Principal Amount') }}" required></div>
                        <div class="col-md-1"><input type="number" step="0.01" min="0" max="100" name="interest_rate_percent" class="form-control" placeholder="{{ __('Interest') }}"></div>
                        <div class="col-md-1"><input type="number" min="1" name="installment_count" class="form-control loan-installment-count" placeholder="{{ __('Count') }}" required></div>
                        <div class="col-md-2"><input type="number" step="0.01" min="0" name="installment_amount" class="form-control loan-installment-amount" placeholder="{{ __('Installment Amount') }}" readonly></div>
                        <div class="col-md-1"><button class="btn btn-custom w-100" type="submit"><i class="icon-plus"></i></button></div>
                        <div class="col-md-2"><input type="text" name="issued_date" class="form-control datetimepicker" value="{{ now()->toDateString() }}" placeholder="{{ __('Issued') }}" required></div>
                        <div class="col-md-2"><input type="text" name="first_installment_date" class="form-control datetimepicker" placeholder="{{ __('First due') }}"></div>
                        @if($canManageLoans)
                            <div class="col-md-2"><select name="status" class="form-control"><option value="active">{{ __('Active') }}</option><option value="pending_supervisor">{{ __('Pending Supervisor') }}</option><option value="pending_final">{{ __('Pending Final') }}</option><option value="paused">{{ __('Paused') }}</option><option value="closed">{{ __('Closed') }}</option></select></div>
                        @elseif($canApplyLoan)
                            <input type="hidden" name="status" value="pending_supervisor">
                        @endif
                        <div class="col-md-6"><input type="text" name="remarks" class="form-control" placeholder="{{ __('Remarks') }}"></div>
                    </form>

                    <form method="GET" class="row g-2 mb-3">
                        <div class="col-md-3">
                            @if($canViewAllLoans)
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
                                        <option>{{ __('My loan records') }}</option>
                                    @endforelse
                                </select>
                            @endif
                        </div>
                        <div class="col-md-2"><select name="status" class="form-control"><option value="">{{ __('All Status') }}</option>@foreach($statusOptions as $status => $label)<option value="{{ $status }}" {{ $filters['status']===$status?'selected':'' }}>{{ $label }}</option>@endforeach</select></div>
                        <div class="col-md-2"><select name="per_page" class="form-control">@foreach([10,20,50,100] as $size)<option value="{{ $size }}" {{ (int)$filters['per_page']===$size?'selected':'' }}>{{ $size }} / page</option>@endforeach</select></div>
                        <div class="col-md-5 d-flex gap-2"><button class="btn btn-custom" type="submit"><i class="icon-magnifier"></i> {{ __('Filter') }}</button><a href="{{ route('payroll.loans.index') }}" class="btn btn-custom-default"><i class="icon-refresh"></i> {{ __('Reset') }}</a></div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead><tr><th>{{ __('Employee') }}</th><th>{{ __('Reference') }}</th><th>{{ __('Principal Amount') }}</th><th>{{ __('Installment Amount') }}</th><th>{{ __('Paid') }}</th><th>{{ __('Remaining') }}</th><th>{{ __('Issued') }}</th><th>{{ __('Status') }}</th><th>{{ __('Actions') }}</th></tr></thead>
                            <tbody>
                                @forelse($loans as $loan)
                                    <tr>
                                        <td>{{ trim($loan->employee?->first_name.' '.$loan->employee?->last_name) }} <small class="text-muted">({{ $loan->employee?->employee_code }})</small></td>
                                        <td>{{ $loan->loan_reference }}</td>
                                        <td>{{ number_format((float)$loan->principal_amount, 2) }}</td>
                                        <td>{{ $loan->installment_count }} x {{ number_format((float)$loan->installment_amount, 2) }}</td>
                                        <td>{{ number_format((float)($loan->paid_total ?? 0), 2) }}</td>
                                        <td>{{ number_format(max(0, (float)$loan->principal_amount - (float)($loan->paid_total ?? 0)), 2) }}</td>
                                        <td>{{ $loan->issued_date }}</td>
                                        <td><span class="badge bg-secondary">{{ isset($statusOptions[$loan->status]) ? __($statusOptions[$loan->status]) : __(ucfirst($loan->status)) }}</span></td>
                                        <td class="action-buttons"><a href="{{ route('payroll.loans.show', $loan) }}" title="{{ __('View Loan') }}"><i class="icon-eye"></i></a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="text-center">{{ __('No loans found.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $loans->links('pagination::bootstrap-5') }}
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
