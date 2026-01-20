<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAdmin;
use App\Http\Requests\RegisterAdmin;
use App\Http\Requests\UpdatePasswordRequest;
use App\Services\AuthAdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    protected $authAdminService;

    public function __construct(AuthAdminService $authAdminService)
    {

        $this->authAdminService = $authAdminService;

    }

    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function showRegister()
    {
        return view('admin.auth.register');
    }

    public function register(RegisterAdmin $req)
    {

        return $this->authAdminService->register($req->validated());

    }

    public function login(LoginAdmin $req)
    {

        return $this->authAdminService->login($req);

    }

    public function dashboard()
    {

        return view('/');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect()->route('admin.login');
    }

    public function sendResetLink(Request $req)
    {
        $req->validate([
            'email' => ['required', 'email'],
        ]);
        $status = Password::broker('admins')->sendResetLink(
            $req->only('email')
        );

        return back()->with('status', 'Operation successful!');

    }

    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgotPassword');
    }

    public function showResetForm($token)
    {
        return view('admin.auth.resetPasswordForm', [
            'token' => $token,
            'email' => request('email'),
        ]);
    }

    // reset password
    public function updatePassword(ResetPasswordAdmin $req)
    {

        return $this->authAdminService->resetPassword($req);

    }

    public function passwordUpdate(UpdatePasswordRequest $request)
    {

        return $this->authAdminService->updatePassword($request);

    }
}
