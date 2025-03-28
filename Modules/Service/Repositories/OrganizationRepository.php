<?php

namespace Modules\Service\Repositories;

use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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
        if (!empty($filters['manager_id'])) {
            $query->where('manager_id', $filters['manager_id']);
        }
        if (!empty($filters['first'])) {
            return $query->first();
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

    /**
     * @return \Illuminate\Support\Collection
     * Lấy các đơn vị con
     */
    public function departmentTreeCollection($parentId)
    {
        $organizations = DB::connection('mysql_service')->select(
            "
            WITH RECURSIVE temp(id, name, level, path, parent_id) AS (
                SELECT organizations.id, CONCAT(configs.cfg_key, ' ', organizations.name) AS name,
                       0 AS level, CAST(organizations.id AS CHAR(200)) AS path, parent_id
                FROM organizations
                JOIN configs ON organizations.dept_type_id = configs.id
                WHERE organizations.id = ?

                UNION ALL

                SELECT b.id, CONCAT(REPEAT(' ', a.level + 1), configs.cfg_key, ' ', b.name) AS name,
                       a.level + 1, CONCAT(a.path, '->', b.id), b.parent_id
                FROM temp AS a
                JOIN organizations AS b ON a.id = b.parent_id
                JOIN configs ON b.dept_type_id = configs.id
            )
            SELECT id, name, level, parent_id FROM temp ORDER BY path;",
            Arr::wrap($parentId)
        );

        return collect($organizations);
    }

    /**
     * @return \Illuminate\Support\Collection
     * Lấy ra đơn vị cha
     */
    public function getParentOrganization($organizationId)
    {
        $organizations = DB::connection('mysql_service')->select(
            '
            WITH RECURSIVE unit_hierarchy AS (
                SELECT id, parent_id, name
                FROM organizations
                WHERE id = ?

                UNION ALL

                SELECT u.id, u.parent_id, u.name
                FROM organizations u
                         INNER JOIN unit_hierarchy uh ON u.id = uh.parent_id
            )
            SELECT * FROM unit_hierarchy',
            Arr::wrap($organizationId)
        );

        return collect($organizations);
    }
}
