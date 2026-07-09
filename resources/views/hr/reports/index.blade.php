@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title">
        <h1><i class="icon-chart"></i> {{ __('Reports') }}</h1>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="row g-3">
                @if(auth()->user()?->hasAnyPermission(['report.employee', 'report.view', 'employee.view']))
                    <div class="col-md-3">
                        <a href="{{ route('reports.employees') }}" class="card no-border text-decoration-none">
                            <div class="content_wrapper content-padded">
                                <h5 class="table_banner_title mb-1">{{ __('Employee Report') }}</h5>
                                <p class="text-muted mb-0">{{ __('Employee list, departments, designations and status.') }}</p>
                            </div>
                        </a>
                    </div>
                @endif
                @if(auth()->user()?->hasAnyPermission(['report.attendance', 'report.view', 'attendance.report', 'attendance.view', 'attendance.manage']))
                    <div class="col-md-3">
                        <a href="{{ route('reports.attendance') }}" class="card no-border text-decoration-none">
                            <div class="content_wrapper content-padded">
                                <h5 class="table_banner_title mb-1">{{ __('Attendance Report') }}</h5>
                                <p class="text-muted mb-0">{{ __('Daily attendance, status and worked minutes.') }}</p>
                            </div>
                        </a>
                    </div>
                @endif
                @if(auth()->user()?->hasAnyPermission(['report.leave', 'report.view', 'leave.report', 'leave.approve', 'leave.view']))
                    <div class="col-md-3">
                        <a href="{{ route('leave-reports.index') }}" class="card no-border text-decoration-none">
                            <div class="content_wrapper content-padded">
                                <h5 class="table_banner_title mb-1">{{ __('Leave Report') }}</h5>
                                <p class="text-muted mb-0">{{ __('Leave applications, categories, days and approval status.') }}</p>
                            </div>
                        </a>
                    </div>
                @endif
                @if(auth()->user()?->hasAnyPermission(['report.payroll', 'report.view', 'payroll.report', 'payslip.view']))
                    <div class="col-md-3">
                        <a href="{{ route('reports.payroll') }}" class="card no-border text-decoration-none">
                            <div class="content_wrapper content-padded">
                                <h5 class="table_banner_title mb-1">{{ __('Payroll Report') }}</h5>
                                <p class="text-muted mb-0">{{ __('Payroll items, gross, deductions and net payable.') }}</p>
                            </div>
                        </a>
                    </div>
                @endif
                @if(auth()->user()?->hasAnyPermission(['provident_fund.view', 'provident_fund.report', 'payroll.manage-pf']))
                    <div class="col-md-3">
                        <a href="{{ route('reports.provident-fund') }}" class="card no-border text-decoration-none">
                            <div class="content_wrapper content-padded">
                                <h5 class="table_banner_title mb-1">{{ __('Provident Fund Report') }}</h5>
                                <p class="text-muted mb-0">{{ __('Yearly employee and employer PF contributions.') }}</p>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
