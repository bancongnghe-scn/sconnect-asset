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

        if (!empty($filters['ids'])) {
            $query->whereIn('id', Arr::wrap($filters['ids']));
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', Arr::wrap($filters['status']));
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

    public function getFirst($filters, $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns);
        if (!empty($filters['shopping_plan_company_id'])) {
            $query->where('shopping_plan_company_id', $filters['shopping_plan_company_id']);
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', Arr::wrap($filters['status']));
        }

        return $query->first();
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);

        if (!empty($filters['shopping_plan_company_id'])) {
            $query->whereIn('shopping_plan_company_id', Arr::wrap($filters['shopping_plan_company_id']));
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', Arr::wrap($filters['status']));
        }

        return $query->get();
    }

    public function getListingOfOrganization($filters, $organizationId, $columns = [
        'shopping_plan_company.name', 'shopping_plan_company.time', 'shopping_plan_company.start_time', 'shopping_plan_company.end_time',
        'shopping_plan_company.type', 'shopping_plan_company.plan_year_id', 'shopping_plan_company.plan_quarter_id', 'shopping_plan_company.month',
        'shopping_plan_company.created_at', 'shopping_plan_company.created_by', 'shopping_plan_organization.status', 'shopping_plan_organization.id',
    ])
    {
        $query = $this->_model->newQuery()->select($columns)
            ->join('shopping_plan_company', 'shopping_plan_company.id', 'shopping_plan_organization.shopping_plan_company_id')
            ->where('shopping_plan_organization.organization_id', $organizationId)
            ->orderBy('shopping_plan_organization.created_at', 'desc');

        if (!empty($filters['time'])) {
            $query->where('shopping_plan_company.time', $filters['time']);
        }

        if (!empty($filters['type'])) {
            $query->where('shopping_plan_company.type', $filters['type']);
        }

        if (!empty($filters['status'])) {
            $query->whereIn('shopping_plan_organization.status', Arr::wrap($filters['status']));
        }

        if (!empty($filters['plan_year_id'])) {
            $query->where('shopping_plan_company.plan_year_id', $filters['plan_year_id']);
        }

        if (!empty($filters['plan_quarter_id'])) {
            $query->where('shopping_plan_company.plan_quarter_id', $filters['plan_quarter_id']);
        }

        if (!empty($filters['from'])) {
            $query->where('shopping_plan_company.start_time', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->where('shopping_plan_company.end_time', '<=', $filters['to']);
        }

        if (!empty($filters['name'])) {
            $query->where('shopping_plan_company.name', $filters['name']);
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }
}
