@extends('layouts.auth', ['title' => 'ZerithonLabs | Change Password', 'heading' => 'Reset Password', 'subtitle' => 'Choose a new password for your account.'])

@push('styles')
<style>
    .form-wrapper {
        width: 100%;
        margin: 0 auto;
        padding: 50px;
        background-color: #fff;
        animation: flip;
        animation-duration: 320ms;
    }
    @keyframes flip {
        from { transform: rotateY(180deg); }
        to { transform: rotateY(0deg); }
    }
</style>
@endpush

@section('content')
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form">
            <div class="form-group">
                <input type="email" name="email" class="form-control" value="{{ old('email', $email) }}" placeholder="Enter email" required autofocus>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
            </div>
            <input type="submit" class="btn btn-custom btn-fullwidth" value="Submit">
        </div>
    </form>

    <div class="login-footer">
        <a href="{{ route('register') }}">Register</a> | <a href="{{ route('login') }}">Login</a>
    </div>
@endsection