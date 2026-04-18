@extends('layouts.auth', ['title' => 'ZerithonLabs | Create Account', 'heading' => 'Create Account', 'subtitle' => 'Set up a new ZerithonLabs account to get started.'])

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
    <form method="POST" action="{{ route('register.store') }}">
        @csrf
        <div class="form">
            <div class="form-group">
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter fullname" required autofocus>
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Enter email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
            </div>
            <div class="form-group">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
            </div>
            <input type="submit" class="btn btn-custom btn-fullwidth" value="Submit">
        </div>
    </form>

    <div class="login-footer">
        <a href="{{ route('password.request') }}">Forgot Password</a> | <a href="{{ route('login') }}">Login</a>
    </div>
@endsection
