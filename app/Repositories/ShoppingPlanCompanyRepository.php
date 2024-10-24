<?php

namespace App\Repositories;

use App\Models\ShoppingPlanCompany;
use App\Models\ShoppingPlanOrganization;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class ShoppingPlanCompanyRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ShoppingPlanCompany::class;
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with)->orderBy('created_at', 'desc');

        if (!empty($filters['time'])) {
            $query->whereIn('time', Arr::wrap($filters['time']));
        }

        if (!empty($filters['id'])) {
            $query->whereIn('id', Arr::wrap($filters['id']));
        }

        if (!empty($filters['type'])) {
            $query->whereIn('type', Arr::wrap($filters['type']));
        }

        if (!empty($filters['plan_year_id'])) {
            $query->whereIn('plan_year_id', Arr::wrap($filters['plan_year_id']));
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', Arr::wrap($filters['status']));
        }

        if (!empty($filters['name'])) {
            $query->where('name', 'like', $filters['name'].'%');
        }

        if (!empty($filters['from'])) {
            $query->where('start_time', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->where('end_time', '<=', $filters['to']);
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }

    public function getFirst($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);
        if (!empty($filters['id'])) {
            $query->where('id', $filters['id']);
        }

        if (!empty($filters['time'])) {
            $query->where('time', $filters['time']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['plan_year_id'])) {
            $query->where('plan_year_id', $filters['plan_year_id']);
        }

        if (!empty($filters['plan_quarter_id'])) {
            $query->where('plan_quarter_id', $filters['plan_quarter_id']);
        }

        if (!empty($filters['month'])) {
            $query->where('month', $filters['month']);
        }

        return $query->first();
    }

    public function deleteShoppingPlanCompanyByIds(array $ids)
    {
        return $this->_model->whereIn('id', $ids)->delete();
    }

    public function getListingOfOrganization($filters, $organizationId, $columns = [
        'shopping_plan_company.name', 'shopping_plan_company.time', 'shopping_plan_company.start_time', 'shopping_plan_company.end_time',
        'shopping_plan_company.type', 'shopping_plan_company.plan_year_id', 'shopping_plan_company.plan_quarter_id', 'shopping_plan_company.month',
        'shopping_plan_company.created_at', 'shopping_plan_company.created_by', 'shopping_plan_organization.status', 'shopping_plan_organization.id',
    ])
    {
        $query = $this->_model->newQuery()->select($columns)
            ->join('shopping_plan_organization', 'shopping_plan_organization.shopping_plan_company_id', 'shopping_plan_company.id')
            ->where('shopping_plan_organization.organization_id', $organizationId)
            ->where('shopping_plan_organization.status', '!=', ShoppingPlanOrganization::STATUS_NEW);

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
