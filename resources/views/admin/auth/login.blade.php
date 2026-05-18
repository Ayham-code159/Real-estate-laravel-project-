@extends('layouts.app')

@section('title', 'Admin Login')

@section('auth')
    <style>
        .servixa-auth-bg {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
        }

        .servixa-auth-bg::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image: url("{{ asset('images/servixa-logo.png') }}");
            background-repeat: no-repeat;
            background-position: center;
            background-size: min(1100px, 95vw);
            opacity: 0.07;
            filter: blur(6px);
            transform: scale(1.08);
            pointer-events: none;
            z-index: 0;
        }

        .servixa-auth-bg::after {
            content: "";
            position: fixed;
            inset: 0;
            background:
                radial-gradient(circle at 20% 20%, rgba(124, 58, 237, 0.12), transparent 30%),
                radial-gradient(circle at 80% 75%, rgba(139, 92, 246, 0.12), transparent 32%);
            pointer-events: none;
            z-index: 1;
        }

        .servixa-auth-bg .auth-card {
            position: relative;
            z-index: 2;
            backdrop-filter: blur(14px);
            background: rgba(255, 255, 255, 0.86);
        }
    </style>

    <div class="servixa-auth-bg">
        <x-auth-wrapper>
            @include('partials.flash-messages')

            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-logo">S</div>
                    <h1>Welcome Back</h1>
                
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
    </div>
@endsection
