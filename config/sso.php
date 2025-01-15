<?php

return [
    'url_login'            => env('URL_SERVER_SSO', 'https://sso.sconnect.com.vn') . '/login',
    'sso-secret-key'       => '012345678',
    'login-local'          => env('LOGIN_LOCAL', false),
    'login-sso-server-url' => env('URL_SERVER_SSO', 'https://sso.sconnect.com.vn'),
    'logout-sso'           => env('SSO_LOGOUT', env('URL_SERVER_SSO') . '/api/log-out'),
    'get-session-sso'      => env('SSO_GET_SESSION', env('URL_SERVER_SSO') . '/api/get-session'),
    'get-all-user'         => env('SSO_GET_ALL_USER', env('URL_SERVER_SSO') . '/api/user-list'),
];
