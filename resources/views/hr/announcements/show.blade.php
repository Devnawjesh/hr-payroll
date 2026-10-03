@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title d-flex justify-content-between align-items-center">
        <h1><i class="icon-bell"></i> {{ __('Notice/Announcement Details') }}</h1>
        <a href="{{ route('announcements.index') }}" class="btn btn-custom-default"><i class="icon-arrow-left"></i> {{ __('Back') }}</a>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                        <div>
                            <h3 class="mb-1">{{ $announcement->title }}</h3>
                            <div class="small text-muted">
                                {{ __('Type:') }} {{ __(ucfirst($announcement->announcement_type)) }} |
                                {{ __('Priority:') }} {{ __(ucfirst($announcement->priority)) }} |
                                Audience:
                                @if($announcement->audience_type === 'employees')
                                    Selected Employees ({{ ($audienceEmployees ?? collect())->count() }})
                                @else
                                    All Users
                                @endif
                            </div>
                        </div>
                        <div class="small text-muted text-end">
                            <div>Published: {{ $announcement->publish_at?->format('Y-m-d H:i') ?? '-' }}</div>
                            <div>{{ __('Expires:') }} {{ $announcement->expires_at?->format('Y-m-d') ?? __('No Expiry') }}</div>
                        </div>
                    </div>

                    <hr>

                    <div class="announcement-body" style="white-space: pre-wrap;">
                        {{ $announcement->body }}
                    </div>

                    @if($announcement->audience_type === 'employees')
                        <hr>
                        <h5 class="mb-2">{{ __('Audience Employees') }}</h5>
                        <div class="row">
                            @forelse(($audienceEmployees ?? collect()) as $employee)
                                @php($fullName = trim($employee->first_name . ' ' . ($employee->last_name ?? '')))
                                <div class="col-md-4 mb-1">
                                    <span class="badge bg-secondary">{{ $fullName }} ({{ $employee->employee_code }})</span>
                                </div>
                            @empty
                                <div class="col-12 text-muted">{{ __('No employee targets configured.') }}</div>
                            @endforelse
                        </div>
                    @endif
                    <hr>

                    <div class="small text-muted">
                        {{ __('Created by:') }} {{ $announcement->creator?->name ?? __('System') }} | {{ __('Approved by:') }} {{ $announcement->approver?->name ?? '-' }} | {{ __('Published by:') }} {{ $announcement->publisher?->name ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
