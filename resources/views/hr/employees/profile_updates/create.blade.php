@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title">
        <h1><i class="icon-note"></i> {{ __('Update Profile') }}</h1>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper content-padded">
                        @if($lastPending && ! session()->has('success'))
                            <div class="alert alert-info">
                                Your pending request was submitted on {{ $lastPending->submitted_at?->format('Y-m-d H:i') }} and is waiting for HR review.
                            </div>
                        @endif

                            @if($lastRejected)
                                <div class="alert alert-danger">
                                    Last request was rejected. Reason: {{ $lastRejected->review_comments ?: 'N/A' }}.
                                    Please correct and resubmit.
                                </div>
                            @endif

                    <form method="POST" action="{{ route('employees.profile-updates.store') }}" enctype="multipart/form-data">
                        @csrf

                        @php($pendingPayload = is_array($lastPending?->payload) ? $lastPending->payload : [])
                        @php($pendingGeneral = is_array($pendingPayload['general_info'] ?? null) ? $pendingPayload['general_info'] : [])
                        @php($addresses = old('addresses', $pendingPayload['addresses'] ?? $employee->addresses->map->only(['address_type','line_1','line_2','city','state','postal_code','country','is_primary'])->toArray()))
                        @php($banks = old('bank_accounts', $pendingPayload['bank_accounts'] ?? $employee->bankAccounts->map->only(['bank_name','branch_name','account_holder_name','account_number','routing_number','account_type','is_primary','is_salary_account','salary_account_start_date','salary_account_end_date'])->toArray()))
                        
                        @php($contacts = old('emergency_contacts', $pendingPayload['emergency_contacts'] ?? $employee->emergencyContacts->map->only(['name','relationship','phone','email','address','is_primary'])->toArray()))
                        @php($documents = old('documents', $pendingPayload['documents'] ?? $employee->documents->map->only(['document_type','title','file_path','issued_date','expiry_date'])->toArray()))
                        @php($selectedGender = old('gender', $pendingGeneral['gender'] ?? $employee->gender))
                        @php($selectedMaritalStatus = old('marital_status', $pendingGeneral['marital_status'] ?? $employee->marital_status))
                        @php($managerName = $employee->manager ? trim($employee->manager->first_name.' '.$employee->manager->last_name).' ('.$employee->manager->employee_code.')' : 'No Manager')
                        @php($pendingAvatarPath = $pendingGeneral['avatar_path'] ?? null)
                        @php($profileImage = $pendingAvatarPath ? asset($pendingAvatarPath) : ($employee->avatar_path ? asset($employee->avatar_path) : asset('assets/img/user/default.jpg')))

                            <ul class="nav nav-tabs profile-update-tab-nav mb-3" id="profile-update-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-general" data-bs-toggle="tab" data-bs-target="#pane-general" type="button" role="tab" aria-controls="pane-general" aria-selected="true">
                                    {{ __('General Information') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-organization" data-bs-toggle="tab" data-bs-target="#pane-organization" type="button" role="tab" aria-controls="pane-organization" aria-selected="false">
                                    {{ __('Organization Information') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-other" data-bs-toggle="tab" data-bs-target="#pane-other" type="button" role="tab" aria-controls="pane-other" aria-selected="false">
                                    {{ __('Other Information') }}
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="pane-general" role="tabpanel" aria-labelledby="tab-general">
                                <div class="profile-panel mb-4">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Profile Image') }}</label>
                                        <div class="profile-avatar-update">
                                            <img id="profile_avatar_preview" src="{{ $profileImage }}" alt="Employee Profile Image">
                                            <div class="profile-avatar-actions">
                                                <input type="file" name="avatar" id="profile_avatar_input" class="profile-avatar-input" accept=".jpg,.jpeg,.png,.webp" {{ $lastPending ? 'disabled' : '' }}>
                                                <label for="profile_avatar_input" class="btn btn-custom btn-sm mb-2 {{ $lastPending ? 'disabled' : '' }}">
                                                    <i class="icon-picture"></i> {{ __('Upload Photo') }}
                                                </label>
                                                <small id="profile_avatar_file_name" class="text-muted d-block">
                                                    {{ $pendingAvatarPath ? __('Pending image selected') : __('No file chosen') }}
                                                </small>
                                                <small class="text-muted d-block mt-1">{{ __('JPG, PNG, WEBP. Max 2MB.') }}</small>
                                                @error('avatar')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('First Name') }}</label>
                                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $pendingGeneral['first_name'] ?? $employee->first_name) }}" required>
                                        </div>
                                            <div class="col-md-4">
                                                <label class="form-label">{{ __('Last Name') }}</label>
                                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $pendingGeneral['last_name'] ?? $employee->last_name) }}">
                                            </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Gender') }}</label>
                                            <select name="gender" class="form-control">
                                                <option value="">{{ __('Select Gender') }}</option>
                                                @foreach(['male','female','other'] as $gender)
                                                    <option value="{{ $gender }}" {{ $selectedGender === $gender ? 'selected' : '' }}>{{ __(ucfirst($gender)) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Date of Birth') }}</label>
                                            <input type="text" name="date_of_birth" class="form-control datetimepicker" value="{{ old('date_of_birth', $pendingGeneral['date_of_birth'] ?? $employee->date_of_birth) }}" placeholder="{{ __('YYYY-MM-DD') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Phone') }}</label>
                                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $pendingGeneral['phone'] ?? $employee->phone) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Alternate Phone') }}</label>
                                            <input type="text" name="alternate_phone" class="form-control" value="{{ old('alternate_phone', $pendingGeneral['alternate_phone'] ?? $employee->alternate_phone) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Marital Status') }}</label>
                                            <select name="marital_status" class="form-control">
                                                <option value="">{{ __('Select') }}</option>
                                                @foreach(['single','married','divorced','widowed'] as $marital)
                                                    <option value="{{ $marital }}" {{ $selectedMaritalStatus === $marital ? 'selected' : '' }}>{{ __(ucfirst($marital)) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                            <div class="col-md-4">
                                                <label class="form-label">{{ __('NID Number') }}</label>
                                            <input type="text" name="nid_number" class="form-control" value="{{ old('nid_number', $pendingGeneral['nid_number'] ?? $employee->nid_number) }}">
                                            </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Passport Number') }}</label>
                                            <input type="text" name="passport_number" class="form-control" value="{{ old('passport_number', $pendingGeneral['passport_number'] ?? $employee->passport_number) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Tax ID') }}</label>
                                            <input type="text" name="tax_id" class="form-control" value="{{ old('tax_id', $pendingGeneral['tax_id'] ?? $employee->tax_id) }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">{{ __('Notes') }}</label>
                                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $pendingGeneral['notes'] ?? $employee->notes) }}</textarea>
                                        </div>
                                </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pane-organization" role="tabpanel" aria-labelledby="tab-organization">
                                <div class="profile-panel mb-4">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Employee Code') }}</label>
                                            <input type="text" class="form-control" value="{{ $employee->employee_code }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Work Email') }}</label>
                                            <input type="text" class="form-control" value="{{ $employee->work_email ?: '-' }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Login Email') }}</label>
                                            <input type="text" class="form-control" value="{{ $employee->user?->email ?: '-' }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Department') }}</label>
                                            <input type="text" class="form-control" value="{{ $employee->department?->name ?: '-' }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Designation') }}</label>
                                            <input type="text" class="form-control" value="{{ $employee->designation?->name ?: '-' }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Salary Grade') }}</label>
                                            <input type="text" class="form-control" value="{{ $employee->salaryGrade ? $employee->salaryGrade->grade_name.' ('.$employee->salaryGrade->grade_code.')' : '-' }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Reporting To') }}</label>
                                            <input type="text" class="form-control" value="{{ $managerName }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Date of Joining') }}</label>
                                            <input type="text" class="form-control" value="{{ $employee->date_of_joining ?: '-' }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Employment Type') }}</label>
                                            <input type="text" class="form-control" value="{{ $employee->employment_type ? __(ucfirst(str_replace('_',' ', $employee->employment_type))) : '-' }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Employment Status') }}</label>
                                            <input type="text" class="form-control" value="{{ $employee->employment_status ? __(ucfirst(str_replace('_',' ', $employee->employment_status))) : '-' }}" readonly>
                                        </div>
                                </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pane-other" role="tabpanel" aria-labelledby="tab-other">
                                <h5 class="table_banner_title mb-2">{{ __('Addresses') }}</h5>
                                <div id="addresses-container" class="mb-3"></div>
                                <button type="button" class="btn btn-custom-default btn-sm mb-4" data-add-row="addresses"><i class="icon-plus"></i> {{ __('Add Address') }}</button>

                                <h5 class="table_banner_title mb-2">{{ __('Bank Accounts') }}</h5>
                                <div id="banks-container" class="mb-3"></div>
                                <button type="button" class="btn btn-custom-default btn-sm mb-4" data-add-row="banks"><i class="icon-plus"></i> {{ __('Add Bank Account') }}</button>

                                <h5 class="table_banner_title mb-2">{{ __('Emergency Contacts') }}</h5>
                                <div id="contacts-container" class="mb-3"></div>
                                <button type="button" class="btn btn-custom-default btn-sm mb-4" data-add-row="contacts"><i class="icon-plus"></i> {{ __('Add Contact') }}</button>

                                <h5 class="table_banner_title mb-2">{{ __('Documents') }}</h5>
                                <div id="documents-container" class="mb-3"></div>
                                <button type="button" class="btn btn-custom-default btn-sm mb-4" data-add-row="documents"><i class="icon-plus"></i> {{ __('Add Document') }}</button>
                            </div>
                        </div>

                        <div class="mt-3" id="profile-submit-actions">
                            <button type="submit" class="btn btn-custom" {{ $lastPending ? 'disabled' : '' }}>
                                <i class="icon-check"></i> {{ __('Submit for HR Approval') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var avatarInput = document.getElementById('profile_avatar_input');
    var avatarPreview = document.getElementById('profile_avatar_preview');
    var avatarFileName = document.getElementById('profile_avatar_file_name');

    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function () {
            var file = avatarInput.files && avatarInput.files[0] ? avatarInput.files[0] : null;
            if (!file) {
                return;
            }

            if (avatarFileName) {
                avatarFileName.textContent = file.name;
            }

            var reader = new FileReader();
            reader.onload = function (event) {
                avatarPreview.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    var data = {
        addresses: @json($addresses),
        banks: @json($banks),
        contacts: @json($contacts),
        documents: @json($documents),
    };

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function boolChecked(v) {
        return (v === true || v === 1 || v === '1') ? 'checked' : '';
    }

    function rowHtml(type, i, row) {
        row = row || {};

        if (type === 'addresses') {
            return `<div class="profile-row-card" data-row>
                <div class="row g-2">
                    <div class="col-md-2"><input name="addresses[${i}][address_type]" class="form-control" placeholder="{{ __('Type') }}" value="${escapeHtml(row.address_type)}"></div>
                    <div class="col-md-4"><input name="addresses[${i}][line_1]" class="form-control" placeholder="{{ __('Address line 1') }}" value="${escapeHtml(row.line_1)}"></div>
                    <div class="col-md-3"><input name="addresses[${i}][line_2]" class="form-control" placeholder="{{ __('Address line 2') }}" value="${escapeHtml(row.line_2)}"></div>
                    <div class="col-md-2"><input name="addresses[${i}][city]" class="form-control" placeholder="{{ __('City') }}" value="${escapeHtml(row.city)}"></div>
                    <div class="col-md-1"><button type="button" class="btn btn-custom-default btn-sm profile-row-remove" data-remove-row><i class="icon-trash"></i></button></div>
                    <div class="col-md-2"><input name="addresses[${i}][state]" class="form-control" placeholder="{{ __('State') }}" value="${escapeHtml(row.state)}"></div>
                    <div class="col-md-2"><input name="addresses[${i}][postal_code]" class="form-control" placeholder="{{ __('Postal') }}" value="${escapeHtml(row.postal_code)}"></div>
                    <div class="col-md-3"><input name="addresses[${i}][country]" class="form-control" placeholder="{{ __('Country') }}" value="${escapeHtml(row.country)}"></div>
                    <div class="col-md-3 d-flex align-items-center">
                        <div class="checkbox checkbox-default mb-0">
                            <input id="addresses_primary_${i}" type="checkbox" name="addresses[${i}][is_primary]" value="1" ${boolChecked(row.is_primary)}>
                            <label for="addresses_primary_${i}">{{ __('Primary') }}</label>
                        </div>
                    </div>
                </div>
            </div>`;
        }

        if (type === 'banks') {
            return `<div class="profile-row-card" data-row>
                <div class="row g-2">
                    <div class="col-md-3"><input name="bank_accounts[${i}][bank_name]" class="form-control" placeholder="{{ __('Bank name') }}" value="${escapeHtml(row.bank_name)}"></div>
                    <div class="col-md-3"><input name="bank_accounts[${i}][branch_name]" class="form-control" placeholder="{{ __('Branch name') }}" value="${escapeHtml(row.branch_name)}"></div>
                    <div class="col-md-3"><input name="bank_accounts[${i}][account_holder_name]" class="form-control" placeholder="{{ __('Account holder') }}" value="${escapeHtml(row.account_holder_name)}"></div>
                    <div class="col-md-2"><input name="bank_accounts[${i}][account_number]" class="form-control" placeholder="{{ __('Account no') }}" value="${escapeHtml(row.account_number)}"></div>
                    <div class="col-md-1"><button type="button" class="btn btn-custom-default btn-sm profile-row-remove" data-remove-row><i class="icon-trash"></i></button></div>
                    <div class="col-md-2"><input name="bank_accounts[${i}][routing_number]" class="form-control" placeholder="{{ __('Routing') }}" value="${escapeHtml(row.routing_number)}"></div>
                    <div class="col-md-2"><input name="bank_accounts[${i}][account_type]" class="form-control" placeholder="{{ __('Type') }}" value="${escapeHtml(row.account_type)}"></div>
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="checkbox checkbox-default mb-0">
                                <input id="banks_primary_${i}" type="checkbox" name="bank_accounts[${i}][is_primary]" value="1" ${boolChecked(row.is_primary)}>
                                <label for="banks_primary_${i}">{{ __('Primary') }}</label>
                            </div>
                        </div>
                    <input type="hidden" name="bank_accounts[${i}][is_salary_account]" value="${boolChecked(row.is_salary_account) ? '1' : ''}">
                    <input type="hidden" name="bank_accounts[${i}][salary_account_start_date]" value="${escapeHtml(row.salary_account_start_date)}">
                    <input type="hidden" name="bank_accounts[${i}][salary_account_end_date]" value="${escapeHtml(row.salary_account_end_date)}">
                    </div>
                </div>`;
        }

        if (type === 'contacts') {
            return `<div class="profile-row-card" data-row>
                <div class="row g-2">
                    <div class="col-md-3"><input name="emergency_contacts[${i}][name]" class="form-control" placeholder="{{ __('Name') }}" value="${escapeHtml(row.name)}"></div>
                    <div class="col-md-2"><input name="emergency_contacts[${i}][relationship]" class="form-control" placeholder="{{ __('Relationship') }}" value="${escapeHtml(row.relationship)}"></div>
                    <div class="col-md-2"><input name="emergency_contacts[${i}][phone]" class="form-control" placeholder="{{ __('Phone') }}" value="${escapeHtml(row.phone)}"></div>
                    <div class="col-md-3"><input name="emergency_contacts[${i}][email]" class="form-control" placeholder="{{ __('Email') }}" value="${escapeHtml(row.email)}"></div>
                    <div class="col-md-1"><button type="button" class="btn btn-custom-default btn-sm profile-row-remove" data-remove-row><i class="icon-trash"></i></button></div>
                    <div class="col-md-5"><input name="emergency_contacts[${i}][address]" class="form-control" placeholder="{{ __('Address') }}" value="${escapeHtml(row.address)}"></div>
                    <div class="col-md-3 d-flex align-items-center">
                        <div class="checkbox checkbox-default mb-0">
                            <input id="contacts_primary_${i}" type="checkbox" name="emergency_contacts[${i}][is_primary]" value="1" ${boolChecked(row.is_primary)}>
                            <label for="contacts_primary_${i}">{{ __('Primary') }}</label>
                        </div>
                    </div>
                </div>
            </div>`;
        }

        if (type === 'documents') {
            return `<div class="profile-row-card" data-row>
                <div class="row g-2">
                    <div class="col-md-2"><input name="documents[${i}][document_type]" class="form-control" placeholder="{{ __('Type') }}" value="${escapeHtml(row.document_type)}"></div>
                    <div class="col-md-3"><input name="documents[${i}][title]" class="form-control" placeholder="{{ __('Title') }}" value="${escapeHtml(row.title)}"></div>
                    <div class="col-md-4"><input name="documents[${i}][file_path]" class="form-control" placeholder="{{ __('File path') }}" value="${escapeHtml(row.file_path)}"></div>
                    <div class="col-md-2"><button type="button" class="btn btn-custom-default btn-sm profile-row-remove" data-remove-row><i class="icon-trash"></i></button></div>
                    <div class="col-md-2"><input name="documents[${i}][issued_date]" class="form-control datetimepicker" placeholder="{{ __('Issued') }}" value="${escapeHtml(row.issued_date)}"></div>
                    <div class="col-md-2"><input name="documents[${i}][expiry_date]" class="form-control datetimepicker" placeholder="{{ __('Expiry') }}" value="${escapeHtml(row.expiry_date)}"></div>
                </div>
            </div>`;
        }

        return '';
    }

    var refs = {
        addresses: {container: document.getElementById('addresses-container'), key: 'addresses'},
        banks: {container: document.getElementById('banks-container'), key: 'banks'},
        contacts: {container: document.getElementById('contacts-container'), key: 'contacts'},
        documents: {container: document.getElementById('documents-container'), key: 'documents'},
    };

    function render(type) {
        var ref = refs[type];
        if (!ref || !ref.container) return;

        var rows = Array.isArray(data[ref.key]) ? data[ref.key] : [];
        if (rows.length === 0) {
            ref.container.innerHTML = '';
            $('.datetimepicker').datepicker({ format: 'yyyy-mm-dd' });
            return;
        }

        ref.container.innerHTML = rows.map(function (row, i) {
            return rowHtml(type, i, row);
        }).join('');

        $('.datetimepicker').datepicker({ format: 'yyyy-mm-dd' });
    }

    Object.keys(refs).forEach(render);

    var submitActions = document.getElementById('profile-submit-actions');

    function syncSubmitActionVisibility() {
        if (!submitActions) return;
        var organizationPane = document.getElementById('pane-organization');
        var isOrganizationActive = organizationPane && organizationPane.classList.contains('active');
        submitActions.style.display = isOrganizationActive ? 'none' : '';
    }

    document.querySelectorAll('#profile-update-tabs [data-bs-toggle="tab"]').forEach(function (tabBtn) {
        tabBtn.addEventListener('shown.bs.tab', syncSubmitActionVisibility);
    });

    syncSubmitActionVisibility();

    document.querySelectorAll('[data-add-row]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var type = btn.getAttribute('data-add-row');
            var key = refs[type].key;
            if (!Array.isArray(data[key])) data[key] = [];
            data[key].push({});
            render(type);
        });
    });

    document.addEventListener('click', function (event) {
        var removeBtn = event.target.closest('[data-remove-row]');
        if (!removeBtn) return;

        var card = removeBtn.closest('[data-row]');
        if (!card) return;

        var container = card.parentElement;
        var type = Object.keys(refs).find(function (k) { return refs[k].container === container; });
        if (!type) return;

        var index = Array.prototype.indexOf.call(container.children, card);
        var key = refs[type].key;
        if (Array.isArray(data[key]) && index > -1) {
            data[key].splice(index, 1);
            render(type);
        }
    });
})();
</script>
@endpush
