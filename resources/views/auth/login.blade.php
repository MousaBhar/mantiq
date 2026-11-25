@extends('layouts.app')

@section('title', 'Mantiq - Login')

@section('content')

<main class="login-container">

    <!-- Logo -->
    <div class="logo">Mantiq</div>

    <!-- Page Title -->
    <section class="title">
        <h1>Login</h1>
        <p>Welcome back! Please enter your details.</p>
    </section>

    <!-- Login Form -->
    <form id="loginForm" method="POST" action="{{ route('login.submit') }}">
        @csrf

        @if ($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email</label>

            <div class="input-box">
                <svg width="20" height="20" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor"
                     stroke-width="1.8" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                    <path d="m3 7 9 6 9-6"/>
                </svg>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="example@gmail.com"
                    autocomplete="email"
                    value="{{ old('email') }}"
                    required>
            </div>
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password</label>

            <div class="input-box">
                <svg width="20" height="20" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor"
                     stroke-width="1.8" aria-hidden="true">
                    <rect x="4" y="10" width="16" height="11" rx="2"/>
                    <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                </svg>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="************"
                    autocomplete="current-password"
                    required>

                <button
                    type="button"
                    id="togglePassword"
                    aria-label="Show password"
                    style="border:0;background:none;cursor:pointer">

                    <svg width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="1.8" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>

                </button>
            </div>

            <!-- Password Options -->
            <div class="password-options">

                <label class="remember">
                    <input type="checkbox" name="remember">
                    Remember Me
                </label>

                <a href="#">Forget Password?</a>

            </div>
        </div>

        <!-- Login Button -->
        <button type="submit" class="login-button">
            Login
        </button>

    </form>

    <!-- Divider -->
    <div class="divider">
        <span>Or continue with</span>
    </div>

    <!-- Google Sign In -->
    <a href="{{ route('google.redirect') }}"
       class="secondary-button"
       style="text-decoration: none;">

        <svg width="20" height="20" viewBox="0 0 48 48"
             aria-hidden="true">
            <path fill="#EA4335"
                d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5Z"/>
            <path fill="#4285F4"
                d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.72 7.18l7.65 5.93c4.47-4.13 7.11-10.2 7.11-17.58Z"/>
            <path fill="#FBBC05"
                d="M10.53 28.59A14.5 14.5 0 0 1 9.75 24c0-1.59.27-3.13.76-4.59l-7.98-6.19A23.9 23.9 0 0 0 0 24c0 3.89.93 7.57 2.56 10.78l7.97-6.19Z"/>
            <path fill="#34A853"
                d="M24 48c6.48 0 11.93-2.13 15.9-5.87l-7.65-5.93c-2.12 1.43-4.84 2.3-8.25 2.3-6.26 0-11.57-4.22-13.46-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48Z"/>
        </svg>

        Continue with Google

    </a>

    <!-- Guest Registration -->
    <a href="{{ route('register') }}"
       class="secondary-button"
       style="text-decoration: none;">

        <svg width="20" height="20" viewBox="0 0 24 24"
             fill="none" stroke="currentColor"
             stroke-width="1.8" aria-hidden="true">
            <circle cx="12" cy="8" r="4"/>
            <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
        </svg>

Continue as a Guest
    </a>

    <!-- Sign Up -->
    <div class="signup">
        Don't have an account?
        <a href="{{ route('register') }}">Sign Up</a>
    </div>

</main>

@endsection
