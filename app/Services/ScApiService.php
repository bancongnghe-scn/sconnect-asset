<?php

namespace App\Services;

use App\Support\Constants\SOfficeConstant;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    public static function getOrganizationByIds($ids)
    {
        $ids = Arr::wrap($ids);

        $result = collect();

        foreach ($ids as $idx => $id) {
            $cacheKey = config('cache_keys.keys.organization') . '_' . $id;

            $organization = Cache::tags(config('cache_keys.tags.organization'))->get($cacheKey);
            if ($organization) {
                $result->put($id, $organization);
                unset($ids[$idx]);
            }
        }
        if (!empty($ids)) {
            $response = self::getOrganizationsApi($ids, SOfficeConstant::ORGANIZATION_STATUS_ACTIVE);

            if (!is_null($response) && $response['success']) {
                foreach ($response['data'] as $organization) {
                    $result->put($organization['id'], $organization);

                    $cacheKey = config('cache_keys.keys.organization') . '_' . $organization['id'];
                    Cache::tags(config('cache_keys.tags.organization'))->put($cacheKey, $organization, config('cache_keys.ttl.month'));
                }
            }
        }

        return $result;
    }

    public static function getAllOrganizationParent()
    {
        $response = self::getOrganizationsApi(status: SOfficeConstant::ORGANIZATION_STATUS_ACTIVE);
        dd($response);

        return Cache::tags(config('cache_keys.tags.organization'))
            ->remember(config('cache_keys.keys.organization_all'), now()->addMonths(2), function () {
                $response = self::getOrganizationsApi(status: SOfficeConstant::ORGANIZATION_STATUS_ACTIVE);
                if (!is_null($response) && $response['success']) {
                    foreach ($response['data'] as $organization) {
                        $cacheKey = config('cache_keys.keys.organization') . '_' . $organization['id'];
                        Cache::tags(config('cache_keys.tags.organization'))->put($cacheKey, $organization, config('cache_keys.ttl.month'));
                    }

                    return $response['data'];
                }

                return [];
            });
    }

    public static function getOrganizationsApi($ids = [], $status = [], $parentId = SOfficeConstant::ORGANIZATION_PARENT_MAIN)
    {
        $host     = config('services.sc-api.domain');
        $endpoint = '/api/organization/getOrganizations';
        $url      = $host . $endpoint;

        $params = [];

        if (!empty($ids)) {
            $params['ids'] = Arr::wrap($ids);
        }

        if (!empty($status)) {
            $params['status'] = Arr::wrap($status);
        }

        if (!empty($parentId)) {
            $params['parent_id'] = $parentId;
        }

        try {
            $response = Http::withToken('123')
                ->timeout(static::$TIMEOUT_15)
                ->get($url, $params);
            dd($response);

            return $response->json();
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());

            return null;
        }
    }

    public static function getAllJob()
    {
        return Cache::tags(config('cache_keys.tags.job_title'))
            ->remember(config('cache_keys.keys.job_title_all'), now()->addMonths(2), function () {
                $response = self::getJobsApi();
                if (!is_null($response) && $response['success']) {
                    foreach ($response['data'] as $job) {
                        $cacheKey = config('cache_keys.keys.job_title') . '_' . $job['id'];
                        Cache::tags(config('cache_keys.tags.job_title'))->put($cacheKey, $job, config('cache_keys.ttl.month'));
                    }

                    return $response['data'];
                }

                return [];
            });
    }

    public static function getJobsApi($ids = [])
    {
        $host     = config('services.sc-api.domain');
        $endpoint = '/api/job/getJobs';
        $url      = $host . $endpoint;

        $params = [];

        if (!empty($ids)) {
            $params['id'] = Arr::wrap($ids);
        }

        try {
            $response = Http::withToken('123')
                ->timeout(static::$TIMEOUT_15)
                ->get($url, $params);

            return $response->json();
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());

            return null;
        }
    }
}
