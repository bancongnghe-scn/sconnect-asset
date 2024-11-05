<?php

namespace App\Http\Controllers;

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

            return response_error();
        }
    }
}
