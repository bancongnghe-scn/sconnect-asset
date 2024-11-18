<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssetLostRequest;
use App\Services\Manage\AssetLostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssetLostController extends Controller
{
    public function __construct(
        protected AssetLostService $assetLostService,
    ) {

    }

    /**
     * Display a listing of the resource.
     */
    public function getListAssetLost(AssetLostRequest $request)
    {
        $request->validated([
            'name_code'    => 'nullable|string',
        ]);
        try {
            $result = $this->assetLostService->list($request->all());

            return response_success($result);
        } catch (\Exception $e) {
            Log::error(__FILE__ . __LINE__ . ': ' . $e->getMessage());

            return response_error();
        }
    }

    public function findAssetLost(string $id)
    {
        try {
            $result = $this->assetLostService->findAssetLost($id);

            return response_success($result);
        } catch (\Exception $e) {
            Log::error(__FILE__ . __LINE__ . ': ' . $e->getMessage());

            return response_error();
        }
    }

    public function updateAssetLost(Request $request)
    {
        try {
            $result = $this->assetLostService->updateAssetLost($request->all());

            return response_success($result);
        } catch (\Exception $e) {
            Log::error(__FILE__ . __LINE__ . ': ' . $e->getMessage());

            return response_error();
        }
    }
}
