<?php

namespace App\Services\Inventory;

use App\Http\Resources\Inventory\AssetRepairResource;
use App\Models\Asset;
use App\Models\AssetRepair;
use App\Repositories\AssetRepository;
use App\Repositories\Inventory\AssetRepairRepository;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Facades\DB;

class AssetRepairService
{
    public function __construct(
        protected AssetRepairRepository $assetRepairRepository,
        protected AssetRepository $assetRepository,
    ) {

    }

    public function updateAssetRepair($data)
    {
        $dataUpdate = [];
        if (!empty($data['assets'])) {
            $assets   = $data['assets'];
            $assetIds = array_column($assets, 'id');

            foreach ($assets as $asset) {
                $addressRepair = ($data['location'] == AssetRepair::ADDRESS_NAME[AssetRepair::ADDRESS_COMPANY])
                    ? AssetRepair::ADDRESS_COMPANY
                    : AssetRepair::ADDRESS_SUPPLIER;

                $performerSupplier = (AssetRepair::ADDRESS_COMPANY === $addressRepair)
                    ? $data['performer_id']
                    : $data['supplier_id'];


                $dataUpdate[] = [
                    'asset_id'              => $asset['id'],
                    'date_repair'           => $data['date_repair'],
                    'date_repaired'         => $data['date_repaired'],
                    'address'               => $data['address'] ?? '',
                    'address_repair'        => $addressRepair,
                    'performer_supplier'    => $performerSupplier,
                    'cost_repair'           => $asset['cost_repair'],
                    'note_repair'           => $asset['note_repair'],
                    'created_at'            => date('Y-m-d H:i:s'),
                    'status'                => AssetRepair::STATUS_NOT_COMPLETE,
                ];
            }

            DB::beginTransaction();
            try {
                foreach ($dataUpdate as $assetRepair) {
                    $insertInforAssetRepair = $this->assetRepairRepository->insert($assetRepair);
                    if (!$insertInforAssetRepair) {
                        DB::rollBack();

                        return [
                            'success'       => false,
                            'error_code'    => AppErrorCode::CODE_5009,
                        ];
                    }
                }

                $updateAsset = $this->assetRepository->changeStatusAsset($assetIds, Asset::STATUS_REPAIR);
                if (!$updateAsset) {
                    DB::rollBack();

                    return [
                        'success'       => false,
                        'error_code'    => AppErrorCode::CODE_5001,
                    ];
                }
            } catch (\Exception $e) {
                DB::rollBack();

                return [
                    'success'       => false,
                    'error_code'    => AppErrorCode::CODE_5001,
                ];
            }
            DB::commit();
        }

        return [
            'success' => true,
        ];
    }

    public function getListAssetRepair(array $filter = [])
    {
        $data = $this->assetRepairRepository->getListAssetRepair(
            $filter,
            [
                'id',
                'asset_id',
                'date_repair',
                'address',
                'address_repair',
                'performer_supplier',
                'cost_repair',
                'status',
            ],
            [
                'asset:id,name,code,status,reason',
            ]
        );

        if ($data->isEmpty()) {
            return [];
        }

        return AssetRepairResource::make($data)->resolve();
    }

    public function getAssetRepairById($id)
    {
        $assetRepair = $this->assetRepairRepository
            ->find($id)
            ->load('asset:id,name,code,status,reason,price');

        if (empty($assetRepair)) {
            return [];
        }

        return [$assetRepair->toArray()];
    }

    public function updateMultiAssetRepaired($data)
    {
        $assetsRepaired = $data['assets'];
        $dateRepaired   = $data['date_repaired'];

        DB::beginTransaction();
        try {
            foreach ($assetsRepaired as $assetRepair) {
                $dataAssetRepaired = [
                    'date_repair'           => $assetRepair['date_repair'],
                    'cost_repair'           => $assetRepair['cost_repair'],
                    'note_repair'           => $assetRepair['note_repair'],
                    'address_repair'        => $assetRepair['address_repair'],
                    'performer_supplier'    => $assetRepair['performer_supplier'],
                    'address'               => $assetRepair['address'],
                    'date_repaired'         => $dateRepaired,
                    'status'                => AssetRepair::STATUS_COMPLETE,
                ];

                $updateAssetRepaired = $this->assetRepairRepository->updateAssetRepaired($assetRepair['id'], $dataAssetRepaired);
                if (!$updateAssetRepaired) {
                    DB::rollBack();

                    return [
                        'success'       => false,
                        'error_code'    => AppErrorCode::CODE_5010,
                    ];
                }

                // Status asset: repair -> active
                $updateStatusAsset = $this->assetRepository->changeStatusAsset($assetRepair['asset_id'], Asset::STATUS_ACTIVE);
                if (!$updateStatusAsset) {
                    DB::rollBack();

                    return [
                        'success'       => false,
                        'error_code'    => AppErrorCode::CODE_5001,
                    ];
                }
            }

            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Exception $e) {

            DB::rollBack();

            return [
                'success'       => false,
                'error_code'    => AppErrorCode::CODE_5010,
            ];
        }
    }

    public function getMultiAssetRepairById($ids)
    {
        $multiAssetRepair = $this->assetRepairRepository
            ->getMultiAssetRepairById(
                $ids,
                [
                    'id',
                    'asset_id',
                    'date_repair',
                    'address',
                    'address_repair',
                    'performer_supplier',
                    'cost_repair',
                    'note_repair',
                    'status',
                ],
                [
                    'asset:id,name,code,price',
                ]
            );

        if (empty($multiAssetRepair)) {
            return [];
        }

        return $multiAssetRepair->all();
    }
}
