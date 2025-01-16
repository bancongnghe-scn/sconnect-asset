<?php

namespace App\Repositories\Manage;

use App\Models\PlanMaintain;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class PlanMaintainRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return PlanMaintain::class;
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with)->orderBy('created_at', 'DESC');

        if (!empty($filters['id'])) {
            $query->whereIn('id', Arr::wrap($filters['id']));
        }

        if (!empty($filters['name_code'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('code', 'LIKE', $filters['name_code'] . '%')
                  ->orWhere('name', 'LIKE', $filters['name_code'] . '%');
            });
        }

        if (!empty($filters['created_at'])) {
            $query->whereDate('created_at', $filters['created_at']);
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', Arr::wrap($filters['status']));
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }

    public function deleteMultipleByIds($ids)
    {
        return $this->_model->whereIn('id', Arr::wrap($ids))->delete();
    }

    public function getListPlanMaintain($filters, $columns = ['*'])
    {
        $query = $this->_model->select($columns)
            ->where('plan_maintain.type', PlanMaintain::TYPE_MAINTAIN)
            ->newQuery();

        if (!empty($filters['name_code'])) {
            $query->where('plan_maintain.name', 'like', '%' . $filters['name_code'] . '%')
                ->orWhere('plan_maintain.code', 'like', '%' . $filters['name_code'] . '%');
        }

        if (!empty($filters['start_time']) && !empty($filters['end_time'])) {
            $query->where('plan_maintain.start_time', '>=', $filters['start_time'])
                ->where('plan_maintain.end_time', '<=', $filters['end_time']);
        }

        if (!empty($filters['status'])) {
            $query->where('plan_maintain.status', $filters['status']);
        }

        if (!empty($filters['supplier_id'])) {
            $query->leftJoin('plan_maintain_supplier', 'plan_maintain_supplier.plan_maintain_id', 'plan_maintain.id')
                ->where('plan_maintain_supplier.supplier_id', $filters['supplier_id']);
        }

        return $query->get();
    }
}
