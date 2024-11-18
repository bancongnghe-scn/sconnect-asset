<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssetLostRequest;
use App\Services\Manage\AssetLiquidationService;
use Illuminate\Support\Facades\Log;

class AssetLiquidationController extends Controller
{
    public function __construct(
        protected AssetLiquidationService $assetLiquidationService,
    ) {

    }

    /**
     * Display a listing of the resource.
     */
    public function getListAssetLiquidation(AssetLostRequest $request)
    {
        $request->validated([
            'name_code'    => 'nullable|string',
        ]);
        try {
            $result = $this->assetLiquidationService->list($request->all());

            return response_success($result);
        } catch (\Exception $e) {
            Log::error(__FILE__ . __LINE__ . ': ' . $e->getMessage());

            return response_error();
        }
    }
}
