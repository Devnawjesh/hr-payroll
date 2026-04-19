@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title">
        <h1><i class="icon-check"></i> Signup Approval</h1>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper" style="padding:20px;">
                    <div class="mb-3">
                        <strong>{{ $user->name }}</strong><br>
                        {{ $user->email }} | {{ $user->phone ?: '-' }}
                    </div>

                    <form method="POST" action="{{ route('users.approval.process', $user) }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label>Decision</label>
                            <select name="decision" class="form-control" required>
                                <option value="approve" {{ old('decision') === 'approve' ? 'selected' : '' }}>Approve</option>
                                <option value="reject" {{ old('decision') === 'reject' ? 'selected' : '' }}>Reject</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Assign Roles (required for approval)</label>
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
                            <label>Reject Reason (if rejected)</label>
                            <input type="text" class="form-control" name="rejected_reason" value="{{ old('rejected_reason') }}" maxlength="255">
                        </div>

                        <button class="btn btn-custom" type="submit"><i class="icon-check"></i> Submit Decision</button>
                        <a href="{{ route('users.index') }}" class="btn btn-custom-default"><i class="icon-arrow-left"></i> Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
