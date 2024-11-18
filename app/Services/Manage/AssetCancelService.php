<?php

namespace App\Services\Manage;

use App\Repositories\Manage\AssetCancelRepository;
use App\Http\Resources\Manage\AssetCancelResource;

class AssetCancelService
{
    public function __construct(
        protected AssetCancelRepository $assetCancelRepository,
    ) {

    }

    public function list(array $filters = [])
    {
        // Thêm trạng thái tài sản hủy
        $filters['status'] = 5;

        $data = $this->assetCancelRepository->getListing(
            $filters,
            [
                'name',
                'code',
                'status',
                'user_id',
                'date',
                'location',
                'reason',
            ],
            [
                'user:id,name',
            ]
        );

        if ($data->isEmpty()) {
            return [];
        }

        return AssetCancelResource::make($data)->resolve();
    }

    // public function createAppendix(array $data)
    // {
    //     $appendix = $this->contractAppendixRepository->getFirst(['code' => $data['code']]);
    //     if (!empty($appendix)) {
    //         return [
    //             'success'    => false,
    //             'error_code' => AppErrorCode::CODE_2033,
    //         ];
    //     }

    //     $data['created_by'] = Auth::id();
    //     $data['status']     = ContractAppendix::STATUS_PENDING;
    //     $appendix           = $this->contractAppendixRepository->create($data);
    //     DB::beginTransaction();
    //     try {
    //         $insertContractMonitor = resolve(MonitorService::class)->insertMonitors($data['user_ids'], $appendix->id, Monitor::TYPE_CONTRACT_APPENDIX);
    //         if (!$insertContractMonitor) {
    //             DB::rollBack();

    //             return [
    //                 'success'    => false,
    //                 'error_code' => AppErrorCode::CODE_2025,
    //             ];
    //         }

    //         $files = $data['files'] ?? [];
    //         if (!empty($files)) {
    //             $insertContractFiles = resolve(ContractFileService::class)->insertContractFiles($files, $appendix->id);
    //             if (!$insertContractFiles) {
    //                 DB::rollBack();

    //                 return [
    //                     'success'    => false,
    //                     'error_code' => AppErrorCode::CODE_2026,
    //                 ];
    //             }
    //         }

    //         DB::commit();
    //     } catch (\Throwable $exception) {
    //         DB::rollBack();

    //         return [
    //             'success'    => false,
    //             'error_code' => AppErrorCode::CODE_1000,
    //         ];
    //     }

    //     return [
    //         'success' => true,
    //     ];
    // }

    // public function findAppendix($id)
    // {
    //     $appendix = $this->contractAppendixRepository->getFirst(['id' => $id], with: ['contractFiles', 'contractMonitors']);
    //     if (empty($appendix)) {
    //         return [];
    //     }

    //     return AppendixInfoResource::make($appendix)->resolve();
    // }

    // public function updateAppendix($data, $id)
    // {
    //     $appendix = $this->contractAppendixRepository->find($id);
    //     if (empty($appendix)) {
    //         return [
    //             'success'    => false,
    //             'error_code' => AppErrorCode::CODE_2034,
    //         ];
    //     }

    //     $data['updated_by'] = Auth::id();
    //     $appendix->fill($data);

    //     if (!$appendix->save()) {
    //         return [
    //             'success'    => false,
    //             'error_code' => AppErrorCode::CODE_2035,
    //         ];
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $updateContractMonitor = resolve(MonitorService::class)->updateMonitor($id, $data['user_ids'], Monitor::TYPE_CONTRACT_APPENDIX);
    //         if (!$updateContractMonitor) {
    //             DB::rollBack();

    //             return [
    //                 'success'    => false,
    //                 'error_code' => AppErrorCode::CODE_2032,
    //             ];
    //         }

    //         $files = $data['files'] ?? [];
    //         if (!empty($files)) {
    //             $updateContractFile = resolve(ContractFileService::class)->updateContractFiles($files, $id);
    //             if (!$updateContractFile) {
    //                 DB::rollBack();

    //                 return [
    //                     'success'    => false,
    //                     'error_code' => AppErrorCode::CODE_2026,
    //                 ];
    //             }
    //         }

    //         DB::commit();
    //     } catch (\Throwable $exception) {
    //         DB::rollBack();

    //         return [
    //             'success'    => false,
    //             'error_code' => AppErrorCode::CODE_1000,
    //         ];
    //     }

    //     return [
    //         'success' => true,
    //     ];
    // }

    // public function deleteAppendixById($id)
    // {
    //     $appendix = $this->contractAppendixRepository->find($id);
    //     if (empty($appendix)) {
    //         return [
    //             'success'    => false,
    //             'error_code' => AppErrorCode::CODE_2034,
    //         ];
    //     }

    //     $deleteContract = $appendix->update([
    //         'deleted_by' => Auth::id(),
    //         'deleted_at' => date('Y-m-d H:i:s'),
    //     ]);
    //     if (!$deleteContract) {
    //         return [
    //             'success'    => false,
    //             'error_code' => AppErrorCode::CODE_2036,
    //         ];
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $deleteContractMonitor = $this->contractMonitorRepository->deleteMonitor([
    //             'target_id' => $id,
    //             'type'      => Monitor::TYPE_CONTRACT_APPENDIX,
    //         ]);
    //         if (!$deleteContractMonitor) {
    //             DB::rollBack();

    //             return [
    //                 'success'    => false,
    //                 'error_code' => AppErrorCode::CODE_2029,
    //             ];
    //         }

    //         $this->contractFileRepository->deleteByContractIds($id);
    //         DB::commit();
    //     } catch (\Throwable $exception) {
    //         DB::rollBack();

    //         return [
    //             'success'    => false,
    //             'error_code' => AppErrorCode::CODE_1000,
    //         ];
    //     }

    //     return [
    //         'success' => true,
    //     ];
    // }

    // public function deleteAppendixMultiple($ids)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $this->contractAppendixRepository->deleteMultipleByIds($ids);
    //         $this->contractMonitorRepository->deleteMonitor([
    //             'target_id' => $ids,
    //             'type'      => Monitor::TYPE_CONTRACT_APPENDIX,
    //         ]);
    //         $this->contractFileRepository->deleteByContractIds($ids);
    //         DB::commit();
    //     } catch (\Throwable $exception) {
    //         DB::rollBack();

    //         return [
    //             'success'    => false,
    //             'error_code' => AppErrorCode::CODE_1000,
    //         ];
    //     }

    //     return [
    //         'success' => true,
    //     ];
    // }
}
