<?php

namespace App\Services;

use App\Repositories\ContractFileRepository;

class ContractFileService
{
    public function __construct(
        protected ContractFileRepository $contractFileRepository,
    ) {

    }

    public function insertContractFiles(array $files, $contractId)
    {
        $contractFiles = [];
        foreach ($files as $file) {
            $path            = $file->store('uploads', 'public');
            $contractFiles[] = [
                'contract_id' => $contractId,
                'file_url'    => $path,
                'file_name'   => $file->getClientOriginalName(),
            ];
        }

        return $this->contractFileRepository->insert($contractFiles);
    }

    public function updateContractFiles($filesUpload, $contractId)
    {
        $contractFile = $this->contractFileRepository->getListing([
            'contract_id' => $contractId,
        ]);

        $oldFileNames    = $contractFile->pluck('file_name')->toArray();
        $uploadFileNames = [];
        $newFiles        = [];
        foreach ($filesUpload as $file) {
            $fileName = is_array($file) ? $file['name'] : $file->getClientOriginalName();
            if (!in_array($fileName, $oldFileNames)) {
                $newFiles[] = $file;
            }
            $uploadFileNames[] = $fileName;
        }
        $removeFileNames = array_diff($oldFileNames, $uploadFileNames);
        if (!empty($removeFileNames)) {
            $this->contractFileRepository->deleteFilesOfContract($removeFileNames, $contractId);
        }
        if (!empty($newFiles)) {
            return $this->insertContractFiles($newFiles, $contractId);
        }

        return true;
    }
}
