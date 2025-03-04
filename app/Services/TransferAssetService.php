<?php

namespace App\Services;

use App\Models\TransferAsset;
use App\Repositories\MoveAssetUserRepository;
use App\Repositories\TransferAssetRepository;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Facades\Auth;

class TransferAssetService
{
    public function __construct(
        protected TransferAssetRepository $transferAssetRepository,
        protected MoveAssetUserRepository $moveAssetUserRepository,
    ) {
    }

    public function assetTransferFormCompany($type, array $data)
    {
        $userIdFrom = TransferAsset::TYPE_RECOVERY == $type ? $data['user_id_from'] : null;
        $orgIdFrom  = TransferAsset::TYPE_RECOVERY == $type ? $data['org_id_from'] : null;
        $userIdTo   = TransferAsset::TYPE_ALLOCATION == $type ? $data['user_id_to'] : null;
        $orgIdTo    = TransferAsset::TYPE_ALLOCATION == $type ? $data['org_id_to'] : null;

        $transferAsset = $this->transferAssetRepository->create([
            'type'        => $type,
            'user_id'     => $userIdTo,
            'org_id'      => $orgIdTo,
            'to_user_id'  => $userIdFrom,
            'to_org_id'   => $orgIdFrom,
            'description' => $data['description'] ?? null,
            'created_at'  => $data['created_at'] ?? date('Y-m-d H:i:s'),
            'created_by'  => $data['created_by'] ?? Auth::id(),
        ]);

        $insert = $this->moveAssetUserRepository->insert([
            'type'              => $type,
            'user_id'           => $userIdFrom,
            'org_id'            => $orgIdFrom,
            'asset_id'          => $data['asset_id'],
            'org_id_after'      => $orgIdTo,
            'user_id_after'     => $userIdTo,
            'description'       => $data['description'] ?? null,
            'transfer_asset_id' => $transferAsset->id,
            'created_at'        => $data['created_at'] ?? date('Y-m-d H:i:s'),
        ]);
        if (!$insert) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

        return [
            'success' => true,
        ];
    }
}
