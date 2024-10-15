<?php

namespace App\Services;

use App\Repositories\ShoppingPlanCompanyRepository;

class ShoppingPlanCompanyService
{
    public function __construct(
        protected ShoppingPlanCompanyRepository $planCompanyRepository,
    ) {

    }

    public function getListPlanCompany(array $filters)
    {
        $planCompany = $this->planCompanyRepository->getListing($filters, ['id', 'name', 'created_by', 'created_at', 'status']);

        return $planCompany->toArray();
    }
}
