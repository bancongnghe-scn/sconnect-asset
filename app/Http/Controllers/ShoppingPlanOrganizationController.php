<?php

namespace App\Http\Controllers;

use App\Services\ShoppingPlanOrganizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingPlanOrganizationController
{
    public function __construct(
        protected ShoppingPlanOrganizationService $shoppingPlanOrganizationService,
    ) {
    }

    public function findShoppingPlanOrganization(string $id)
    {
        try {
            $result = $this->shoppingPlanOrganizationService->findShoppingPlanOrganization($id);
            if ($result['success']) {
                return response_success($result['data']);
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function getRegisterShoppingPlanOrganization(string $id)
    {
        try {
            $result = $this->shoppingPlanOrganizationService->getRegisterShoppingPlanOrganization($id);

            if ($result['success']) {
                return response_success($result['data']);
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function accountApprovalShoppingPlanOrganization(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer',
            'type'  => 'required|string',
            'note'  => 'nullable|string',
        ]);

        Auth::user()->canPer('shopping_plan_company.accounting_approval');

        try {
            $result = $this->shoppingPlanOrganizationService->accountApprovalShoppingPlanOrganization($request->all());

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function saveTotalAssetApproval(Request $request)
    {
        $request->validate([
            'id'                                     => 'required|integer',
            'registers'                              => 'required|array',
            'registers.*.assets'                     => 'required|array',
            'registers.*.assets.*.id'                => 'required|integer',
            'registers.*.assets.*.quantity_approved' => 'required|integer',
        ]);

        Auth::user()->canPer('shopping_plan_company.accounting_approval');

        try {
            $result = $this->shoppingPlanOrganizationService->saveTotalAssetApproval($request->all());

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}