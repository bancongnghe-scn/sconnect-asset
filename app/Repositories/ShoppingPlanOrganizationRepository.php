<?php

namespace App\Repositories;

use App\Models\ShoppingPlanOrganization;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;
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
            $query->whereIn('shopping_plan_company_id', Arr::wrap($filters['shopping_plan_company_id']));
        }

        return $query->update([
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => Auth::id(),
        ]);
    }

    public function updateShoppingPlanOrganization(array $filters, array $dataUpdate)
    {
        $query = $this->_model->newQuery();
        if (!empty($filters['shopping_plan_company_id'])) {
            $query->where('shopping_plan_company_id', $filters['shopping_plan_company_id']);
        }

        return $query->update($dataUpdate);
    }

    public function getInfoShoppingPlanOrganizationById($id, $columns = [
        'shopping_plan_company.name', 'shopping_plan_company.start_time', 'shopping_plan_company.end_time',
        'shopping_plan_organization.organization_id', 'shopping_plan_organization.status',
    ])
    {
        return $this->_model->select($columns)
            ->join('shopping_plan_company', 'shopping_plan_company.id', 'shopping_plan_organization.shopping_plan_company_id')
            ->where('shopping_plan_organization.id', $id)->first();
    }
}
