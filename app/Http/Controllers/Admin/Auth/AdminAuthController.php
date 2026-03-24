<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Services\Admin\Auth\AdminAuthService;
use App\Http\Requests\Admin\Auth\AdminLoginRequest;

class AdminAuthController extends Controller
{
    public function __construct(
        private AdminAuthService $authService
    ) {}

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(AdminLoginRequest $request): RedirectResponse
    {
        $this->authService->login($request->validated());

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout();

        return redirect()->route('admin.login');
    }
}
