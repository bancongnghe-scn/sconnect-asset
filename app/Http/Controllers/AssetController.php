<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Models\Organization;
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

    public function listUserAsset(): View
    {
        return view('assets.asset.list-user');
    }

    public function getListUserAsset(Request $request): JsonResponse
    {
        try {
            $listUserAsset = $this->assetService->getListUserAsset($request);

            return response_success([
                'listUserAsset' => $listUserAsset,
                'listUnit' => Organization::with('deptType')->where('parent_id', 1)->get(),
                'listAssetType' => AssetType::all(),
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function getListAssetOfUser(Request $request): JsonResponse
    {
        try {
            $listAssetOfUser = $this->assetService->getListAssetOfUser($request->userId);

            return response_success([
                'listAssetOfUser' => $listAssetOfUser,
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function allocateAsset(Request $request): JsonResponse
    {
        try {
            $listAssetOfUser = $this->assetService->allocateAsset($request);

            return response_success([
                'listAssetOfUser' => $listAssetOfUser,
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }
}
