@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title">
        <h1><i class="icon-check"></i> {{ __('Signup Approval') }}</h1>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    <div class="mb-3">
                        <strong>{{ $user->name }}</strong><br>
                        {{ $user->email }} | {{ $user->phone ?: '-' }}
                    </div>

                    @if($user->employee)
                        <div class="alert alert-success">
                            {{ __('Employee profile linked:') }}
                            <strong>{{ trim($user->employee->first_name.' '.$user->employee->last_name) }}</strong>
                            <small class="text-muted">({{ $user->employee->employee_code }})</small>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <strong>{{ __('Employee setup required before approval.') }}</strong>
                            <div>{{ __('Create an employee profile and link this user account before assigning roles and approving access.') }}</div>
                            <a href="{{ route('employees.create', ['user_id' => $user->id]) }}" class="btn btn-custom btn-sm mt-2">
                                <i class="icon-user"></i> {{ __('Create Employee Profile') }}
                            </a>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.approval.process', $user) }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label>{{ __('Decision') }}</label>
                            <select name="decision" class="form-control" required>
                                <option value="approve" {{ old('decision') === 'approve' ? 'selected' : '' }}>{{ __('Approve') }}</option>
                                <option value="reject" {{ old('decision') === 'reject' ? 'selected' : '' }}>{{ __('Reject') }}</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>{{ __('Assign Roles (required for approval)') }}</label>
                            @php($assignedRoleIds = collect(old('role_ids', $user->roles->pluck('id')->all()))->map(fn($id) => (int) $id)->all())
                            <div class="row">
                                @foreach($roles as $role)
                                    <div class="col-md-3 mb-2">
                                        @php($checkboxId = 'approval_role_'.$role->id)
                                        <div class="checkbox checkbox-default">
                                            <input id="{{ $checkboxId }}" type="checkbox" name="role_ids[]" value="{{ $role->id }}" {{ in_array($role->id, $assignedRoleIds, true) ? 'checked' : '' }}>
                                            <label for="{{ $checkboxId }}">{{ $role->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>{{ __('Reject Reason (if rejected)') }}</label>
                            <input type="text" class="form-control" name="rejected_reason" value="{{ old('rejected_reason') }}" maxlength="255">
                        </div>

                        <button class="btn btn-custom" type="submit"><i class="icon-check"></i> {{ __('Submit Decision') }}</button>
                        <a href="{{ route('users.index') }}" class="btn btn-custom-default"><i class="icon-arrow-left"></i> {{ __('Back') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
