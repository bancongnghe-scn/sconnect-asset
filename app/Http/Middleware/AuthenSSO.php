<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthenSSO
{
    public function handle(Request $request, \Closure $next)
    {
        $secretKey     = env('SECRET_KEY');
        $token         = @$_GET['token'];
        $sig           = @$_GET['sig'];
        $sessionCookie = @$_COOKIE[env('SESSION_NAME')];

        if (hash_equals(hash_hmac('sha256', $token, $secretKey), $sig)) {

            $login = $this->handleLoginSSO($sessionCookie, $secretKey);
            if ($login) {
                Cookie::queue('sso-authen', true, 5);

                return redirect()->route('home');
            }
        }
        callApiSSO(env('API_LOGOUT_SSO'), $sessionCookie, $secretKey);

        return redirect(env('URL_SERVER_SSO') . '/login?redirect_url=' . env('URL_CLIENT_SSO'));
    }

    /**
     * @throws Exception
     */
    private function handleLoginSSO($sessionCookie, $secretKey): bool
    {
        $data = callApiSSO(env('API_GET_SESSION'), $sessionCookie, $secretKey);
        if (isset($data['code']) && Response::HTTP_OK === $data['code']) {
            $user = @$data['data']['user'];

            Auth::loginUsingId($user['id']);

            return true;
        }

        return false;
    }
}
