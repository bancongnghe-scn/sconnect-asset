<?php

namespace App\Repositories;

use App\Models\ShoppingPlanOrganization;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Auth;

class ShoppingPlanOrganizationRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ShoppingPlanOrganization::class;
    }

    public function deleteShoppingPlanOrganization($filters)
    {
        $query = $this->_model->newQuery();

        if (!empty($filters['shopping_plan_company_id'])) {
            $query->where('shopping_plan_company_id', $filters['shopping_plan_company_id']);
        }

        return $query->update([
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => Auth::id(),
        ]);
    }
}
