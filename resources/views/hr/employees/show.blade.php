@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    @php
        $canUpdateEmployee = auth()->user()?->hasPermission('employee.update') ?? false;
        $dateOfBirthDisplay = $employee->date_of_birth ? \Illuminate\Support\Carbon::parse($employee->date_of_birth)->format('m-d') : '-';
    @endphp
    <div class="page-title d-flex justify-content-between align-items-center">
        <h1><i class="icon-user"></i> {{ __('Employee Profile') }}</h1>
        @if($canUpdateEmployee)
            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-custom"><i class="icon-pencil"></i> {{ __('Edit') }}</a>
        @endif
    </div>

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="employee-profile-avatar">
                            @if($employee->avatar_path)
                                <img src="{{ asset($employee->avatar_path) }}" alt="Employee Avatar">
                            @else
                                <i class="icon-user employee-profile-avatar-icon"></i>
                            @endif
                        </div>
                        <h4 class="mb-0">{{ trim($employee->first_name.' '.$employee->last_name) }} <small class="text-muted">({{ $employee->employee_code }})</small></h4>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4"><strong>{{ __('Status:') }}</strong> {{ __(ucfirst(str_replace('_',' ', $employee->employment_status))) }}</div>
                        <div class="col-md-4"><strong>{{ __('Type:') }}</strong> {{ __(ucfirst(str_replace('_',' ', $employee->employment_type))) }}</div>
                        <div class="col-md-4"><strong>{{ __('Join Date:') }}</strong> {{ $employee->date_of_joining }}</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4"><strong>{{ __('Department:') }}</strong> {{ $employee->department?->name ?? '-' }}</div>
                        <div class="col-md-4"><strong>{{ __('Designation:') }}</strong> {{ $employee->designation?->name ?? '-' }}</div>
                        <div class="col-md-4"><strong>{{ __('Manager:') }}</strong> {{ $employee->manager ? trim($employee->manager->first_name.' '.$employee->manager->last_name) : '-' }}</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4"><strong>{{ __('Phone:') }}</strong> {{ $employee->phone ?: '-' }}</div>
                        <div class="col-md-4"><strong>{{ __('Work Email:') }}</strong> {{ $employee->work_email ?: '-' }}</div>
                        <div class="col-md-4"><strong>{{ __('Linked User:') }}</strong> {{ $employee->user?->email ?? '-' }}</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4"><strong>{{ __('Date of Birth:') }}</strong> {{ $dateOfBirthDisplay }}</div>
                        <div class="col-md-4"><strong>{{ __('Marital Status:') }}</strong> {{ $employee->marital_status ? __(ucfirst($employee->marital_status)) : '-' }}</div>
                        <div class="col-md-4"><strong>{{ __('Marriage Date:') }}</strong> {{ $employee->marriage_date ?: '-' }}</div>
                    </div>

                    <hr>
                    <h5>{{ __('Addresses') }}</h5>
                    @forelse($employee->addresses as $address)
                        <div class="mb-2">
                            <strong>{{ __(ucfirst($address->address_type ?: 'Address')) }}{{ $address->is_primary ? ' ('.__('Primary').')' : '' }}:</strong>
                            {{ collect([$address->line_1, $address->line_2, $address->city, $address->state, $address->postal_code, $address->country])->filter()->implode(', ') ?: '-' }}
                        </div>
                    @empty
                        <p class="text-muted mb-2">{{ __('No addresses added.') }}</p>
                    @endforelse

                    <h5 class="mt-3">{{ __('Bank Accounts') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-2">
                            <thead>
                                <tr>
                                    <th>{{ __('Bank') }}</th>
                                    <th>{{ __('Branch') }}</th>
                                    <th>{{ __('Account Holder') }}</th>
                                    <th>{{ __('Account No') }}</th>
                                    <th>{{ __('Routing') }}</th>
                                    <th>{{ __('Type') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->bankAccounts as $bank)
                                    <tr>
                                        <td>{{ $bank->bank_name ?: '-' }}{{ $bank->is_primary ? ' ('.__('Primary').')' : '' }}</td>
                                        <td>{{ $bank->branch_name ?: '-' }}</td>
                                        <td>{{ $bank->account_holder_name ?: '-' }}</td>
                                        <td>{{ $bank->account_number ?: '-' }}</td>
                                        <td>{{ $bank->routing_number ?: '-' }}</td>
                                        <td>{{ $bank->account_type ?: '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-muted">{{ __('No bank accounts added.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <h5 class="mt-3">{{ __('Emergency Contacts') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-2">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Relationship') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Address') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->emergencyContacts as $contact)
                                    <tr>
                                        <td>{{ $contact->name ?: '-' }}{{ $contact->is_primary ? ' ('.__('Primary').')' : '' }}</td>
                                        <td>{{ $contact->relationship ?: '-' }}</td>
                                        <td>{{ $contact->phone ?: '-' }}</td>
                                        <td>{{ $contact->email ?: '-' }}</td>
                                        <td>{{ $contact->address ?: '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-muted">{{ __('No emergency contacts added.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <h5 class="mt-3">{{ __('Documents') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-2">
                            <thead>
                                <tr>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('File Path') }}</th>
                                    <th>{{ __('Issued') }}</th>
                                    <th>{{ __('Expiry') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->documents as $document)
                                    @php($documentHref = $document->file_path ? (filter_var($document->file_path, FILTER_VALIDATE_URL) ? $document->file_path : asset($document->file_path)) : null)
                                    <tr>
                                        <td>{{ $document->document_type ?: '-' }}</td>
                                        <td>{{ $document->title ?: '-' }}</td>
                                        <td>
                                            @if($documentHref)
                                                <a href="{{ $documentHref }}" target="_blank" rel="noopener">{{ __('Open') }}</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $document->issued_date ?: '-' }}</td>
                                        <td>{{ $document->expiry_date ?: '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-muted">{{ __('No documents added.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($employee->subordinates->count() > 0)
                        <hr>
                        <h5>{{ __('Subordinates') }}</h5>
                        <ul>
                            @foreach($employee->subordinates as $subordinate)
                                <li>{{ trim($subordinate->first_name.' '.$subordinate->last_name) }} ({{ $subordinate->employee_code }})</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('employees.index') }}" class="btn btn-custom-default"><i class="icon-arrow-left"></i> {{ __('Back') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
