<?php

namespace Modules\Service\Repositories;

use App\Repositories\Base\BaseRepository;
use Modules\Service\Models\OrgInfo;

class OrgInfoRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return OrgInfo::class;
    }
}
