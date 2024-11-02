<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShoppingPlanCompanyYearRequest;
use App\Models\ShoppingPlanCompany;
use App\Services\ShoppingPlanCompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingPlanCompanyYearController extends Controller
{
    public function __construct(
        protected ShoppingPlanCompanyService $planCompanyService,
    ) {

    }

    public function getListShoppingPlanCompanyYear(Request $request)
    {
        $request->validate([
            'time'         => 'nullable|integer',
            'status'       => 'nullable|array',
            'status.*'     => 'integer',
        ]);

        $filters         = $request->all();
        $filters['type'] = ShoppingPlanCompany::TYPE_YEAR;
        try {
            $result = $this->planCompanyService->getListPlanCompany($filters);

            return response_success($result['data'] ?? [], extraData: $result['extra_data'] ?? []);
        } catch (\Throwable $exception) {

            return response_error();
        }
    }

    public function createShoppingPlanCompanyYear(CreateShoppingPlanCompanyYearRequest $request)
    {
        Auth::user()->canPer('shopping_plan_company.insert');

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
