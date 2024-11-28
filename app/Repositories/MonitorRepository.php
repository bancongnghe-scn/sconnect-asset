<?php

namespace App\Repositories;

use App\Models\Monitor;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class MonitorRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return Monitor::class;
    }

    public function getListing($filters)
    {
        $query = $this->_model->newQuery();
        if (!empty($filters['target_id'])) {
            $query->whereIn('target_id', Arr::wrap($filters['target_id']));
        }

        if (!empty($filters['type'])) {
            $query->whereIn('type', Arr::wrap($filters['type']));
        }

        if (!empty($filters['user_id'])) {
            $query->whereIn('user_id', Arr::wrap($filters['user_id']));
        }

        return $query->get();
    }

    public function deleteMonitor(array $filters)
    {
        $query = $this->_model->newQuery();
        if (!empty($filters['target_id'])) {
            $query->whereIn('target_id', Arr::wrap($filters['target_id']));
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['user_id'])) {
            $query->whereIn('user_id', Arr::wrap($filters['user_id']));
        }

        return $query->delete();
    }
}
