@extends('layouts.app')

@section('title', 'Admin Login')

@section('auth')
    <x-auth-wrapper>
        @include('partials.flash-messages')

        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">S</div>
                <h1>Welcome Back</h1>
                <p>
                    Sign in to continue managing Servixa with a clean and secure admin experience.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <x-input
                    label="Email Address"
                    name="email"
                    type="email"
                    placeholder="Enter your admin email"
                    required
                />

                <x-input
                    label="Password"
                    name="password"
                    type="password"
                    placeholder="Enter your password"
                    required
                />

                <x-button type="submit" variant="primary" style="width: 100%;">
                    <span>🔐</span>
                    <span>Login</span>
                </x-button>
            </form>

            <div class="auth-footer">
                Admin access only. Authorized personnel only.
            </div>
        </div>
    </x-auth-wrapper>
@endsection
