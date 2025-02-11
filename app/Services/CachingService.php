<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CachingService
{
    public function flushCache($type, $key, $key_tag)
    {
        if ('key' == $type) {
            Cache::forget($key);
        } elseif ('tag' == $type) {
            if (empty($key)) {
                Cache::tags($key_tag)->flush();
            } else {
                Cache::tags($key_tag)->forget($key);
            }
        }

        return true;
    }

    public function addCache($type, $key, $key_tag, $value, $seconds = 1000)
    {
        if (0 === $seconds) {
            if ('key' == $type) {
                Cache::forever($key, $value);
            } elseif ('tag' == $type) {
                Cache::tags($key_tag)->forever($key, $value);
            }
        } else {
            if ('key' == $type) {
                Cache::put($key, $value, $seconds);
            } elseif ('tag' == $type) {
                Cache::tags($key_tag)->put($key, $value, $seconds);
            }
        }

        return true;
    }

    public function getCache(array $input)
    {
        $tags = \Arr::wrap($input['tags'] ?? []);
        $keys = \Arr::wrap($input['keys'] ?? []);

        $cacheTagged = !empty($tags) ? \Cache::tags($tags) : null;

        if (!empty($input['pattern'])) {
            $matchedKeys = $this->getCacheKeyByPattern($input['pattern'], $cacheTagged);

            sort($matchedKeys);

            $keys = array_merge($keys, $matchedKeys);
        }

        $page  = !empty($input['page']) ? (int) $input['page'] : 1;
        $limit = min((int) ($input['limit'] ?? 100), 1000);
        $total = count($keys);

        if (count($keys) > $limit) {
            $offset = ($page - 1) * $limit;

            $keys = array_slice($keys, $offset, $limit);
        }

        $data = $cacheTagged ? $cacheTagged->get($keys) : \Cache::get($keys);

        return [
            'data'       => $data,
            'extra_data' => [
                'total'     => $total,
                'limit'     => $limit,
                'page'      => $page,
                'last_page' => max((int) ceil($total / $limit), 1),
            ],
        ];
    }

    /**
     * @param TaggedCache $cacheTagged
     * @param bool $withPrefix get fully qualified key keys (with prefix) or without prefix ?
     *
     * @return array<string>
     */
    public function getCacheKeyByPattern(string $pattern, $cacheTagged = null, bool $withPrefix = false)
    {
        $result = [];

        try {
            /** @var \Predis\Client&\Redis $redisClient */
            $redisClient = \Cache::getStore()->connection()->client();

            if ($cacheTagged) {
                // Illuminate\Cache\TaggedCache::taggedItemKey
                $prefix = sha1($cacheTagged->getTags()->getNamespace()).':';
            } else {
                $prefix = \Cache::getPrefix();
            }

            // Get cache keys
            $matchedKeys = $redisClient->keys("*$prefix".$pattern);

            if (!empty($matchedKeys)) {
                // Remove cache prefix
                $result = $withPrefix ? $matchedKeys : array_map(fn ($key) => \Str::after($key, $prefix), $matchedKeys);
            }
        } catch (\Throwable $e) {
            \Log::error($e);
        }

        return $result;
    }
}
