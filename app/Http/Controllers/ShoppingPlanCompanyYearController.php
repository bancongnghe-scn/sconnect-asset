<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShoppingPlanCompanyYearRequest;
use App\Models\ShoppingPlanCompany;
use App\Services\ShoppingPlanCompanyService;

class ShoppingPlanCompanyYearController extends Controller
{
    public function __construct(
        protected ShoppingPlanCompanyService $planCompanyService,
    ) {

    }

    public function createShoppingPlanCompanyYear(CreateShoppingPlanCompanyYearRequest $request)
    {
        try {
            $data         = $request->validated();
            $data['type'] = ShoppingPlanCompany::TYPE_YEAR;

            $result = $this->planCompanyService->createShoppingPlanCompany($data);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function updateShoppingPlanCompanyYear(CreateShoppingPlanCompanyYearRequest $request, string $id)
    {
        try {
            $data         = $request->validated();
            $data['type'] = ShoppingPlanCompany::TYPE_YEAR;
            $result       = $this->planCompanyService->updateShoppingPlanCompany($data, $id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
