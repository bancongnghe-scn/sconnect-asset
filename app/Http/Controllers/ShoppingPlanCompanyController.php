<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShoppingPlanCompanyYearRequest;
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
            'name'         => 'nullable|string',
            'plan_year_id' => 'nullable|integer',
            'time'         => 'nullable|integer',
            'type'         => 'nullable|integer',
            'status'       => 'nullable|integer',
            'start_time'   => 'nullable|date|date_format:Y-m-d',
            'end_time'     => 'nullable|date|date_format:Y-m-d',
        ]);

        try {
            $result = $this->planCompanyService->getListPlanCompany($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function createShoppingPlanCompanyYear(CreateShoppingPlanCompanyYearRequest $request)
    {
        try {
            $result = $this->planCompanyService->createShoppingPlanCompanyYear($request->validated());

            if (!$result['success']) {
                return response_error($result['error_code'], extraData: $result['extra_data']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function updateShoppingPlanCompanyYear(CreateShoppingPlanCompanyYearRequest $request, string $id)
    {
        try {
            $result = $this->planCompanyService->updateShoppingPlanCompanyYear($request->validated(), $id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function deleteShoppingPlanCompanyYear(string $id)
    {
        try {
            $result = $this->planCompanyService->deleteShoppingPlanCompanyYear($id);
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
