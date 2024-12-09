<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Services\Inventory\AssetDamagedService;
use Illuminate\Http\Request;

class AssetDamagedController extends Controller
{
    public function __construct(
        protected AssetDamagedService $assetDamagedService,
    ) {

    }

    /**
     * Display a listing of the resource.
     */
    public function getListAssetDamaged(Request $request)
    {
        try {
            $result = $this->assetDamagedService->list($request->all());

            return response_success($result);
        } catch (\Exception $e) {
            return response_error();
        }
    }

    public function updateMultiAssetLiquidation(Request $request)
    {
        $request->validate([
            '*.id'                     => 'required|integer',
            '*.price_liquidation'      => 'nullable',
            '*.date'                   => 'nullable|date|date_format:Y-m-d',
            '*.reason'                 => 'nullable',
        ]);

        try {
            $result = $this->assetDamagedService->updateMultiAsset($request->all(), Asset::STATUS_PROPOSAL_LIQUIDATION);

            return response_success($result);
        } catch (\Exception $e) {
            return response_error();
        }
    }

    public function updateMultiAssetCancel(Request $request)
    {
        $request->validate([
            '*.id'                     => 'required|integer',
            '*.date'                   => 'nullable|date|date_format:Y-m-d',
            '*.reason'                 => 'nullable',
        ]);

        try {
            $result = $this->assetDamagedService->updateMultiAsset($request->all(), Asset::STATUS_CANCEL);

            return response_success($result);
        } catch (\Exception $e) {
            return response_error();
        }
    }
}
