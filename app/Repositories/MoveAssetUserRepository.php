<?php

namespace App\Repositories;

use App\Models\MoveAssetUser;
use App\Repositories\Base\BaseRepository;

class MoveAssetUserRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return MoveAssetUser::class;
    }
}
