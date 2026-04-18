@extends('layouts.backend')

@section('content')
<div class="wrapper-page">
    <div class="page-title">
        <h1><i class="icon-settings"></i> System Configuration</h1>
    </div>

    @include('partials.flash')

    <div class="page-content">
        <div class="container-fluid">
            <div class="card no-border">
                <div class="content_wrapper" style="padding: 20px;">
                    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <h5 class="table_banner_title mb-3">General</h5>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Application Name</label>
                                <input type="text" class="form-control" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? config('app.name')) }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Company Name</label>
                                <input type="text" class="form-control" name="company_name" value="{{ old('company_name', $settings['company_name'] ?? '') }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Company Email</label>
                                <input type="email" class="form-control" name="company_email" value="{{ old('company_email', $settings['company_email'] ?? '') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Company Phone</label>
                                <input type="text" class="form-control" name="company_phone" value="{{ old('company_phone', $settings['company_phone'] ?? '') }}">
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Company Address</label>
                                <textarea class="form-control" rows="2" name="company_address">{{ old('company_address', $settings['company_address'] ?? '') }}</textarea>
                            </div>
                        </div>

                        <hr>
                        <h5 class="table_banner_title mb-3">Branding</h5>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <div class="brand-upload-box">
                                    <span class="label">Logo</span>
                                    <div class="brand-picker-row">
                                        <input id="company_logo" type="file" class="brand-file-input" name="company_logo" accept=".png,.jpg,.jpeg,.svg,.webp,image/*">
                                        <label for="company_logo" class="btn btn-custom mb-0">Choose Logo</label>
                                        <span id="company_logo_name" class="brand-file-name">No file selected</span>
                                    </div>
                                    @if(!empty($settings['company_logo']))
                                        <div class="mt-2"><img src="{{ asset($settings['company_logo']) }}" alt="Logo" style="max-height:48px;"></div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="brand-upload-box">
                                    <span class="label">Favicon</span>
                                    <div class="brand-picker-row">
                                        <input id="company_favicon" type="file" class="brand-file-input" name="company_favicon" accept=".ico,.png,.jpg,.jpeg,.svg,.webp,image/*">
                                        <label for="company_favicon" class="btn btn-custom mb-0">Choose Favicon</label>
                                        <span id="company_favicon_name" class="brand-file-name">No file selected</span>
                                    </div>
                                    @if(!empty($settings['company_favicon']))
                                        <div class="mt-2"><img src="{{ asset($settings['company_favicon']) }}" alt="Favicon" style="max-height:32px;"></div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="table_banner_title mb-3">Localization</h5>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Currency Prefix</label>
                                <input type="text" class="form-control" name="currency_prefix" value="{{ old('currency_prefix', $settings['currency_prefix'] ?? '৳') }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Employee Code Prefix</label>
                                <input type="text" class="form-control" name="employee_code_prefix" value="{{ old('employee_code_prefix', $settings['employee_code_prefix'] ?? 'EMP') }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Invoice Prefix</label>
                                <input type="text" class="form-control" name="invoice_prefix" value="{{ old('invoice_prefix', $settings['invoice_prefix'] ?? 'INV') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Date Format</label>
                                <input type="text" class="form-control" name="date_format" value="{{ old('date_format', $settings['date_format'] ?? 'Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Time Zone</label>
                                @php($tz = old('time_zone', $settings['time_zone'] ?? config('app.timezone')))
                                <select class="form-control" name="time_zone" required>
                                    @foreach($timezones as $zone)
                                        <option value="{{ $zone }}" {{ $tz === $zone ? 'selected' : '' }}>{{ $zone }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr>
                        <h5 class="table_banner_title mb-3">SMTP Configuration</h5>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Mailer</label>
                                <input type="text" class="form-control" value="SMTP" readonly>
                                <input type="hidden" name="mail_mailer" value="smtp">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Mail Host</label>
                                <input type="text" class="form-control" name="mail_host" value="{{ old('mail_host', $settings['mail_host'] ?? config('mail.mailers.smtp.host')) }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Mail Port</label>
                                <input type="number" class="form-control" name="mail_port" value="{{ old('mail_port', $settings['mail_port'] ?? config('mail.mailers.smtp.port')) }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Mail Username</label>
                                <input type="text" class="form-control" name="mail_username" value="{{ old('mail_username', $settings['mail_username'] ?? config('mail.mailers.smtp.username')) }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Mail Password</label>
                                <input type="password" class="form-control" name="mail_password" placeholder="Leave blank to keep current password">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Encryption</label>
                                <select class="form-control" name="mail_encryption">
                                    @php($enc = old('mail_encryption', $settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption')))
                                    <option value="">None</option>
                                    @foreach(['tls','ssl','starttls'] as $opt)
                                        <option value="{{ $opt }}" {{ $enc === $opt ? 'selected' : '' }}>{{ strtoupper($opt) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>From Address</label>
                                <input type="email" class="form-control" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address'] ?? config('mail.from.address')) }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>From Name</label>
                                <input type="text" class="form-control" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name'] ?? config('mail.from.name')) }}">
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-custom">
                                <i class="icon-check"></i> Save Settings
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
        function bindFileName(inputId, outputId) {
            var input = document.getElementById(inputId);
            var output = document.getElementById(outputId);
            if (!input || !output) {
                return;
            }

            input.addEventListener('change', function () {
                if (input.files && input.files.length > 0) {
                    output.textContent = input.files[0].name;
                } else {
                    output.textContent = 'No file selected';
                }
            });
        }

        bindFileName('company_logo', 'company_logo_name');
        bindFileName('company_favicon', 'company_favicon_name');
    })();
</script>
@endpush
