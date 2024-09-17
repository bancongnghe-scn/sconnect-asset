<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AssetTypeGroupRepository;
use App\Support\AppErrorCode;
use Illuminate\Support\Facades\Auth;

class AssetTypeGroupService
{
    public function __construct(
        protected AssetTypeGroupRepository $assetTypeGroupRepository
    )
    {

    }

    public function getListAssetTypeGroup(array $filters)
    {
        $data = $this->assetTypeGroupRepository->getListAssetTypeGroup($filters);

        return $data->toArray();
    }

    public function createAssetTypeGroup(array $data)
    {
        $assetTypeGroup = $this->assetTypeGroupRepository->getAssetTypeByName($data['name']);
        if (!empty($assetTypeGroup)) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2001
            ];
        }

        $data['created_by'] = User::USER_ID_ADMIN;
        $createAssetTypeGroup = $this->assetTypeGroupRepository->insert($data);
        if (!$createAssetTypeGroup) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2000
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function deleteAssetTypeGroup($id)
    {
        $assetTypeGroup = $this->assetTypeGroupRepository->find($id);
        if (is_null($assetTypeGroup)) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2002
            ];
        }

        $assetTypeGroup->deleted_at = date('Y-m-d H:i:s');
        $assetTypeGroup->deleted_by = User::USER_ID_ADMIN;

        if (!$assetTypeGroup->save()) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2003
            ];
        }

        return [
            'success' => true
        ];
    }

    public function updateAssetTypeGroup($data)
    {
        $assetTypeGroup = $this->assetTypeGroupRepository->find($data['id']);
        if (is_null($assetTypeGroup)) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2002
            ];
        }

        $data['updated_by'] = User::USER_ID_ADMIN;
        $data['updated_at'] = date('Y-m-d H:i:s');
        $assetTypeGroup->fill($data);
        if (!$assetTypeGroup->save()) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2003
            ];
        }

        return [
            'success' => true
        ];
    }
}
