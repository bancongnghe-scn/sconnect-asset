<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Manage\PlanLiquidationService;

class PlanLiquidationController extends Controller
{
    public function __construct(
        protected PlanLiquidationService $planLiquidationService,
    ) {

    }

    public function createPlan(Request $request)
    {
        $request->validate([
            'name'      => 'required|string',
            'code'      => 'required|string',
            'note'      => 'nullable|string',
        ]);
        try {
            $result = $this->planLiquidationService->createPlanLiquidatoin($request->all());

            return response_success($result);
        } catch (\Exception $e) {
            return response_error();
        }
    }

    public function getPlan(Request $request)
    {
        $request->validate([
            'name_code'         => 'nullable|string',
            'created_at'        => 'nullable|string',
            'status'            => 'nullable|integer',
        ]);
        try {
            $result = $this->planLiquidationService->listPlanLiquidation($request->all());

            return response_success($result);
        } catch (\Exception $e) {
            return response_error();
        }
    }

    public function detail(string $id)
    {
        try {
            $result = $this->planLiquidationService->findPlanLiquidation($id);

            return response_success($result);
        } catch (\Exception $e) {
            return response_error();
        }
    }

    public function updateAssetToPlan(Request $request)
    {
        $request->validate([
            'plan_id'       => 'nullable|integer',
            'asset_ids'     => 'nullable|array',
        ]);
        try {
            $result = $this->planLiquidationService->updateAssetToPlanLiquidation($request->all());

            return response_success($result);
        } catch (\Exception $e) {
            return response_error();
        }
    }

    public function deleteAssetFromPlan($planMaintainAssetId)
    {
        try {
            $result = $this->planLiquidationService->deleteAssetFromPlanLiquidation($planMaintainAssetId);

            return response_success($result);
        } catch (\Exception $e) {
            return response_error();
        }
    }

    public function deleteMultiPlan(Request $request)
    {
        try {
            $result = $this->planLiquidationService->deleteMultiPlan($request->get('plan_ids'));

            return response_success($result);
        } catch (\Exception $e) {
            return response_error();
        }
    }

    public function updatePlan($id, Request $request)
    {
        try {
            $request->validate([
                'status'    => 'required|integer',
            ]);

            $result = $this->planLiquidationService->updatePlan($id, $request->integer('status'));

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Exception $e) {
            return response_error();
        }
    }

    public function changeStatusAssetOfPlan(Request $request)
    {
        try {
            $request->validate([
                'id'        => 'required|integer',
                'status'    => 'required|integer',
            ]);

            $result = $this->planLiquidationService->updatePlanMaintainAsset($request->get('id'), $request->integer('status'));

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Exception $e) {
            return response_error();
        }
    }

    public function changeStatusMultiAssetOfPlan(Request $request)
    {
        $request->validate([
            'ids'       => 'required|array',
            'status'    => 'required|integer',
        ]);
        try {
            $result = $this->planLiquidationService->updateMultiPlanMaintainAsset($request->get('ids'), $request->integer('status'));
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Exception $e) {
            return response_error();
        }
    }
}