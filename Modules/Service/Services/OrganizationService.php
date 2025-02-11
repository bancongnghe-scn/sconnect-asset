<?php

namespace Modules\Service\Services;

use Modules\Service\Repositories\OrganizationRepository;

class OrganizationService
{
    public function __construct(
        protected OrganizationRepository $organizationRepository,
    ) {

    }

    public function getListOrganization($filters = [])
    {
        $data = $this->organizationRepository->getInfoOrganizationByFilters($filters);

        return $data->toArray();
    }

    public function getOrganizationalStructure(array $ids = [])
    {
        // Tải tất cả các bản ghi một lần
        $organizations = $this->organizationRepository->getInfoOrganizationByFilters([])->keyBy('id');

        $result = [];

        if (empty($ids)) {
            $ids = $organizations->pluck('id')->toArray();
        }
        foreach ($ids as $id) {
            if (!isset($organizations[$id])) {
                continue; // Bỏ qua nếu không tìm thấy bản ghi
            }

            $hierarchy = [];
            $current   = $organizations[$id];

            while ($current) {
                $hierarchy[] = $current->name; // Lấy tên phòng ban
                $current     = $organizations[$current->parent_id] ?? null; // Truy ngược lên cha
            }

            $hierarchy = array_reverse($hierarchy); // Đảo ngược thứ tự từ gốc đến con

            $result[$id] = [
                'id'        => $id,
                'hierarchy' => $hierarchy, // Ghép tên các phòng ban
            ];
        }

        return $result;
    }
}
