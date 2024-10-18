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
        $planCompany = $this->planCompanyRepository->getListing($filters, [
            'id', 'name', 'time',
            'start_time', 'end_time', 'plan_year_id',
            'status', 'created_by', 'created_at',
        ]);

        return $planCompany->toArray();
    }
}
