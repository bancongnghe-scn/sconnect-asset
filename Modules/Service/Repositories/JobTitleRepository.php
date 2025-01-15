<?php

namespace Modules\Service\Repositories;

use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Service\Models\JobTitle;

class JobTitleRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return JobTitle::class;
    }

    public function getJobs($filters)
    {
        $query = $this->_model->select(
            'org_job_titles.id',
            DB::raw("CONCAT(c1.cfg_key, ' ', c2.cfg_key) AS name"),
            'org_job_titles.org_id'
        )
            ->join('configs as c1', 'org_job_titles.position', '=', 'c1.id')
            ->join('configs as c2', 'org_job_titles.job_position', '=', 'c2.id');

        if (!empty($filters['id'])) {
            $query->whereIn('org_job_titles.id', Arr::wrap($filters['id']));
        }

        if (!empty($filters['org_id'])) {
            $query->whereIn('org_job_titles.org_id', Arr::wrap($filters['org_id']));
        }

        return $query->get();
    }
}
