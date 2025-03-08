<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAllocationRateRequest;
use App\Http\Requests\UpdateAllocationRateRequest;
use App\Models\AllocationRate;
use App\Services\AllocationRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllocationRateController extends Controller
{
    public function __construct(
        protected AllocationRateService $allocationRateService,
    ) {

    }

    public function getListAllocationRate(Request $request)
    {
        $request->validate([
            'organization_id' => 'nullable|integer',
            'position_id'     => 'nullable|integer',
            'type'            => 'required|integer',
            'page'            => 'nullable|integer',
            'limit'           => 'nullable|integer',
        ]);

        Auth::user()->canPer('allocation.view');
        try {
            $result = $this->allocationRateService->getListAllocationRate($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function createAllocationRate(CreateAllocationRateRequest $request)
    {
        Auth::user()->canPer('allocation.create');
        try {
            $result = $this->allocationRateService->createAllocationRate($request->all());
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function updateAllocationRate(UpdateAllocationRateRequest $request)
    {
        Auth::user()->canPer('allocation.create');
        try {
            $result = $this->allocationRateService->updateAllocationRate($request->all());
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code'], extraData: $result['extra_data']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function deleteAllocationRate(Request $request)
    {
        $request->validate([
            'type'              => 'required|integer',
            'organization_id'   => 'required|array',
            'organization_id.*' => 'integer',
            'position_id'       => 'required_if:type,'.AllocationRate::TYPE_POSITION.'|array',
            'position_id.*'     => 'integer',
        ]);
        Auth::user()->canPer('allocation.delete');
        try {
            $result = $this->allocationRateService->deleteAllocationRate($request->all());
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * Lấy giá tri cua tai san theo dinh muc cap phat cua don vi
     */
    public function getAllocationRateOfOrganization(Request $request)
    {
        $request->validate([
            'organization_id'   => 'required|integer',
            'asset_type_id'     => 'required|integer',
            'type'              => 'required|integer',
            'position_id'       => 'nullable|integer',
        ]);

        Auth::user()->canPer('allocation.view');
        try {
            $result = $this->allocationRateService->getAllocationRateOfOrganization($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {

            report($exception);

            return response_error();
        }
    }
}
