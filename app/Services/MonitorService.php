<?php

namespace App\Services;

use App\Models\Monitor;
use App\Repositories\MonitorRepository;

class MonitorService
{
    public function __construct(
        protected MonitorRepository $contractMonitorRepository,
    ) {

    }

    public function updateMonitor($contractId, array $userIds, $type = Monitor::TYPE_CONTRACT)
    {
        $contractMonitor = $this->contractMonitorRepository->getListing([
            'target_id' => $contractId,
            'type'      => $type,
        ]);
        $oldUserIds      = $contractMonitor->pluck('user_id')->toArray();
        $addUserIds      = array_diff($userIds, $oldUserIds);
        if (!empty($addUserIds)) {
            foreach ($addUserIds as $userId) {
                $dataSave[] = [
                    'type'      => $type,
                    'target_id' => $contractId,
                    'user_id'   => $userId,
                ];
            }

            $save = $this->contractMonitorRepository->insert($dataSave);
            if (!$save) {
                return false;
            }
        }

        $removeUserIds = array_diff($oldUserIds, $userIds);
        if (!empty($removeUserIds)) {
            $this->contractMonitorRepository->deleteMonitor([
                'target_id' => $contractId,
                'user_id'   => $removeUserIds,
                'type'      => $type,
            ]);
        }

        return true;
    }

    public function insertMonitors(array $userIds, $contractId, $type = Monitor::TYPE_CONTRACT)
    {
        $dataCreateContractMonitor = [];
        foreach ($userIds as $userId) {
            $dataCreateContractMonitor[] = [
                'type'      => $type,
                'target_id' => $contractId,
                'user_id'   => $userId,
            ];
        }

        return $this->contractMonitorRepository->insert($dataCreateContractMonitor);
    }
}
