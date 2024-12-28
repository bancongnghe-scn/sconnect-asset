<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Services\AssetService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssetController extends Controller
{
    public function __construct(private readonly AssetService $assetService) {}

    public function listAsset(): View
    {
        return view('assets.asset.list');
    }

    public function getListAsset(Request $request): JsonResponse
    {
        try {
            $listAsset = $this->assetService->getListAsset($request);

            return response_success([
                'listAsset' => $listAsset,
                'listAssetType' => AssetType::all(),
                'listStatus' => config('constant.status'),
                'listLocation' => config('constant.location')
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }
}
