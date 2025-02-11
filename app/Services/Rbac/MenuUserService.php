<?php

namespace App\Services\Rbac;

use App\Repositories\Rbac\MenuUserRepository;

class MenuUserService
{
    public function __construct(
        protected MenuUserRepository $menuUserRepository,
    ) {
    }

    public function insertMenuUsers($userIds, $menuId)
    {
        $dataCreateMenuUsers = [];
        foreach ($userIds as $userId) {
            $dataCreateMenuUsers[] = [
                'user_id' => $userId,
                'menu_id' => $menuId,
            ];
        }

        return $this->menuUserRepository->insert($dataCreateMenuUsers);
    }

    public function updateMenuUsers($userIds, $menuId)
    {
        $menuUsers     = $this->menuUserRepository->getListing(['menu_id' => $menuId]);
        $userIdsOld    = $menuUsers->pluck('user_id')->toArray();
        $newUserIds    = array_diff($userIds, $userIdsOld);
        $removeUserIds = array_diff($userIdsOld, $userIds);

        if (!empty($newUserIds)) {
            $insertMenuUsers = $this->insertMenuUsers($newUserIds, $menuId);
            if (!$insertMenuUsers) {
                return false;
            }
        }

        if (!empty($removeUserIds)) {
            $this->menuUserRepository->deleteMenuUser($removeUserIds, $menuId);
        }

        return true;
    }
}
