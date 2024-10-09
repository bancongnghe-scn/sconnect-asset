<?php

namespace App\Services;

use App\Repositories\IndustryRepository;
use App\Support\AppErrorCode;
use Illuminate\Support\Facades\Auth;

class IndustryService
{
    public function __construct(
        protected IndustryRepository $industryRepository,
    ) {

    }

    public function getListIndustry(array $filters = [])
    {
        $listIndustry = $this->industryRepository->getListIndustry($filters, [
            'industries.id',
            'industries.name',
            'industries.description',
        ]);

        return $listIndustry->toArray();
    }

    public function deleteIndustryById($id)
    {
        $industry = $this->industryRepository->find($id);
        if (is_null($industry)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2004,
            ];
        }

        if (!$industry->delete()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2005,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function findIndustry($id)
    {
        $industry = $this->industryRepository->find($id);
        if (is_null($industry)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2011,
            ];
        }

        return [
            'success' => true,
            'data'    => $industry->toArray(),
        ];
    }

    public function createIndustry($data)
    {
        $industry = $this->industryRepository->findIndustryByName($data['name']);
        if (!empty($industry)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2009,
            ];
        }

        $data['created_by'] = Auth::id();
        $createIndustry     = $this->industryRepository->insert($data);
        if (!$createIndustry) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2010,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function updateIndustry($data, $id)
    {
        $industry = $this->industryRepository->find($id);
        if (is_null($industry)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2011,
            ];
        }

        $data['updated_by'] = Auth::id();
        $industry->fill($data);
        if (!$industry->save()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2012,
            ];
        }

        return [
            'success' => true,
        ];
    }
}
