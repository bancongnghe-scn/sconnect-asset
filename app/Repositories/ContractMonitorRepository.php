<?php

namespace App\Repositories;

use App\Models\ContractMonitor;
use App\Repositories\Base\BaseRepository;

class ContractMonitorRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ContractMonitor::class;
    }
}
