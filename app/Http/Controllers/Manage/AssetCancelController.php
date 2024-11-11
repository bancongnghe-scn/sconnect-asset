<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssetCancelRequest;
use App\Services\Manage\AssetCancelService;
use Illuminate\Support\Facades\DB;

class AssetCancelController extends Controller
{
    public function __construct(
        protected AssetCancelService $assetCancelService,
    ) {

    }

    /**
     * Display a listing of the resource.
     */
    public function getListAssetCancel(AssetCancelRequest $request)
    {
        $request->validated([
            'name_code'    => 'nullable|string',
        ]);
        try {
            $result = $this->assetCancelService->list($request->all());

            return response_success($result);
        } catch (\Exception $e) {
            DB::error(__FILE__ . __LINE__ . ': ' . $e->getMessage());

            return response_error();
        }
    }
}
