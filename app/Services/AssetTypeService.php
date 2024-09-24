<?php

namespace App\Services;

use App\Http\Resources\ListAssetTypeResource;
use App\Models\User;
use App\Repositories\AssetTypeRepository;
use App\Support\AppErrorCode;
use Illuminate\Support\Facades\Auth;
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
            'asset_type.measure',
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

        if (!$assetType->delete()) {
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

        $data['created_by'] = Auth::id();
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

        $data['updated_by'] = Auth::id();
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

    public function deleteMultipleByIds($ids)
    {
        $result = $this->assetTypeRepository->deleteMultipleByIds($ids);
        if (!$result) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2005
            ];
        }

        return [
            'success' => true,
        ];
    }
}
