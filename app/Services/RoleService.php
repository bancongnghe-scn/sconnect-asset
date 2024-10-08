<?php

namespace App\Services;

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
        $role               = $this->roleRepository->insert($data);
        if (!$role) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2038,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function getListRole($filters)
    {
        $data = $this->roleRepository->getListing($filters);

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

            $deleteRoleUser = $this->roleUserRepository->deleteByRoleId($id);
            if (!$deleteRoleUser) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2041,
                ];
            }

            $deleteRolePermission = $this->rolePermissionRepository->deleteByRoleId($id);
            if (!$deleteRolePermission) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2042,
                ];
            }
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
        if (!$role->save()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2043,
            ];
        }

        return ['success' => true];
    }

    public function findRole($id)
    {
        $role = $this->roleRepository->find($id);

        return $role->toArray();
    }
}
