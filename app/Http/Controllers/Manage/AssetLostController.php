<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Manage\AssetLostService;

class AssetLostController extends Controller
{
    public function __construct(
        protected AssetLostService $assetLostService,
    ) {

    }

    /**
     * Display a listing of the resource.
     */
    public function getListAssetLost(Request $request)
    {
        try {
            $result = $this->assetLostService->getListAssetLost($request->all());

            return response_success($result);
        } catch (\Exception $e) {
            return response_error();
        }
    }

    public function findAssetLost(string $id)
    {
        try {
            $result = $this->assetLostService->findAssetLost($id);

            return response_success($result);
        } catch (\Exception $e) {
            return response_error();
        }
    }

    public function updateAssetLost(Request $request)
    {
        $request->validate([
            'update_status_assets'                      => 'nullable|array',
            'update_status_assets.*.id'                 => 'required|integer',
            'update_status_assets.*.status'             => 'required|integer|max:200',
        ]);

        try {
            $result = $this->assetLostService->updateAssetLost($request->update_status_assets);

            return response_success($result);
        } catch (\Exception $e) {
            return response_error();
        }
    }
}
