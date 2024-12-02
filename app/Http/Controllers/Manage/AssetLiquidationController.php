<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Manage\AssetLiquidationService;

class AssetLiquidationController extends Controller
{
    public function __construct(
        protected AssetLiquidationService $assetLiquidationService,
    ) {

    }

    /**
     * Display a listing of the resource.
     */
    public function getListAssetLiquidation(Request $request)
    {
        $request->validate([
            'name_code'    => 'nullable|string',
        ]);
        try {
            $result = $this->assetLiquidationService->getListAssetLiquidation($request->all());

            return response_success($result);
        } catch (\Exception $e) {

            return response_error();
        }
    }
}
