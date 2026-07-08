@extends('layouts.auth', ['title' => 'Zeri HR | Sign In', 'heading' => 'Sign In', 'authClass' => 'auth-login'])

@section('content')
    @php($demoPassword = config('demo_users.password', 'P@ssword'))
    @php($demoAccounts = config('demo_users.accounts', []))

    <div class="login-minimal-heading">
        <h2>Welcome back</h2>
        <p>Sign in to continue.</p>
    </div>

    <form method="POST" action="{{ route('login.store') }}">
        @csrf
        <div class="form">
            <div class="form-group">
                <input type="email" id="login-email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Enter email" required autofocus>
            </div>
            <div class="form-group">
                <input type="password" id="login-password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <div class="form-group">
                <input type="checkbox" name="remember" class="form-check-input" id="remember-me" value="1" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember-me">Remember Me</label>
            </div>
            <input type="submit" class="btn btn-custom btn-fullwidth" value="Submit">
        </div>
    </form>

    <div class="login-footer">
        <a href="{{ route('password.request') }}">Forgot Password</a> | <a href="{{ route('register') }}">Register</a>
    </div>

    @if(! empty($demoAccounts))
        <div class="demo-login-panel">
            <div class="demo-login-title">Demo accounts</div>
            <div class="demo-login-grid">
                @foreach($demoAccounts as $account)
                    <button
                        type="button"
                        class="demo-copy-btn"
                        data-email="{{ $account['email'] }}"
                        data-password="{{ $demoPassword }}"
                    >
                        <span>{{ $account['label'] }}</span>
                        <small>Copy</small>
                    </button>
                @endforeach
            </div>
            <div class="demo-login-note">Default password: <code>{{ $demoPassword }}</code></div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.demo-copy-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                var email = button.getAttribute('data-email');
                var password = button.getAttribute('data-password');
                var emailInput = document.getElementById('login-email');
                var passwordInput = document.getElementById('login-password');

                if (emailInput) {
                    emailInput.value = email;
                }

                if (passwordInput) {
                    passwordInput.value = password;
                }

                var text = 'Email: ' + email + '\nPassword: ' + password;
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text);
                }

                var label = button.querySelector('small');
                if (label) {
                    label.textContent = 'Copied';
                }

                setTimeout(function () {
                    if (label) {
                        label.textContent = 'Copy';
                    }
                }, 1400);
            });
        });
    </script>
@endpush
