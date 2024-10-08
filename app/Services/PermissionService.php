<?php

namespace App\Services;

use App\Repositories\PermissionRepository;
use App\Repositories\RolePermissionRepository;
use App\Repositories\UserPermissionRepository;
use App\Support\AppErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    public function __construct(
        protected PermissionRepository $permissionRepository,
        protected RolePermissionRepository $rolePermissionRepository,
        protected UserPermissionRepository $userPermissionRepository,
    ) {

    }

    public function createPermission($data)
    {
        $permission = $this->permissionRepository->getFirst(['name' => $data['name']]);
        if (!empty($permission)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2044,
            ];
        }

        $data['created_by'] = Auth::id();
        $permission         = $this->permissionRepository->insert($data);
        if (!$permission) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2045,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function getListPermission($filters)
    {
        $data = $this->permissionRepository->getListing($filters, ['id', 'name', 'description']);

        return $data->toArray();
    }

    public function deletePermissionById($id)
    {
        $permission = $this->permissionRepository->find($id);
        if (empty($permission)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2047,
            ];
        }

        DB::beginTransaction();
        try {
            if (!$permission->delete()) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2046,
                ];
            }

            $this->userPermissionRepository->deleteByPermissionId($id);
            $this->rolePermissionRepository->deleteByPermissionId($id);

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

    public function updatePermission($data, $id)
    {
        $permission = $this->permissionRepository->find($id);
        if (empty($permission)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2047,
            ];
        }

        $data['updated_by'] = Auth::id();
        $permission->fill($data);
        if (!$permission->save()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2048,
            ];
        }

        return ['success' => true];
    }

    public function findPermission($id)
    {
        $permission = $this->permissionRepository->find($id);

        return $permission->toArray();
    }
}
