<?php

namespace App\Repositories;

use App\Models\PlanMaintainOrganization;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class PlanMaintainOrganizationRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return PlanMaintainOrganization::class;
    }

    public function deleteByCondition($filters)
    {
        $query = $this->_model->newQuery();
        if ($filters['plan_maintain_id']) {
            $query->where('plan_maintain_id', $filters['plan_maintain_id']);
        }

        return $query->delete();
    }

    public function getByOrganizationId($organizationIds)
    {
        return $this->_model->newQuery()->whereIn('organization_id', Arr::wrap($organizationIds))->get();
    }
}
