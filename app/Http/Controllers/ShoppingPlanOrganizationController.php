<?php

namespace App\Http\Controllers;

use App\Services\ShoppingPlanOrganizationService;

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

    public function accountApprovalShoppingPlanOrganization(string $id)
    {
        try {
            $result = $this->shoppingPlanOrganizationService->accountApprovalShoppingPlanOrganization($id);

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function accountDisapprovalShoppingPlanOrganization(string $id)
    {
        try {
            $result = $this->shoppingPlanOrganizationService->accountDisapprovalShoppingPlanOrganization($id);

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
