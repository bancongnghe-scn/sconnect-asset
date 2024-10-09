<?php

namespace App\Services;

use App\Repositories\RoleUserRepository;

class RoleUserService
{
    public function __construct(
        protected RoleUserRepository $roleUserRepository,
    ) {

    }

    public function insertRoleUsers(array $userIds, $roleId)
    {
        $dataInsertRoleUser = [];
        foreach ($userIds as $userId) {
            $dataInsertRoleUser[] = [
                'user_id' => $userId,
                'role_id' => $roleId,
            ];
        }

        return $this->roleUserRepository->insert($dataInsertRoleUser);
    }

    public function updateRoleUsers(array $userIds, $roleId)
    {
        $roleUsers     = $this->roleUserRepository->getListing(['role_id' => $roleId]);
        $userIdsOld    = $roleUsers->pluck('user_id')->toArray();
        $newUserIds    = array_diff($userIds, $userIdsOld);
        $removeUserIds = array_diff($userIdsOld, $userIds);

        if (!empty($newUserIds)) {
            $insertRoleUsers = $this->insertRoleUsers($newUserIds, $roleId);
            if (!$insertRoleUsers) {
                return false;
            }
        }

        if (!empty($removeUserIds)) {
            $this->roleUserRepository->deleteRoleUsers($removeUserIds, $roleId);
        }

        return true;
    }
}
