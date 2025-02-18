<?php

namespace App\Http\Controllers;

use App\Exports\ListAssetExport;
use App\Models\AssetType;
use App\Models\Org;
use App\Models\Supplier;
use App\Models\User;
use App\Services\ListAssetService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

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
                'listSupplier'  => Supplier::all(),
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
            $listAssetOfObj = $this->assetService->getListAssetOfUser($request->userId);

            return response_success([
                'listAssetOfObj' => $listAssetOfObj,
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function allocateAsset(Request $request): JsonResponse
    {
        try {
            $listAssetOfObj = $this->assetService->allocateAsset($request);
            $this->assetService->exportReportAllocation();

            return response_success([
                'listAssetOfObj' => $listAssetOfObj,
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function recoveryAsset(Request $request): JsonResponse
    {
        try {
            $listAssetOfObj = $this->assetService->recoveryAsset($request);

            return response_success([
                'listAssetOfObj' => $listAssetOfObj,
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
            $listAssetOfObj = $this->assetService->getListAssetOfOrg($request->orgId);

            return response_success([
                'listAssetOfObj' => $listAssetOfObj,
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function allocateAssetOrg(Request $request): JsonResponse
    {
        try {
            $listAssetOfObj = $this->assetService->allocateAssetOrg($request);

            return response_success([
                'listAssetOfObj' => $listAssetOfObj,
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function recoveryAssetOrg(Request $request): JsonResponse
    {
        try {
            $listAssetOfObj = $this->assetService->recoveryAssetOrg($request);

            return response_success([
                'listAssetOfObj' => $listAssetOfObj,
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

    public function getListHistory(Request $request)
    {
        try {
            $listHistory = $this->assetService->getListHistory($request);

            return response_success([
                'listHistory' => $listHistory,
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function getListLog(Request $request)
    {
        try {
            $listLog = $this->assetService->getListLog($request);

            return response_success([
                'listLog' => $listLog,
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function getListOrg()
    {
        try {
            return response_success([
                'listOrg' => Org::with(['deptType', 'manager'])->where('parent_id', 1)->get(),
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function getUser(): JsonResponse
    {
        try {
            return response_success([
                'listUser' => User::where('status', 1)->limit(2000)->get(),
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function rotationAsset(Request $request): JsonResponse
    {
        try {
            $listAssetOfObj = $this->assetService->rotationAsset($request);

            return response_success([
                'listAssetOfObj' => $listAssetOfObj,
            ]);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function liquidationAsset(Request $request): JsonResponse
    {
        try {
            $this->assetService->liquidationAsset($request);

            return response_success();
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function cancelAsset(Request $request): JsonResponse
    {
        try {
            $this->assetService->cancelAsset($request);

            return response_success();
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function brokenAsset(Request $request): JsonResponse
    {
        try {
            $this->assetService->brokenAsset($request);

            return response_success();
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function lostAsset(Request $request): JsonResponse
    {
        try {
            $this->assetService->lostAsset($request);

            return response_success();
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function updateAsset(Request $request): JsonResponse
    {
        try {
            $this->assetService->updateAsset($request);

            return response_success();
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function exportListAsset()
    {
        return Excel::download(new ListAssetExport(), 'danh_sach_tai_san.xlsx');
    }

    public function getAssetInfo($id)
    {
        try {
            $result = $this->assetService->getAssetInfo($id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success($result['data']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }
}
