<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ScApiService
{
    /**
     * The default number of seconds to wait before timing out.
     */
    public static int $TIMEOUT_15 = 15;

    /**
     * The default number of seconds to wait before timing out for long request like upload file, sync data.
     */
    public static int $TIMEOUT_30 = 30;

    /**
     * The number of times to try the request.
     */
    public static int $TRIES = 2;

    /**
     * The number of milliseconds to wait between retries.
     */
    public static int $RETRY_DELAY = 100;

    /**
     * Throw an exception when request fail.
     */
    public static bool $THROW = true;

    public static function graphQl($query)
    {
        $host     = config('services.sc-api.domain');
        $endpoint = '/graphql';
        $url      = $host . $endpoint;

        try {
            $data    = [
                'query' => $query,
            ];

            $response = Http::withToken('123')
                ->timeout(static::$TIMEOUT_15)
                ->asJson()
                ->post($url, $data);

            return $response->json();
        } catch (\Throwable $e) {
            return null;
        }
    }
}
