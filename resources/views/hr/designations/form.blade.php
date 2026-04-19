@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title">
        <h1><i class="icon-badge"></i> {{ $mode === 'edit' ? 'Edit Designation' : 'Add Designation' }}</h1>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper" style="padding:20px;">
                    <form method="POST" action="{{ $mode === 'edit' ? route('designations.update', $designation) : route('designations.store') }}">
                        @csrf
                        @if($mode === 'edit')
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label>Designation Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $designation->name ?? '') }}" required>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label>Code</label>
                                <input type="text" name="code" class="form-control" value="{{ old('code', $designation->code ?? '') }}" maxlength="30">
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label>Department</label>
                                <select name="department_id" class="form-control">
                                    <option value="">Select Department</option>
                                    @php($selectedDepartment = old('department_id', $designation->department_id ?? null))
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ (string) $selectedDepartment === (string) $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}{{ $department->code ? ' ('.$department->code.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label>Status</label>
                                @php($isActive = (int) old('is_active', isset($designation) ? (int) $designation->is_active : 1))
                                <select name="is_active" class="form-control" required>
                                    <option value="1" {{ $isActive === 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $isActive === 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $designation->description ?? '') }}</textarea>
                            </div>
                        </div>

                        <button class="btn btn-custom" type="submit">
                            <i class="{{ $mode === 'edit' ? 'icon-check' : 'icon-plus' }}"></i>
                            {{ $mode === 'edit' ? 'Update Designation' : 'Create Designation' }}
                        </button>
                        <a href="{{ route('designations.index') }}" class="btn btn-custom-default"><i class="icon-arrow-left"></i> Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
