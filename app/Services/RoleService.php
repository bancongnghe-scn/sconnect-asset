<?php

namespace App\Services;

use App\Http\Resources\RoleInfoResource;
use App\Repositories\RolePermissionRepository;
use App\Repositories\RoleRepository;
use App\Repositories\RoleUserRepository;
use App\Support\AppErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleService
{
    public function __construct(
        protected RoleRepository $roleRepository,
        protected RoleUserRepository $roleUserRepository,
        protected RolePermissionRepository $rolePermissionRepository,
    ) {

    }

    public function createRole($data)
    {
        $role = $this->roleRepository->getFirst(['name' => $data['name']]);
        if (!empty($role)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2037,
            ];
        }

        $data['created_by'] = Auth::id();

        DB::beginTransaction();
        try {
            $role = $this->roleRepository->create($data);

            $userIds = $data['user_ids'] ?? [];
            if (!empty($userIds)) {
                $insertRoleUsers = resolve(RoleUserService::class)->insertRoleUsers($userIds, $role->id);
                if (!$insertRoleUsers) {
                    DB::rollBack();

                    return  [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2049,
                    ];
                }
            }

            $permissionIds = $data['permission_ids'] ?? [];
            if (!empty($permissionIds)) {
                $insertRolePermissions = resolve(RolePermissionService::class)->insertRolePermissions($permissionIds, $role->id);
                if (!$insertRolePermissions) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2050,
                    ];
                }
            }

            DB::commit();

        } catch (\Throwable $exception) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function getListRole($filters)
    {
        $data = $this->roleRepository->getListing($filters, ['id', 'name', 'description']);

        return $data->toArray();
    }

    public function deleteRoleById($id)
    {
        $role = $this->roleRepository->find($id);
        if (empty($role)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2040,
            ];
        }

        DB::beginTransaction();
        try {
            if (!$role->delete()) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2039,
                ];
            }

            $this->roleUserRepository->deleteByRoleId($id);
            $this->rolePermissionRepository->deleteByRoleId($id);

            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function updateRole($data, $id)
    {
        $role = $this->roleRepository->find($id);
        if (empty($role)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2040,
            ];
        }

        $data['updated_by'] = Auth::id();
        $role->fill($data);
        DB::beginTransaction();
        try {
            if (!$role->save()) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2043,
                ];
            }

            $userIds = $data['user_ids'] ?? [];
            if (!empty($userIds)) {
                $updateRoleUsers = resolve(RoleUserService::class)->updateRoleUsers($userIds, $id);
                if (!$updateRoleUsers) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2041,
                    ];
                }
            }

            $permissionIds = $data['permission_ids'] ?? [];
            if (!empty($permissionIds)) {
                $updateRolePermissions = resolve(RolePermissionService::class)->updateRolePermissions($permissionIds, $id);
                if (!$updateRolePermissions) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2042,
                    ];
                }
            }

            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

    }

    public function findRole($id)
    {
        $role = $this->roleRepository->getFirst(['id' => $id], with: ['roleUsers', 'rolePermissions']);

        return RoleInfoResource::make($role)->resolve();
    }
}
