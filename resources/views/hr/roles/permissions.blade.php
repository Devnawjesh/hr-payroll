@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title">
        <h1><i class="icon-shield"></i> Role Permissions: {{ $role->name }}</h1>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper" style="padding:20px;">
                    <form method="POST" action="{{ route('roles.permissions.sync', $role) }}">
                        @csrf

                        @foreach($permissionsByGroup as $group => $permissions)
                            <div class="mb-3 p-2" style="border:1px solid #e5e7eb; border-radius:8px;">
                                <h6 class="mb-2 text-uppercase">{{ str_replace('_', ' ', $group) }}</h6>
                                <div class="row">
                                    @foreach($permissions as $permission)
                                        <div class="col-md-3 mb-2">
                                            @php($checkboxId = 'permission_'.$role->id.'_'.$permission->id)
                                            <div class="checkbox checkbox-default">
                                                <input id="{{ $checkboxId }}" type="checkbox" name="permission_ids[]" value="{{ $permission->id }}" {{ in_array($permission->id, $selectedPermissionIds, true) ? 'checked' : '' }}>
                                                <label for="{{ $checkboxId }}">{{ $permission->name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <button class="btn btn-custom" type="submit"><i class="icon-check"></i> Save Permissions</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-custom-default"><i class="icon-arrow-left"></i> Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
