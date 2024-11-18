<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanLiquidationRequest;
use App\Services\Manage\PlanLiquidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlanLiquidationController extends Controller
{
    public function __construct(
        protected PlanLiquidationService $planLiquidationService,
    ) {

    }

    public function create(PlanLiquidationRequest $request)
    {
        $request->validated([
            'name'      => 'required|string',
            'code'      => 'required|string',
            'note'      => 'nullable|string',
        ]);
        try {
            $result = $this->planLiquidationService->createPlanLiquidatoin($request->all());

            return response_success($result);
        } catch (\Exception $e) {
            Log::error(__FILE__ . __LINE__ . ': ' . $e->getMessage());

            return response_error();
        }
    }

    public function get(PlanLiquidationRequest $request)
    {
        try {
            $listPlanLiquidation = $this->planLiquidationService->list($request->all());

            return response_success($listPlanLiquidation);
        } catch (\Exception $e) {
            Log::error(__FILE__ . __LINE__ . ': ' . $e->getMessage());

            return response_error();
        }
    }

    public function detail(string $id)
    {
        try {
            $result = $this->planLiquidationService->findPlanLiquidation($id);

            return response_success($result);
        } catch (\Exception $e) {
            Log::error(__FILE__ . __LINE__ . ': ' . $e->getMessage());

            return response_error();
        }
    }

    public function updateAssetToPlan(Request $request)
    {
        try {
            $result = $this->planLiquidationService->updateAssetToPlanLiquidation($request);

            return response_success($result);
        } catch (\Exception $e) {
            Log::error(__FILE__ . __LINE__ . ': ' . $e->getMessage());

            return response_error();
        }
    }

    public function deleteAssetFromPlan($planMaintainAssetId)
    {
        try {
            $result = $this->planLiquidationService->deleteAssetFromPlanLiquidation($planMaintainAssetId);

            return response_success($result);
        } catch (\Exception $e) {
            Log::error(__FILE__ . __LINE__ . ': ' . $e->getMessage());

            return response_error();
        }
    }

    public function deleteMultiPlan(Request $request)
    {
        try {
            $result = $this->planLiquidationService->deleteMultiPlan($request->get('plan_ids'));

            return response_success($result);
        } catch (\Exception $e) {
            Log::error(__FILE__ . __LINE__ . ': ' . $e->getMessage());

            return response_error();
        }
    }

    public function update($id, Request $request)
    {
        try {
            $result = $this->planLiquidationService->updatePlan($id, $request->all());

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Exception $e) {
            Log::error(__FILE__ . __LINE__ . ': ' . $e->getMessage());

            return response_error();
        }
    }

    public function changeStatusAssetOfPlan(Request $request)
    {
        try {
            $plan_maintain_asset_id = $request->get('id');
            $dataUpdate             = $request->except('id');

            $result = $this->planLiquidationService->updatePlanMaintainAsset($plan_maintain_asset_id, $dataUpdate);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Exception $e) {
            Log::error(__FILE__ . __LINE__ . ': ' . $e->getMessage());

            return response_error();
        }
    }

    public function changeStatusMultiAssetOfPlan(Request $request)
    {
        try {
            $plan_maintain_asset_ids = $request->get('ids');
            $dataUpdate              = $request->except('ids');

            $result = $this->planLiquidationService->updateMultiPlanMaintainAsset($plan_maintain_asset_ids, $dataUpdate);
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Exception $e) {
            Log::error(__FILE__ . __LINE__ . ': ' . $e->getMessage());

            return response_error();
        }
    }
}
