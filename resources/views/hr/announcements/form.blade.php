@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title">
        <h1><i class="icon-bell"></i> {{ __('Add Notice/Announcement') }}</h1>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper content-padded">
                    <form method="POST" action="{{ route('announcements.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                            <label>{{ __('Type') }}</label>
                                <select name="announcement_type" class="form-control" required>
                                    <option value="notice" {{ old('announcement_type', 'notice') === 'notice' ? 'selected' : '' }}>{{ __('Notice') }}</option>
                                    <option value="announcement" {{ old('announcement_type') === 'announcement' ? 'selected' : '' }}>{{ __('Announcement') }}</option>
                                </select>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label>{{ __('Priority') }}</label>
                            <select name="priority" class="form-control" required>
                                <option value="normal" {{ old('priority', 'normal') === 'normal' ? 'selected' : '' }}>{{ __('Normal') }}</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>{{ __('High') }}</option>
                            </select>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label>{{ __('Title') }}</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" maxlength="180" required>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label>{{ __('Message') }}</label>
                                <textarea id="announcement-body" name="body" class="form-control" rows="8" maxlength="10000" required>{{ old('body') }}</textarea>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                            <label>{{ __('Audience') }}</label>
                            <select name="audience_type" class="form-control" required>
                                @php($audienceType = old('audience_type', 'all'))
                                <option value="all" {{ $audienceType === 'all' ? 'selected' : '' }}>{{ __('All Users') }}</option>
                                <option value="employees" {{ $audienceType === 'employees' ? 'selected' : '' }}>{{ __('Selected Employees') }}</option>
                            </select>
                            </div>

                            <div class="col-md-12 form-group mb-3 audience-employees-block {{ $audienceType === 'employees' ? '' : 'd-none' }}">
                            <label>{{ __('Employees') }}</label>
                                @php($selectedEmployees = collect(old('audience_employee_ids', []))->map(fn ($id) => (int) $id)->all())
                                <select name="audience_employee_ids[]" class="form-control js-example-basic-multiple audience-employees-select" multiple>
                                    @foreach(($employees ?? collect()) as $employee)
                                        @php($label = trim($employee->first_name . ' ' . ($employee->last_name ?? '')))
                                        <option value="{{ $employee->id }}" {{ in_array((int) $employee->id, $selectedEmployees, true) ? 'selected' : '' }}>
                                            {{ $label }} ({{ $employee->employee_code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label>{{ __('Expires At (Optional)') }}</label>
                                <input
                                    type="text"
                                    name="expires_at"
                                    class="form-control announcement-date-picker"
                                    placeholder="{{ __('YYYY-MM-DD') }}"
                                    autocomplete="off"
                                    value="{{ old('expires_at') }}"
                                >
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label>{{ __('Pinned') }}</label>
                                @php($isPinned = (int) old('is_pinned', 0))
                                <select name="is_pinned" class="form-control" required>
                                    <option value="0" {{ $isPinned === 0 ? 'selected' : '' }}>{{ __('No') }}</option>
                                    <option value="1" {{ $isPinned === 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                </select>
                            </div>
                            <!--<div class="col-md-4 form-group mb-3">
                                <label>{{ __('Pinned') }}</label>
                                @php($isPinned = (int) old('is_pinned', 1))
                                <select name="is_pinned" class="form-control" required>
                                    <option value="0" {{ $isPinned === 0 ? 'selected' : '' }}>{{ __('No') }}</option>
                                    <option value="1" {{ $isPinned === 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                </select>
                            </div> -->
                        </div>

                        <button class="btn btn-custom" type="submit">
                            <i class="icon-plus"></i>
                            {{ __('Create') }}
                        </button>
                        <a href="{{ route('announcements.index') }}" class="btn btn-custom-default"><i class="icon-arrow-left"></i> {{ __('Back') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')    
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        (function () {
            if (window.CKEDITOR) {
                CKEDITOR.replace('announcement-body', {
                    height: 260,
                });
            }

            if (window.jQuery && $.fn.datepicker) {
                $('.announcement-date-picker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true,
                    orientation: 'bottom auto'
                });
            }

            const $audience = $('select[name=\"audience_type\"]');
            const $employeeBlock = $('.audience-employees-block');
            const $employeeSelect = $('.audience-employees-select');

            function ensureEmployeeSelect2() {
                if (!(window.jQuery && $.fn.select2) || !$employeeSelect.length) {
                    return;
                }

                if ($employeeSelect.hasClass('select2-hidden-accessible')) {
                    $employeeSelect.select2('destroy');
                }

                $employeeSelect.select2({
                    width: '100%',
                    placeholder: @json(__('Select employees')),
                    allowClear: false
                });
            }

            function toggleAudienceBlock() {
                if ($audience.val() === 'employees') {
                    $employeeBlock.removeClass('d-none');
                    ensureEmployeeSelect2();
                } else {
                    $employeeBlock.addClass('d-none');
                }
            }

            ensureEmployeeSelect2();
            toggleAudienceBlock();
            $audience.on('change', toggleAudienceBlock);
        })();
    </script>
@endpush
