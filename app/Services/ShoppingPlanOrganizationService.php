<?php

namespace App\Services;

class ShoppingPlanOrganizationService
{
    public function __construct()
    {
    }

    public function insertShoppingPlanOrganizations($shoppingPlanCompanyId, $organizationIds = [])
    {
        if (empty($organizationIds)) {
            $organizationIds = $this
        }
    }
}
