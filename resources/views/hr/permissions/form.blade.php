@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title">
        <h1><i class="icon-key"></i> {{ $mode === 'edit' ? 'Edit Permission' : 'Add Permission' }}</h1>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper" style="padding:20px;">
                    <form method="POST" action="{{ $mode === 'edit' ? route('permissions.update', $permission) : route('permissions.store') }}">
                        @csrf
                        @if($mode === 'edit')
                            @method('PUT')
                        @endif

                        <div class="form-group mb-3">
                            <label>Group Name</label>
                            <input type="text" name="group_name" class="form-control" value="{{ old('group_name', $permission->group_name ?? '') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $permission->name ?? '') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Slug (optional)</label>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug', $permission->slug ?? '') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control" value="{{ old('description', $permission->description ?? '') }}">
                        </div>

                        <button class="btn btn-custom" type="submit">
                            <i class="{{ $mode === 'edit' ? 'icon-check' : 'icon-plus' }}"></i>
                            {{ $mode === 'edit' ? 'Update Permission' : 'Create Permission' }}
                        </button>
                        <a href="{{ route('permissions.index') }}" class="btn btn-custom-default"><i class="icon-arrow-left"></i> Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
