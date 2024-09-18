<?php

namespace App\Services;

use App\Http\Resources\ListAssetTypeResource;
use App\Models\User;
use App\Repositories\AssetTypeRepository;
use App\Support\AppErrorCode;
use Illuminate\Support\Facades\DB;

class AssetTypeService
{
    public function __construct(
        protected AssetTypeRepository $assetTypeRepository
    )
    {

    }

    public function getListAssetType(array $filters = [])
    {
        $listAssetType = $this->assetTypeRepository->getListAssetType($filters, [
            'asset_type.id',
            'asset_type.name',
            'asset_type.asset_type_group_id',
            'asset_type.description',
            'asset_type.maintenance_months',
        ], ['assetTypeGroup:id,name']);

        return ListAssetTypeResource::make($listAssetType)->resolve();
    }

    public function deleteAssetTypeById($id)
    {
        $assetType = $this->assetTypeRepository->find($id);
        if (is_null($assetType)) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2004
            ];
        }

        $assetType->deleted_at = date('Y-m-d H:i:s');
        $assetType->deleted_by = User::USER_ID_ADMIN;

        if (!$assetType->save()) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2005
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function findAssetType($id)
    {
        $assetType = $this->assetTypeRepository->find($id);
        if (is_null($assetType)) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2004
            ];
        }

        return [
            'success' => true,
            'data' => $assetType->toArray()
        ];
    }

    public function createAssetType($data)
    {
        $assetType = $this->assetTypeRepository->findAssetTypeByName($data['name']);
        if (!empty($assetType)) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2007
            ];
        }

        $data['created_by'] = User::USER_ID_ADMIN;
        $createAssetType = $this->assetTypeRepository->insert($data);
        if (!$createAssetType) {
            return [
              'success' => false,
              'error_code' => AppErrorCode::CODE_2006
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function updateAssetType($data, $id)
    {
        $assetType = $this->assetTypeRepository->find($id);
        if (is_null($assetType)) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2004
            ];
        }

        $data['updated_by'] = User::USER_ID_ADMIN;
        $assetType->fill($data);
        if (!$assetType->save()) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2008
            ];
        }

        return [
            'success' => true,
        ];
    }
}
