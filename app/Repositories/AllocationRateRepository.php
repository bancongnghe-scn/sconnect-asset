<?php

namespace App\Repositories;

use App\Models\AllocationRate;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AllocationRateRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return AllocationRate::class;
    }

    public function getList($filters = [])
    {
        $query = $this->_model->newQuery();

        if (!empty($filters['type'])) {
            if (AllocationRate::TYPE_ORGANIZATION == $filters['type']) {
                $query->select('organization_id', DB::raw('group_concat(id) as ids'))
                    ->whereNull('position_id')->groupBy('organization_id');
            } else {
                $query->select('position_id', 'organization_id', DB::raw('group_concat(id) as ids'))
                    ->whereNotNull('position_id')
                    ->groupBy('organization_id', 'position_id');
            }
        }

        if (!empty($filters['id'])) {
            $query->whereIn('id', Arr::wrap($filters['id']));
        }

        if (!empty($filters['organization_id'])) {
            $query->where('organization_id', $filters['organization_id']);
        }

        if (!empty($filters['position_id'])) {
            $query->where('position_id', $filters['position_id']);
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }

    public function getAllocationRatePosition($organizationId, $positionId, $first = false)
    {
        $query = $this->_model->newQuery()->where('organization_id', $organizationId)->where('position_id', $positionId);
        if ($first) {
            return $query->first();
        }

        return $query->get();
    }

    public function getAllocationRateOrganization($organizationId, $first = false)
    {
        $query = $this->_model->newQuery()->where('organization_id', $organizationId)->whereNull('position_id');
        if ($first) {
            return $query->first();
        }

        return $query->get();
    }

    public function deleteByIds($ids)
    {
        return $this->_model->whereIn('id', Arr::wrap($ids))->delete();
    }

    public function deleteAllocationRatePosition($organizationId, $positionId)
    {
        return $this->_model->whereIn('organization_id', $organizationId)->whereIn('position_id', $positionId)->delete();
    }

    public function deleteAllocationRateOrganization($organizationId)
    {
        return $this->_model->whereIn('organization_id', $organizationId)->whereNull('position_id')->delete();
    }
}
