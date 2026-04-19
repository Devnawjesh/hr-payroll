@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title d-flex justify-content-between align-items-center">
        <h1><i class="icon-badge"></i> Designations</h1>
        <a href="{{ route('designations.create') }}" class="btn btn-custom"><i class="icon-plus"></i> Add Designation</a>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper" style="padding:20px;">
                    <form method="GET" class="row g-2 mb-3">
                        <div class="col-md-3">
                            <input type="text" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="Search name/code/description">
                        </div>
                        <div class="col-md-3">
                            <select name="department_id" class="form-control">
                                <option value="0">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ (int) $filters['department_id'] === $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}{{ $department->code ? ' ('.$department->code.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="active" {{ $filters['status'] === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $filters['status'] === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="per_page" class="form-control">
                                @foreach([10,20,50,100] as $size)
                                    <option value="{{ $size }}" {{ (int) $filters['per_page'] === $size ? 'selected' : '' }}>{{ $size }} / page</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button class="btn btn-custom" type="submit"><i class="icon-magnifier"></i> Filter</button>
                            <a href="{{ route('designations.index') }}" class="btn btn-custom-default"><i class="icon-refresh"></i></a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Department</th>
                                    <th>Employees</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($designations as $designation)
                                    <tr>
                                        <td>{{ $designation->name }}</td>
                                        <td>{{ $designation->code ?: '-' }}</td>
                                        <td>{{ $designation->department?->name ?? '-' }}</td>
                                        <td>{{ $designation->employees_count }}</td>
                                        <td>
                                            @if($designation->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="action-buttons">
                                            <a href="{{ route('designations.edit', $designation) }}" title="Edit Designation">
                                                <i class="icon-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('designations.destroy', $designation) }}" style="display:inline;" onsubmit="return confirm('Delete this designation?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Delete Designation"><i class="icon-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No designations found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $designations->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
