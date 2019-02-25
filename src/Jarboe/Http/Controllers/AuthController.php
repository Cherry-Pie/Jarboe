<?php

namespace Yaro\Jarboe\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Yaro\Jarboe\Http\Requests\Auth\LoginRequest;
use Yaro\Jarboe\Http\Requests\Auth\RegisterRequest;
use Yaro\Jarboe\Http\Middleware\RedirectIfAdminAuthenticated;

class AuthController extends Controller
{
    private $guard;

    public function __construct()
    {
        $this->guard = admin_user_guard();

        $this->middleware(RedirectIfAdminAuthenticated::class)->except([
            'logout',
        ]);
    }

    public function showLogin()
    {
        return view('jarboe::auth.login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::guard($this->guard)->attempt($request->only('email', 'password'), (bool) $request->get('remember'))) {
            return redirect(admin_url(config('jarboe.admin_panel.dashboard')));
        }

        return redirect()->back()->withErrors(['email' => [__('jarboe::auth.user_not_found')]]);
    }

    public function logout()
    {
        Auth::guard($this->guard)->logout();

        return redirect(admin_url('login'));
    }

    public function showRegister()
    {
        return view('jarboe::auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $model = config('jarboe.admin_panel.admin_model');

        $admin = $model::create($request->only('name', 'email', 'password'));

        Auth::guard($this->guard)->login($admin);

        return redirect(admin_url(config('jarboe.admin_panel.dashboard')));
    }

}