<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShoppingPlanCompanyYearRequest;
use App\Models\ShoppingPlanCompany;
use App\Services\ShoppingPlanCompanyService;
use Illuminate\Support\Facades\Auth;

class ShoppingPlanCompanyYearController extends Controller
{
    public function __construct(
        protected ShoppingPlanCompanyService $planCompanyService,
    ) {

    }

    public function createShoppingPlanCompanyYear(CreateShoppingPlanCompanyYearRequest $request)
    {
        Auth::user()->canPer('shopping_plan_company.insert	');

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
        Auth::user()->canPer('shopping_plan_company.insert	');

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
