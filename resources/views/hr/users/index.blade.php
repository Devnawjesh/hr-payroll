@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title d-flex justify-content-between align-items-center">
        <h1><i class="icon-user"></i> Users</h1>
        <a href="{{ route('users.create') }}" class="btn btn-custom"><i class="icon-plus"></i> Add User</a>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper" style="padding:20px;">
                    <form method="GET" class="row g-2 mb-3">
                        <div class="col-md-3">
                            <input type="text" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="Search name/email/phone">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                @foreach(['pending_approval','active','inactive','rejected'] as $status)
                                    <option value="{{ $status }}" {{ $filters['status'] === $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="role_id" class="form-control">
                                <option value="0">All Roles</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ (int) $filters['role_id'] === $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="per_page" class="form-control">
                                @foreach([10,20,50,100] as $size)
                                    <option value="{{ $size }}" {{ (int) $filters['per_page'] === $size ? 'selected' : '' }}>{{ $size }} / page</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button class="btn btn-custom" type="submit"><i class="icon-magnifier"></i> Filter</button>
                            <a href="{{ route('users.index') }}" class="btn btn-custom-default"><i class="icon-refresh"></i> Reset</a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Roles</th>
                                    <th>Approved</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone ?: '-' }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $user->account_status ?? 'pending_approval')) }}</td>
                                        <td>
                                            @forelse($user->roles as $role)
                                                <span class="badge bg-secondary">{{ $role->name }}</span>
                                            @empty
                                                -
                                            @endforelse
                                        </td>
                                        <td>
                                            @if($user->approved_at)
                                                {{ $user->approved_at->format('Y-m-d H:i') }}
                                                @if($user->approvedBy)
                                                    <div class="small text-muted">by {{ $user->approvedBy->name }}</div>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="action-buttons">
                                            <a href="{{ route('users.edit', $user) }}" title="Edit User">
                                                <i class="icon-pencil"></i>
                                            </a>
                                            @if($user->account_status === 'pending_approval')
                                                <a href="{{ route('users.approval', $user) }}" title="Approve Signup">
                                                    <i class="icon-check"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
