<?php

namespace App\Http\Controllers;

use App\Services\ShoppingPlanCompanyService;
use Illuminate\Http\Request;

class ShoppingPlanCompanyController extends Controller
{
    public function __construct(
        protected ShoppingPlanCompanyService $planCompanyService,
    ) {

    }

    public function getListShoppingPlanCompany(Request $request)
    {
        $request->validate([
            'name'         => 'nullable|string|max:255',
            'plan_year_id' => 'nullable|integer',
            'time'         => 'nullable|integer',
            'type'         => 'nullable|integer',
            'status'       => 'nullable|array',
            'status.*'     => 'integer',
            'start_time'   => 'nullable|date|date_format:Y-m-d',
            'end_time'     => 'nullable|date|date_format:Y-m-d',
        ]);

        try {
            $result = $this->planCompanyService->getListPlanCompany($request->all());

            return response_success($result['data'] ?? [], extraData: $result['extra_data'] ?? []);
        } catch (\Throwable $exception) {

            return response_error();
        }
    }

    public function deleteShoppingPlanCompany(string $id)
    {
        try {
            $result = $this->planCompanyService->deleteShoppingPlanCompany($id);
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function deleteMultiple(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer',
        ]);

        try {
            $result = $this->planCompanyService->deleteShoppingPlanCompanyMultiple($request->get('ids'));

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function findShoppingPlanCompany(string $id)
    {

        try {
            $result = $this->planCompanyService->findShoppingPlanCompany($id);
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success($result['data']);
        } catch (\Throwable $exception) {
            dd($exception);

            return response_error();
        }
    }

    public function sentNotificationRegister(string $id)
    {
        try {
            $result = $this->planCompanyService->sentNotificationRegister($id);

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
