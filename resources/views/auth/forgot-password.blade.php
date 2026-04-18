@extends('layouts.auth', ['title' => 'ZerithonLabs | Reset Password', 'heading' => 'Reset Password', 'subtitle' => 'Enter your email address and we will help you reset access.'])

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
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form">
            <div class="form-group">
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Enter email" required autofocus>
            </div>
            <input type="submit" class="btn btn-custom btn-fullwidth" value="Submit">
        </div>
    </form>

    <div class="login-footer">
        <a href="{{ route('register') }}">Register</a> | <a href="{{ route('login') }}">Login</a>
    </div>
@endsection
