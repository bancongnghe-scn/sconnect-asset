<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterShoppingPlanOrganizationRequest;
use App\Services\ShoppingPlanOrganizationService;
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
            return response_error();
        }
    }

    //    public function getRegisterShoppingPlanOrganization(string $id)
    //    {
    //        try {
    //            $result = $this->shoppingPlanOrganizationService->getRegisterShoppingPlanOrganization($id);
    //
    //            if ($result['success']) {
    //                return response_success($result['data']);
    //            }
    //
    //            return response_error($result['error_code']);
    //        } catch (\Throwable $exception) {
    //            return response_error();
    //        }
    //    }

    public function registerShoppingPlanOrganization(RegisterShoppingPlanOrganizationRequest $request)
    {
        Auth::user()->canPer('manager_organization');

        try {
            $result = $this->shoppingPlanOrganizationService->registerShoppingPlanOrganization($request->validated());

            if ($result['success']) {
                return response_success($result['data']);
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
