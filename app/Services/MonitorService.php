<?php

namespace App\Services;

use App\Models\Monitor;
use App\Repositories\MonitorRepository;

class MonitorService
{
    public function __construct(
        protected MonitorRepository $monitorRepository,
    ) {

    }

    public function updateMonitor($targetId, array $userIds, $type = Monitor::TYPE_CONTRACT)
    {
        $monitors = $this->monitorRepository->getListing([
            'target_id' => $targetId,
            'type'      => $type,
        ]);
        $oldUserIds      = $monitors->pluck('user_id')->toArray();
        $addUserIds      = array_diff($userIds, $oldUserIds);
        if (!empty($addUserIds)) {
            foreach ($addUserIds as $userId) {
                $dataSave[] = [
                    'type'      => $type,
                    'target_id' => $targetId,
                    'user_id'   => $userId,
                ];
            }

            $save = $this->monitorRepository->insert($dataSave);
            if (!$save) {
                return false;
            }
        }

        $removeUserIds = array_diff($oldUserIds, $userIds);
        if (!empty($removeUserIds)) {
            $this->monitorRepository->deleteMonitor([
                'target_id' => $targetId,
                'user_id'   => $removeUserIds,
                'type'      => $type,
            ]);
        }

        return true;
    }

    public function insertMonitors(array $userIds, $targetId, $type = Monitor::TYPE_CONTRACT)
    {
        $dataCreateContractMonitor = [];
        foreach ($userIds as $userId) {
            $dataCreateContractMonitor[] = [
                'type'      => $type,
                'target_id' => $targetId,
                'user_id'   => $userId,
            ];
        }

        return $this->monitorRepository->insert($dataCreateContractMonitor);
    }
}
