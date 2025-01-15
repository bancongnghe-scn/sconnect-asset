<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePlanMaintainRequest;
use App\Services\MaintainService;
use Illuminate\Http\Request;

class MaintainController extends Controller
{
    public function __construct(
        protected MaintainService $maintainService,
    ) {

    }

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
            dd($exception);
            report($exception);

            return response_error();
        }
    }

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
            $result = $this->maintainService->getAssetMaintaining($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

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

    public function createPlanMaintain(CreatePlanMaintainRequest $request)
    {

    }
}
