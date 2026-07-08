@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title d-flex justify-content-between align-items-center">
        <h1><i class="icon-wallet"></i> {{ __('Salary Templates') }}</h1>
        <div class="d-flex gap-2">
            @if(auth()->user()?->hasAnyPermission(['salary_template.view', 'employee_salary.view', 'employee_salary.list', 'payroll.manage-salary-templates']))
                <a href="{{ route('payroll.salary-template-assignments.index') }}" class="btn btn-custom-default"><i class="icon-list"></i> {{ __('Employee Salaries') }}</a>
            @endif
            @if(auth()->user()?->hasAnyPermission(['salary_template.assign', 'employee_salary.assign', 'payroll.manage-salary-templates']))
                <a href="{{ route('payroll.salary-template-assignments.create') }}" class="btn btn-custom-default"><i class="icon-user-follow"></i> {{ __('Assign Employee Salary') }}</a>
            @endif
            @if(auth()->user()?->hasAnyPermission(['salary_template.create', 'payroll.manage-salary-templates']))
                <a href="{{ route('payroll.salary-templates.create') }}" class="btn btn-custom"><i class="icon-plus"></i> {{ __('Add Template') }}</a>
            @endif
        </div>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    <form method="GET" class="row g-2 mb-3">
                        <div class="col-md-5"><input type="text" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="{{ __('Search template name or code') }}"></div>
                        <div class="col-md-2"><select name="per_page" class="form-control">@foreach([10,20,50,100] as $size)<option value="{{ $size }}" {{ (int)$filters['per_page']===$size?'selected':'' }}>{{ $size }} / page</option>@endforeach</select></div>
                        <div class="col-md-5 d-flex gap-2">
                            <button class="btn btn-custom" type="submit"><i class="icon-magnifier"></i> {{ __('Filter') }}</button>
                            <a href="{{ route('payroll.salary-templates.index') }}" class="btn btn-custom-default"><i class="icon-refresh"></i> {{ __('Reset') }}</a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Frequency') }}</th>
                                    <th>{{ __('Basic') }}</th>
                                    <th>{{ __('Allowances') }}</th>
                                    <th>{{ __('PF %') }}</th>
                                    <th>{{ __('Tax %') }}</th>
                                    <th>{{ __('Assignments') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($templates as $template)
                                    @php($allowances = (float)$template->house_rent + (float)$template->medical_allowance + (float)$template->conveyance_allowance + (float)$template->other_allowance)
                                    <tr>
                                        <td>{{ $template->name }}</td>
                                        <td>{{ $template->code }}</td>
                                        <td>{{ __(ucfirst($template->pay_frequency)) }}</td>
                                        <td>{{ number_format((float) $template->basic_salary, 2) }}</td>
                                        <td>{{ number_format($allowances, 2) }}</td>
                                        <td>{{ number_format((float) $template->provident_fund_percent, 2) }}</td>
                                        <td>{{ number_format((float) $template->tax_percent, 2) }}</td>
                                        <td>{{ $template->employees_count }}</td>
                                        <td><span class="badge {{ $template->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $template->is_active ? __('Active') : __('Inactive') }}</span></td>
                                        <td class="action-buttons">
                                            @if(auth()->user()?->hasAnyPermission(['salary_template.update', 'payroll.manage-salary-templates']))
                                                <a href="{{ route('payroll.salary-templates.edit', $template) }}" title="{{ __('Edit') }}"><i class="icon-pencil"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="10" class="text-center">{{ __('No salary templates found.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $templates->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
