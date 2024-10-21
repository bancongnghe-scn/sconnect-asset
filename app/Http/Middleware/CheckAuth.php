<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class CheckAuth
{
    public function handle(Request $request, \Closure $next)
    {
        $secretKey     = env('SECRET_KEY');
        $sessionCookie = @$_COOKIE['scn_session'];
        // if (!Auth::check()) {
        if (!Session::has('auth_user')) {
            $data = callApiSSO(env('API_GET_SESSION_DOCKER'), $sessionCookie, $secretKey);
            if (isset($data['code']) && Response::HTTP_OK === $data['code']) {
                $user = @$data['data']['user'];
                Session::put('auth_user', $user);

                // Auth::loginUsingId($user['id']);
                return $next($request);
            }
            Cookie::queue(Cookie::forget('sso-authen'));

            return redirect(env('URL_SERVER_SSO') . '/login?redirect_url=' . env('URL_CLIENT_SSO'));
        }

        if (!Cookie::get('sso-authen')) {
            $data = callApiSSO(env('API_GET_SESSION_DOCKER'), $sessionCookie, $secretKey);
            if (isset($data['code']) && Response::HTTP_OK === $data['code']) {
                Cookie::queue('sso-authen', true, 5);

                return $next($request);
            }
            // Auth::logout();
            Session::forget('auth_user');

            return redirect(env('URL_SERVER_SSO') . '/login?redirect_url=' . env('URL_CLIENT_SSO'));
        }

        return $next($request);
    }
}
