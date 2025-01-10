<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Models\Org;
use App\Models\User;
use App\Services\ListAssetService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ListAssetController extends Controller
{
    public function __construct(private readonly ListAssetService $assetService)
    {
    }

    public function listAsset(): View
    {
        return view('assets.asset.list');
    }

    public function getListAsset(Request $request): JsonResponse
    {
        try {
            $listAsset = $this->assetService->getListAsset($request);

            return response_success([
                'listAsset'     => $listAsset,
                'listAssetType' => AssetType::all(),
                'listStatus'    => config('constant.status'),
                'listLocation'  => config('constant.location'),
                'listOrg'       => Org::with('deptType')->where('parent_id', 1)->get(),
                'listUser'      => User::where('status', 1)->limit(2000)->get(),
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
                'listUnit'      => Org::with('deptType')->where('parent_id', 1)->get(),
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

    public function recoveryAsset(Request $request): JsonResponse
    {
        try {
            $listAssetOfUser = $this->assetService->recoveryAsset($request);

            return response_success([
                'listAssetOfUser' => $listAssetOfUser,
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function listOrgAsset(): View
    {
        return view('assets.asset.list-org');
    }

    public function getListOrgAsset(Request $request): JsonResponse
    {
        try {
            $listOrg = $this->assetService->getListOrgAsset($request);

            return response_success([
                'listOrg'       => $listOrg,
                'listUnit'      => Org::with('deptType')->where('parent_id', 1)->get(),
                'listAssetType' => AssetType::all(),
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function getListAssetOfOrg(Request $request): JsonResponse
    {
        try {
            $listAssetOfOrg = $this->assetService->getListAssetOfOrg($request->orgId);

            return response_success([
                'listAssetOfOrg' => $listAssetOfOrg,
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function allocateAssetOrg(Request $request): JsonResponse
    {
        try {
            $listAssetOfOrg = $this->assetService->allocateAssetOrg($request);

            return response_success([
                'listAssetOfOrg' => $listAssetOfOrg,
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function recoveryAssetOrg(Request $request): JsonResponse
    {
        try {
            $listAssetOfUser = $this->assetService->recoveryAssetOrg($request);

            return response_success([
                'listAssetOfOrg' => $listAssetOfUser,
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function getUserByUnit(Request $request): JsonResponse
    {
        try {
            $listUser = $this->assetService->getUserByUnit($request);

            return response_success([
                'listUser' => $listUser,
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }
}
