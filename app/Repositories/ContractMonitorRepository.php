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
}
