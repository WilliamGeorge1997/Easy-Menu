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

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember_me'))) {
            $admin = Auth::guard('admin')->user();
            if ($admin->lang) {
                app()->setLocale($admin->lang);
            }
            $request->session()->regenerate();
            return to_route('admin.dashboard');
        }

        throw ValidationException::withMessages([
            'login' => [__('auth.failed')],
        ]);
    }
}
