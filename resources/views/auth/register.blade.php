@extends('layouts.app')

@section('title', 'Mantiq - Sign Up')

@section('content')
<div class="login-container">

    <div class="logo">
        <h1 style="display:flex;align-items:center;justify-content:center;gap:12px;"><img src="{{ asset('images/brand/logo-mark.svg') }}" alt="" width="42" height="42"><span>Mantiq</span></h1>
    </div>

    <h2 class="title">Create Your Account</h2>

    <form method="POST" action="{{ route('register.submit') }}">
        @csrf

        <div class="form-group">
            <label for="name">Full Name</label>
            <div class="input-box">
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter your full name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                >
            </div>
            @error('name')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <div class="input-box">
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    value="{{ old('email') }}"
                    required
                >
            </div>
            @error('email')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-box">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Create a password"
                    required
                >
            </div>
            @error('password')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <div class="input-box">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Confirm your password"
                    required
                >
            </div>
        </div>

        <button type="submit" class="login-button">
            Create Account
        </button>
    </form>

    <p class="signup">
        Already have an account?
        <a href="{{ route('login') }}">Sign In</a>
    </p>

</div>
@endsection
