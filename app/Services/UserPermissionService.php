<?php

namespace App\Services;

use App\Repositories\UserPermissionRepository;

class UserPermissionService
{
    public function __construct(
        protected UserPermissionRepository $userPermissionRepository,
    ) {

    }

    public function insertUsersPermission($userIds, $permissionId)
    {
        $dataInsertUsersPermission = [];
        foreach ($userIds as $userId) {
            $dataInsertUsersPermission[] = [
                'user_id'       => $userId,
                'permission_id' => $permissionId,
            ];
        }

        return $this->userPermissionRepository->insert($dataInsertUsersPermission);
    }

    public function updateUsersPermission(array $userIds, $permissionId)
    {
        $usersPermission = $this->userPermissionRepository->getListing(['permission_id' => $permissionId]);
        $userIdsOld      = $usersPermission->pluck('user_id')->toArray();
        $newUserIds      = array_diff($userIds, $userIdsOld);
        $removeUserIds   = array_diff($userIdsOld, $userIds);

        if (!empty($newUserIds)) {
            $insertUsersPermission = $this->insertUsersPermission($newUserIds, $permissionId);
            if (!$insertUsersPermission) {
                return false;
            }
        }

        if (!empty($removeUserIds)) {
            $this->userPermissionRepository->deleteByUserIdsAndPermissionId($removeUserIds, $permissionId);
        }

        return true;
    }
}
