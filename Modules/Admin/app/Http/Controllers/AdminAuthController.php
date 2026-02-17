<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\Admin\Http\Requests\AdminLoginRequest;

class AdminAuthController extends Controller
{
    public function loginForm()
    {
        return view('admin::login');
    }

    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->validated() + ['is_active' => 1];
        $guard = Auth::guard('admin');
        if ($guard->attempt($credentials, $request->boolean('remember_me'))) {
            $request->session()->regenerate();

            $admin = $guard->user();
            $request->session()->put('locale', $admin->lang);

            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'login' => [__('auth.failed')],
        ]);
    }
}
