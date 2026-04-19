@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title">
        <h1><i class="icon-user"></i> {{ $mode === 'edit' ? 'Edit User' : 'Add User' }}</h1>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper" style="padding:20px;">
                    <form method="POST" action="{{ $mode === 'edit' ? route('users.update', $user) : route('users.store') }}">
                        @csrf
                        @if($mode === 'edit')
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Status</label>
                                <select name="account_status" class="form-control" required>
                                    @php($selectedStatus = old('account_status', $user->account_status ?? 'active'))
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ $selectedStatus === $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>{{ $mode === 'edit' ? 'New Password (Optional)' : 'Password' }}</label>
                                <input type="password" name="password" class="form-control" {{ $mode === 'create' ? 'required' : '' }}>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ $mode === 'edit' ? 'Confirm New Password' : 'Confirm Password' }}</label>
                                <input type="password" name="password_confirmation" class="form-control" {{ $mode === 'create' ? 'required' : '' }}>
                            </div>

                            <div class="col-md-12 form-group">
                                <label>Assign Roles</label>
                                @php($assignedRoleIds = collect(old('role_ids', isset($user) ? $user->roles->pluck('id')->all() : []))->map(fn($id) => (int) $id)->all())
                                <div class="row">
                                    @foreach($roles as $role)
                                        <div class="col-md-3 mb-2">
                                            @php($checkboxId = 'role_'.$role->id)
                                            <div class="checkbox checkbox-default">
                                                <input id="{{ $checkboxId }}" type="checkbox" name="role_ids[]" value="{{ $role->id }}" {{ in_array($role->id, $assignedRoleIds, true) ? 'checked' : '' }}>
                                                <label for="{{ $checkboxId }}">{{ $role->name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-custom" type="submit">
                            <i class="{{ $mode === 'edit' ? 'icon-check' : 'icon-user-follow' }}"></i>
                            {{ $mode === 'edit' ? 'Update User' : 'Create User' }}
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-custom-default"><i class="icon-arrow-left"></i> Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
