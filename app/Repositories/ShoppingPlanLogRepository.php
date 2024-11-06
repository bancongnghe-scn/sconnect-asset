<?php

namespace App\Repositories;

use App\Models\ShoppingPlanLog;
use App\Repositories\Base\BaseRepository;

class ShoppingPlanLogRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ShoppingPlanLog::class;
    }
}
