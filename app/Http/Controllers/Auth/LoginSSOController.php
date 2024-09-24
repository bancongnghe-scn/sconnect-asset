<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class LoginSSOController extends Controller
{
    public function login()
    {
        $redirectUrl = config('sso.url_login');
        return redirect($redirectUrl);
    }
    public function loginSSO(): Redirector|Application|RedirectResponse
    {
        Auth::loginUsingId(Auth::id());
        return redirect()->route('home');

        $secretKey = config('sso.sso-secret-key');
        $token = @$_GET['token'];
        $sig = @$_GET['sig'];
        $sessionCookie = @$_COOKIE['scn_session'];
        if ($token && $sig) {
            if (!hash_equals(hash_hmac('sha256', $token, $secretKey), $sig)) {
                $url = config('sso.logout-sso');
                $this->callApiWithSession($url, $sessionCookie, $secretKey);
                return redirect()->route('login');
            }
            $data = $this->callApiWithSession(config('sso.get-session-sso'), $sessionCookie, $secretKey);
            if (isset($data['code']) && $data['code'] === Response::HTTP_OK) {
                $user = @$data['data']['user'];
                $exists = User::query()->where('email', $user['email'])->where('status', User::STATUS_ACTIVE)->exists();
                if($exists){
                    Auth::loginUsingId($user['id']);
                    return redirect()->route('home');
                }
            }
            $url = config('sso.logout-sso');
            $this->callApiWithSession($url, $sessionCookie, $secretKey);
            Auth::logout();
            return redirect()->route('login');
        }
        return redirect()->route('login');
    }

    private function callApiWithSession($url, $sessionCookie, $secretKey)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIE, 'scn_session=' . $sessionCookie);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Origin:" . config('app.url'),
            "Site-Access:" . hash_hmac('sha256', config('app.url'), $secretKey),
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    public function logoutSSO(Request $request)
    {
        $sessionCookie = @$_COOKIE['scn_session'];
        $url = config('sso.logout-sso');
        $ch = curl_init($url);

        // Cấu hình cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIE, 'scn_session=' . $sessionCookie);

        // Thực thi cURL và lấy phản hồi
        $response = curl_exec($ch);
        curl_close($ch);

        // Xử lý phản hồi từ server
        $data = json_decode($response, true);
        if(@$data['code'] == 200){
            $this->performLogout($request);
            return redirect()->route('login');
        }
        return redirect()->back();
    }
}
