@extends('layouts.auth', ['title' => 'ZerithonLabs | Sign In', 'heading' => 'Sign In', 'subtitle' => 'Continue to your ZerithonLabs dashboard.'])

@section('content')

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
@endsection
