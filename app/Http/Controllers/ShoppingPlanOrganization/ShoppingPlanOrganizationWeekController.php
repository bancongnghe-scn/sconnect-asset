<?php

namespace App\Http\Controllers\ShoppingPlanOrganization;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterShoppingPlanOrganizationWeekRequest;
use App\Models\ShoppingPlanCompany;
use App\Services\ShoppingPlanOrganizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingPlanOrganizationWeekController extends Controller
{
    public function __construct(
        protected ShoppingPlanOrganizationService $shoppingPlanOrganizationService,
    ) {
    }

    public function getListShoppingPlanOrganizationWeek(Request $request)
    {
        $request->validate([
            'plan_quarter_id' => 'nullable|integer',
            'time'            => 'nullable|integer',
            'status'          => 'nullable|array',
            'status.*'        => 'integer',
        ]);

        Auth::user()->canPer('shopping_plan_organization.view');
        try {
            $filters         = $request->all();
            $filters['type'] = ShoppingPlanCompany::TYPE_WEEK;
            $result          = $this->shoppingPlanOrganizationService->getListShoppingPlanOrganization($filters);

            return response_success($result['data'] ?? [], extraData: $result['extra_data'] ?? []);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function registerShoppingPlanOrganizationWeek(RegisterShoppingPlanOrganizationWeekRequest $request)
    {
        Auth::user()->canPer('shopping_plan_organization.register');

        try {
            $result = $this->shoppingPlanOrganizationService->registerShoppingPlanOrganization($request->validated());

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            dd($exception);
            report($exception);

            return response_error();
        }
    }
}
