<?php

namespace App\Http\Controllers;

use App\Services\AssetTypeGroupService;
use Illuminate\Http\Request;

class AssetTypeGroupController extends Controller
{
    public function __construct(
        protected AssetTypeGroupService $assetTypeGroupService
    )
    {

    }
    public function getListAssetTypeGroup(Request $request) {
        try {
            $result = $this->assetTypeGroupService->getListAssetTypeGroup($request->all());
            if ($result['success']) {
                return response_success($result['data']);
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
