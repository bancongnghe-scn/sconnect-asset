<?php

namespace App\Repositories;

use App\Models\ContractMonitor;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ContractMonitorRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ContractMonitor::class;
    }

    public function deleteByContractIds($contractIds)
    {
        return $this->_model->whereIn('contract_id', Arr::wrap($contractIds))->delete();
    }

    public function getListing($filters)
    {
        $query = $this->_model->newQuery();
        if (isset($filters['contract_id'])) {
            $query->whereIn('contract_id', Arr::wrap($filters['contract_id']));
        }

        return $query->get();
    }

    public function deleteFlowersContract($contractId, $userIds)
    {
        return $this->_model->where('contract_id', $contractId)->whereIn('user_id', Arr::wrap($userIds))->delete();
    }
}
