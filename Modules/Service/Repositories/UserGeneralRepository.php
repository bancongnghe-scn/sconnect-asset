<?php

namespace Modules\Service\Repositories;

use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;
use Modules\Service\Models\UserGeneral;

class UserGeneralRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return UserGeneral::class;
    }

    public function getListing($filters = [], $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns);

        if (!empty($filters['user_id'])) {
            $query->whereIn('user_id', Arr::wrap($filters['user_id']));
        }

        return $query->get();
    }
}
