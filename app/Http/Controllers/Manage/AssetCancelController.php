<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Manage\AssetCancelService;

class AssetCancelController extends Controller
{
    public function __construct(
        protected AssetCancelService $assetCancelService,
    ) {

    }

    /**
     * Display a listing of the resource.
     */
    public function getListAssetCancel(Request $request)
    {
        $request->validate([
            'name_code'    => 'nullable|string',
        ]);
        try {
            $result = $this->assetCancelService->getListAssetCancel($request->all());

            return response_success($result);
        } catch (\Exception $e) {
            return response_error();
        }
    }
}
