<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CheckAuth
{
    public function handle(Request $request, \Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return $next($request);
        $secretKey     = env('SECRET_KEY');
        $sessionCookie = @$_COOKIE['scn_session'];
        if (!Auth::check()) {
            $data = callApiSSO(env('API_GET_SESSION'), $sessionCookie, $secretKey);
            if (isset($data['code']) && Response::HTTP_OK === $data['code']) {
                $user = @$data['data']['user'];
                Auth::loginUsingId($user['id']);

                return $next($request);
            }
            Cookie::queue(Cookie::forget('sso-authen'));

            return redirect(env('URL_SERVER_SSO') . '/login?redirect_url=' . env('URL_CLIENT_SSO'));
        }

        if (!Cookie::get('sso-authen')) {
            $data = callApiSSO(env('API_GET_SESSION'), $sessionCookie, $secretKey);
            if (isset($data['code']) && Response::HTTP_OK === $data['code']) {
                Cookie::queue('sso-authen', true, 5);

                return $next($request);
            }
            Auth::logout();

            return redirect(env('URL_SERVER_SSO') . '/login?redirect_url=' . env('URL_CLIENT_SSO'));
        }

        return $next($request);
    }
}
