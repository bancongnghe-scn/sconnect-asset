<?php

use App\Support\AppErrorCode;

if (!function_exists('response_success')) {
    /**
     * @return Illuminate\Http\JsonResponse
     */
    function response_success(array $data = [], ?string $message = null, array $extraData = [])
    {
        $responseData = [
            'success' => true,
            'message' => !empty($message) ? $message : 'Thành công',
            'data'    => $data ?? new stdClass(),
        ];

        $responseData = array_merge($responseData, $extraData);

        return response()->json($responseData);
    }
}

if (!function_exists('response_error')) {
    /**
     * @param null|mixed $message
     *
     * @return Illuminate\Http\JsonResponse
     */
    function response_error(int $errorCode = AppErrorCode::CODE_1000, ?string $message = null, array $errors = [], array $extraData = [], int $statusCode = 200, array $data = [])
    {
        if ($message instanceof Throwable && !isDev()) {
            $message = null;
        }

        $body = [
            'success'  => false,
            'code'     => $errorCode,
            'errors'   => empty($errors) ? $extraData['errors'] ?? [] : $errors,
            'message'  => !empty($message) ? $message : __('error.'.$errorCode, $extraData),
        ];

        if (!empty($data)) {
            $body['data']  = $data;
        }

        $body = array_merge($body, Illuminate\Support\Arr::except($extraData, ['errors']));

        return response()->json($body, $statusCode);
    }
}
