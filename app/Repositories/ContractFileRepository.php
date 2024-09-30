<?php

namespace App\Repositories;

use App\Models\Contract;
use App\Models\ContractFile;
use App\Repositories\Base\BaseRepository;

class ContractFileRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ContractFile::class;
    }
}
