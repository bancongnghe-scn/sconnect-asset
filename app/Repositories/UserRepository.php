<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Base\BaseReadRepository;
use App\Repositories\Base\BaseRepository;

class UserRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return User::class;
    }
}
