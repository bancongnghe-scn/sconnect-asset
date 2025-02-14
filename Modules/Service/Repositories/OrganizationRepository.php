<?php

namespace Modules\Service\Repositories;

use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;
use Modules\Service\Models\Organization;

class OrganizationRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return Organization::class;
    }

    public function getListing($filters, $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns);

        if (!empty($filters['id'])) {
            $query->whereIn('id', Arr::wrap($filters['id']));
        }
        if (!empty($filters['status'])) {
            $query->whereIn('status', Arr::wrap($filters['status']));
        }
        if (!empty($filters['parent_id'])) {
            $query->whereIn('parent_id', Arr::wrap($filters['parent_id']));
        }

        return $query->get();
    }

    public function getInfoOrganizationByFilters($filters)
    {
        $query = $this->_model->newQuery()->selectRaw(
            "organizations.id, organizations.status, organizations.manager_id, organizations.parent_id,
             CONCAT(configs.cfg_key, ' ', organizations.name) AS name"
        )->join('configs', 'configs.id', 'organizations.dept_type_id');

        if (!empty($filters['id'])) {
            $query->whereIn('organizations.id', Arr::wrap($filters['id']));
        }

        if (!empty($filters['parent_id'])) {
            $query->where('organizations.parent_id', $filters['parent_id']);
        }

        if (!empty($filters['status'])) {
            $query->whereIn('organizations.status', Arr::wrap($filters['status']));
        }

        return $query->get();
    }

    public function getOrganizationMain()
    {
        return $this->_model->newQuery()->selectRaw(
            "organizations.id, organizations.status, organizations.manager_id, organizations.parent_id,
             CONCAT(configs.cfg_key, ' ', organizations.name) AS name"
        )->join('configs', 'configs.id', 'organizations.dept_type_id')
            ->where(function ($query) {
                $query->where('organizations.parent_id', Organization::PARENT_MAIN)
                    ->orWhere('organizations.id', Organization::PARENT_MAIN);
            })
            ->where('organizations.status', Organization::STATUS_ACTIVE)->get();
    }
}
