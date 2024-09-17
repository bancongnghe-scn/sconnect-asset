<?php

namespace App\Http\Middleware;

use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    public function handle($request, \Closure $next, ...$guards): mixed
    {
        if (config('sso.login-local')) {
            $this->authenticate($request, $guards);
            return $next($request);
        }
        $secretKey = config('sso.sso-secret-key');
        $token = @$_GET['token'];
        $sig = @$_GET['sig'];
        $sessionCookie = @$_COOKIE['scn_session'];

        if ($token && $sig) {
            if (!hash_equals(hash_hmac('sha256', $token, $secretKey), $sig)) {
                $url = config('sso.logout-sso');
                $this->callApiWithSession($url, $sessionCookie, $secretKey);
                Auth::logout();
                return redirect()->route('login');
            }
            $data = $this->callApiWithSession(config('sso.get-session-sso'), $sessionCookie, $secretKey);
            // Kiểm tra phản hồi từ API đầu tiên
            if (isset($data['code']) && $data['code'] === Response::HTTP_OK) {
                $user = @$data['data']['user'];
                resolve(UserService::class)->checkUserExistLogin($user);
                Auth::loginUsingId($user['id']);
                $time = Carbon::now()->format('Y-m');
                $userId = auth()->user()->id;
                return redirect()->route('home.tkp-side-bar', ['month' => $time, 'id' => $userId]);
            }
            $url = config('sso.logout-sso');
            $this->callApiWithSession($url, $sessionCookie, $secretKey);
            Auth::logout();
            return redirect()->route('login');
        }
        return $this->storeSession($sessionCookie, $secretKey, $next($request));
    }

    private function storeSession($sessionCookie, $secretKey, $next)
    {
        //Gọi API get-session
        $data = $this->callApiWithSession(config('sso.get-session-sso'), $sessionCookie, $secretKey);

        // Kiểm tra phản hồi từ API đầu tiên
        if (isset($data['code']) && $data['code'] === Response::HTTP_OK) {
            $user = @$data['data']['user'];
            resolve(UserService::class)->checkUserExistLogin($user);
            if (!Auth::check()) {
                Auth::loginUsingId($user['id']);
            }
            return $next;
        }
        $url = config('sso.logout-sso');
        $this->callApiWithSession($url, $sessionCookie, $secretKey);
        Auth::logout();
        return redirect()->route('login');
    }

    private function callApiWithSession($url, $sessionCookie, $secretKey)
    {
        $ch = curl_init($url);
        // Cấu hình cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIE, 'scn_session=' . $sessionCookie);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Origin:" . config('app.url'),
            "Site-Access:" . hash_hmac('sha256', config('app.url'), $secretKey),
        ]);
        // Thực thi cURL và lấy phản hồi
        $response = curl_exec($ch);
        curl_close($ch);

        // Xử lý phản hồi từ server
        return json_decode($response, true);
    }

    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
