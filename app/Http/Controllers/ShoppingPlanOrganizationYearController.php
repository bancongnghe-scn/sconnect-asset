<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterShoppingPlanOrganizationYearRequest;
use App\Models\ShoppingPlanCompany;
use App\Services\ShoppingPlanOrganizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingPlanOrganizationYearController extends Controller
{
    public function __construct(
        protected ShoppingPlanOrganizationService $shoppingPlanOrganizationService,
    ) {
    }

    public function getListShoppingPlanOrganizationYear(Request $request)
    {
        $request->validate([
            'time'         => 'nullable|integer',
            'status'       => 'nullable|array',
            'status.*'     => 'integer',
        ]);

        Auth::user()->canPer('shopping_plan_organization.view');
        try {
            $filters         = $request->all();
            $filters['type'] = ShoppingPlanCompany::TYPE_YEAR;
            $result          = $this->shoppingPlanOrganizationService->getListShoppingPlanOrganization($filters);

            return response_success($result['data'] ?? [], extraData: $result['extra_data'] ?? []);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function registerShoppingPlanOrganizationYear(RegisterShoppingPlanOrganizationYearRequest $request)
    {
        Auth::user()->canPer('shopping_plan_organization.register');

        try {
            $result = $this->shoppingPlanOrganizationService->registerShoppingPlanOrganization($request->validated());

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }
}