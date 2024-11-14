<?php

namespace App\Services;

use App\Repositories\MenuRoleRepository;

class MenuRoleService
{
    public function __construct(
        protected MenuRoleRepository $menuRoleRepository,
    ) {

    }

    public function insertMenuRoles($roleIds, $menuId)
    {
        $dataCreateRoleMenu = [];
        foreach ($roleIds as $roleId) {
            $dataCreateRoleMenu[] = [
                'role_id' => $roleId,
                'menu_id' => $menuId,
            ];
        }

        return $this->menuRoleRepository->insert($dataCreateRoleMenu);
    }

    public function updateMenuRoles($roleIds, $menuId)
    {
        $rolesMenu     = $this->menuRoleRepository->getListing(['menu_id' => $menuId]);
        $roleIdsOld    = $rolesMenu->pluck('role_id')->toArray();
        $newRoleIds    = array_diff($roleIds, $roleIdsOld);
        $removeRoleIds = array_diff($roleIdsOld, $roleIds);

        if (!empty($newRoleIds)) {
            $insertMenuRoles = $this->insertMenuRoles($newRoleIds, $menuId);
            if (!$insertMenuRoles) {
                return false;
            }
        }

        if (!empty($removeRoleIds)) {
            $this->menuRoleRepository->deleteMenuRole($removeRoleIds, $menuId);
        }

        return true;
    }
}
