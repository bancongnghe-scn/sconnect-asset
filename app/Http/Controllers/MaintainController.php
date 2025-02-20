<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePlanMaintainRequest;
use App\Http\Requests\UpdatePlanMaintainRequest;
use App\Models\PlanMaintainAsset;
use App\Services\MaintainService;
use Illuminate\Http\Request;

class MaintainController extends Controller
{
    public function __construct(
        protected MaintainService $maintainService,
    ) {

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * Lay danh sach tai san can bao duong
     */
    public function getAssetNeedMaintain(Request $request)
    {
        $request->validate([
            'name_code'           => 'nullable|string',
            'next_maintain_start' => 'nullable|date|date_format:Y-m-d',
            'next_maintain_end'   => 'nullable|date|date_format:Y-m-d',
            'location'            => 'nullable|integer',
            'status'              => 'nullable|integer',
            'organization_ids'    => 'nullable|array',
            'organization_ids.*'  => 'integer',
            'page'                => 'nullable|integer',
            'limit'               => 'nullable|integer',
        ]);

        try {
            $result = $this->maintainService->getAssetNeedMaintain($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * Lay danh sach tai san can bao duong theo thang
     */
    public function getAssetNeedMaintainWithMonth(Request $request)
    {
        $request->validate([
            'time' => 'required|date_format:m/Y',
        ]);

        try {
            $result = $this->maintainService->getAssetNeedMaintainWithMonth($request->get('time'));

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * Lay danh sach tai san dang bao duong
     */
    public function getAssetMaintaining(Request $request)
    {
        $request->validate([
            'name_code'              => 'nullable|string',
            'start_date_maintain'    => 'nullable|date|date_format:Y-m-d',
            'complete_date_maintain' => 'nullable|date|date_format:Y-m-d',
            'location'               => 'nullable|integer',
            'page'                   => 'nullable|integer',
            'limit'                  => 'nullable|integer',
        ]);

        try {
            $filters           = $request->all();
            $filters['status'] = PlanMaintainAsset::STATUS_MAINTAINING;
            $result            = $this->maintainService->getAssetMaintaining($filters);

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * Lay danh sach ke hoach bao tri
     */
    public function getPlanMaintain(Request $request)
    {
        $request->validate([
            'name_code'                 => 'nullable|string',
            'start_time'                => 'nullable|date|date_format:Y-m-d',
            'end_time'                  => 'nullable|date|date_format:Y-m-d',
            'supplier_id'               => 'nullable|integer',
            'status'                    => 'nullable|integer',
            'page'                      => 'nullable|integer',
            'limit'                     => 'nullable|integer',
        ]);

        try {
            $result = $this->maintainService->getPlanMaintain($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * Tao ke hoach bao duong
     */
    public function createPlanMaintain(CreatePlanMaintainRequest $request)
    {
        try {
            $result = $this->maintainService->createPlanMaintain($request->validated());

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function getInfoPlanMaintain(string $id)
    {
        try {
            $result = $this->maintainService->getInfoPlanMaintain($id);

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function completeAssetMaintain(Request $request)
    {
        $request->validate([
            'configs'        => 'required|array',
            'configs.*'      => 'required|array',
            'configs.*.id'   => 'required|integer',
            'configs.*.note' => 'nullable|string',
        ]);

        try {
            $result = $this->maintainService->completeAssetMaintain($request->get('configs'));
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function updatePlanMaintain(string $id, UpdatePlanMaintainRequest $request)
    {
        try {
            $result = $this->maintainService->updatePlanMaintain($id, $request->validated());
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function completePlanMaintain(string $id)
    {
        try {
            $result = $this->maintainService->completePlanMaintain($id);
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function deletePlanMaintain(string $id)
    {
        try {
            $result = $this->maintainService->deletePlanMaintain($id);
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }
}
