<?php

namespace Yaro\Jarboe\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Yaro\Jarboe\Http\Requests\Auth\LoginRequest;
use Yaro\Jarboe\Http\Requests\Auth\RegisterRequest;
use Yaro\Jarboe\Http\Middleware\RedirectIfAdminAuthenticated;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(RedirectIfAdminAuthenticated::class)->except([
            'logout',
        ]);
    }

    public function root()
    {
        return redirect(admin_url('login'));
    }

    public function showLogin()
    {
        return view('jarboe::auth.login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::guard(admin_user_guard())->attempt($request->only('email', 'password'), (bool) $request->get('remember'))) {
            return redirect(admin_url(config('jarboe.admin_panel.dashboard')));
        }

        return redirect()->back()->withErrors(['email' => [__('jarboe::auth.user_not_found')]]);
    }

    public function logout()
    {
        Auth::guard(admin_user_guard())->logout();

        return redirect(admin_url('login'));
    }

    public function showRegister()
    {
        return view('jarboe::auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $model = config('jarboe.admin_panel.admin_model');

        $data = $request->only('name', 'email') + [
                'password' => bcrypt($request->get('password')),
            ];
        $admin = $model::create($data);

        Auth::guard(admin_user_guard())->login($admin);

        return redirect(admin_url(config('jarboe.admin_panel.dashboard')));
    }
}
