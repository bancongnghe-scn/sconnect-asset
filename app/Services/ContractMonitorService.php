<?php

namespace App\Services;

use App\Repositories\ContractMonitorRepository;

class ContractMonitorService
{
    public function __construct(
        protected ContractMonitorRepository $contractMonitorRepository,
    ) {

    }

    public function updateFollowersOfContract($contractId, array $userIds)
    {
        $contractMonitor = $this->contractMonitorRepository->getListing(['contract_id' => $contractId]);
        $oldUserIds      = $contractMonitor->pluck('user_id')->toArray();
        $addUserIds      = array_diff($userIds, $oldUserIds);
        if (!empty($addUserIds)) {
            foreach ($addUserIds as $userId) {
                $dataSave[] = [
                    'user_id'     => $userId,
                    'contract_id' => $contractId,
                ];
            }

            $save = $this->contractMonitorRepository->insert($dataSave);
            if (!$save) {
                return false;
            }
        }

        $removeUserIds = array_diff($oldUserIds, $userIds);
        if (!empty($removeUserIds)) {
            $this->contractMonitorRepository->deleteFlowersContract($contractId, $removeUserIds);
        }

        return true;
    }

    public function insertContractMonitors(array $userIds, $contractId)
    {
        $dataCreateContractMonitor = [];
        foreach ($userIds as $userId) {
            $dataCreateContractMonitor[] = [
                'contract_id' => $contractId,
                'user_id'     => $userId,
            ];
        }

        return $this->contractMonitorRepository->insert($dataCreateContractMonitor);
    }
}
