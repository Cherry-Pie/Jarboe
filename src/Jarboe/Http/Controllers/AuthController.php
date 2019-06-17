<?php

namespace Yaro\Jarboe\Http\Controllers;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use PragmaRX\Google2FA\Google2FA;
use Yaro\Jarboe\Events\Auth\InvalidOTP;
use Yaro\Jarboe\Events\Auth\LoginFailed;
use Yaro\Jarboe\Events\Auth\Registered;
use Yaro\Jarboe\Events\Auth\LoginSuccess;
use Yaro\Jarboe\Events\Auth\Logout;
use Yaro\Jarboe\Http\Middleware\RedirectIfAdminAuthenticated;
use Yaro\Jarboe\Http\Requests\Auth\LoginRequest;
use Yaro\Jarboe\Http\Requests\Auth\RegisterRequest;

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
        return view('jarboe::auth.login', [
            'shouldOTP' => $this->shouldOTP(),
        ]);
    }

    protected function getAuthCredentials(LoginRequest $request): array
    {
        return $request->only('email', 'password');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $this->getAuthCredentials($request);
        $shouldRemember = (bool) $request->get('remember');
        if (Auth::guard(admin_user_guard())->attempt($credentials, $shouldRemember)) {
            if ($this->isValidOTP(admin_user(), $request->get('otp'))) {
                event(new LoginSuccess(admin_user()));
                return redirect(admin_url(config('jarboe.admin_panel.dashboard')));
            }
            event(new InvalidOTP(admin_user()));
            Auth::guard(admin_user_guard())->logout();
        }
        event(new LoginFailed($request));

        return redirect()->back()->withErrors(['email' => [__('jarboe::auth.user_not_found')]]);
    }

    public function logout()
    {
        event(new Logout(admin_user()));
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
            'avatar' => '',
        ] + $this->getDataForOTP();
        $admin = $model::create($data);
        event(new Registered($admin));

        Auth::guard(admin_user_guard())->login($admin);
        event(new LoginSuccess(admin_user()));

        if ($this->shouldOTP()) {
            $url = app(Google2FA::class)->getQRCodeUrl(
                config('jarboe.admin_panel.two_factor_auth.company_name', config('app.name')),
                $admin->email,
                $admin->otp_secret
            );
            $writer = new Writer(new ImageRenderer(
                new RendererStyle(180),
                new SvgImageBackEnd()
            ));
            return view('jarboe::auth.otp', [
                'svg' => $writer->writeString($url),
                'secret' => $admin->otp_secret,
            ]);
        }
        return redirect(admin_url(config('jarboe.admin_panel.dashboard')));
    }

    private function isValidOTP($user, $otp): bool
    {
        if (!$this->shouldOTP()) {
            return true;
        }

        $isValid = false;
        try {
            $isValid = app(Google2FA::class)->verifyKey($user->otp_secret, $otp);
        } catch (IncompatibleWithGoogleAuthenticatorException $e) {
        } catch (InvalidCharactersException $e) {
        } catch (SecretKeyTooShortException $e) {
        }

        return $isValid;
    }

    private function shouldOTP(): bool
    {
        return (bool) config('jarboe.admin_panel.two_factor_auth.enabled');
    }

    private function getDataForOTP(): array
    {
        if ($this->shouldOTP()) {
            return [
                'otp_secret' => app(Google2FA::class)->generateSecretKey(),
            ];
        }

        return [];
    }
}
