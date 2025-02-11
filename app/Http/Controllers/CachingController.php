<?php

namespace App\Http\Controllers;

use App\Services\CachingService;
use Illuminate\Http\Request;

class CachingController extends Controller
{
    protected $cachingService;

    public function __construct(CachingService $cachingService)
    {
        $this->cachingService = $cachingService;
    }

    /**
     * @OA\Post(
     *     security={{"bearer_token":{}}},
     *     path="/cache/flush",
     *     tags={"Cache"},
     *     summary="API xóa cache theo key hoặc theo tag",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                  required={"type"},
     *
     *                  @OA\Property(property="type", type="string", description="Xóa cache theo key hoặc theo tag"),
     *                  @OA\Property(property="key", type="string", description="Key cache, có thể flush tag theo key này"),
     *                  @OA\Property(property="key_tag", type="string", description="Key tag, sử dụng nếu type là tag")
     *             )
     *
     *         )
     *
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Successful",
     *
     *          @OA\JsonContent(ref="#/components/schemas/Response")
     *       )
     *     )
     */
    public function flushCache(Request $request)
    {
        $request->validate([
            'type'      => 'required|in:key,tag',
            'key'       => 'required_if:type,key|string',
            'key_tag'   => 'required_if:type,tag|string',
        ]);

        //        Auth::user()->canPer('cache.flush');

        try {
            $this->cachingService->flushCache(
                $request->input('type'),
                $request->input('key'),
                $request->input('key_tag'),
            );

            return response_success();
        } catch (\Throwable $e) {
            report($e);

            return response_error();
        }
    }

    /**
     * @OA\Post(
     *     security={{"bearer_token":{}}},
     *     path="/cache/add",
     *     tags={"Cache"},
     *     summary="API thêm cache theo key hoặc theo tag",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                  required={"type","key","value"},
     *
     *             @OA\Property(property="type", type="string", description="Thêm cache theo key hoặc theo tag"),
     *             @OA\Property(property="key", type="string", description="Key cache (dùng cho cả tag)"),
     *             @OA\Property(property="key_tag", type="string", description="Key tag, sử dụng nếu type là tag"),
     *             @OA\Property(property="value", type="string", description="Giá trị cache"),
     *             @OA\Property(property="seconds", type="int", description="Số giây lưu cache (nếu giá trị bằng 0 sẽ set forever cho cache)")
     *             )
     *
     *         )
     *
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Successful",
     *
     *          @OA\JsonContent(ref="#/components/schemas/Response")
     *       )
     *     )
     */
    public function addCache(Request $request)
    {
        $request->validate([
            'type'      => 'required|in:key,tag',
            'key'       => 'required|string',
            'key_tag'   => 'required_if:type,tag|string',
            'value'     => 'required|string',
            'seconds'   => 'int',
        ]);

        //        Auth::user()->canPer('cache.add');

        try {
            $this->cachingService->addCache(
                $request->input('type'),
                $request->input('key'),
                $request->input('key_tag'),
                $request->input('value'),
                $request->input('seconds'),
            );

            return response_success();
        } catch (\Throwable $e) {
            report($e);

            return response_error();
        }
    }

    /**
     * @OA\Get(
     *     security={{"bearer_token":{}}},
     *     path="/cache/get",
     *     tags={"Cache"},
     *     summary="API lấy giá trị cache theo cache tags, keys, key pattern",
     *
     *      @OA\Parameter(
     *          name="tags[]",
     *          in="query",
     *          description="Cache tags",
     *          explode=true,
     *
     *          @OA\Schema(type="array", @OA\Items(type="string")),
     *      ),
     *
     *      @OA\Parameter(
     *          name="keys[]",
     *          in="query",
     *          description="Cache keys, required if pattern not set",
     *          explode=true,
     *
     *          @OA\Schema(type="array", @OA\Items(type="string")),
     *      ),
     *
     *     @OA\Parameter(
     *          description="Cache key pattern, required if cache keys not set",
     *          in="query",
     *          name="pattern",
     *
     *          @OA\Schema(type="string"),
     *      ),
     *
     *     @OA\Parameter(
     *         description="Cache key (old)",
     *         in="query",
     *         name="key",
     *
     *         @OA\Schema(type="string"),
     *     ),
     *
     *     @OA\Parameter(
     *         description="Cache tag (old)",
     *         in="query",
     *         name="key_tag",
     *
     *         @OA\Schema(type="string"),
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Successful",
     *
     *          @OA\JsonContent(ref="#/components/schemas/Response")
     *       )
     *     )
     */
    public function getCache(Request $request)
    {
        // Support old params
        if ($request->has('key_tag')) {
            $request->mergeIfMissing(['tags' => \Arr::wrap($request->input('key_tag'))]);
        }
        if ($request->has('key')) {
            $request->mergeIfMissing(['keys' => \Arr::wrap($request->input('key'))]);
        }

        $request->validate([
            'tags'    => 'array',
            'tags.*'  => 'string',
            'keys'    => 'required_without:pattern|array',
            'keys.*'  => 'string',
            'pattern' => 'required_without:keys|string',
            'limit'   => 'int',
            'page'    => 'int',
        ]);

        //        Auth::user()->canPer('cache.get');

        try {
            $result = $this->cachingService->getCache($request->input());

            return response_success($result['data'] ?? [], extraData: $result['extra_data'] ?? []);
        } catch (\Throwable $e) {
            report($e);

            return response_error();
        }
    }
}
