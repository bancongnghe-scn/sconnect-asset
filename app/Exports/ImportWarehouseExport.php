<?php

namespace App\Exports;

use App\Models\ImportWarehouse;
use App\Repositories\ImportWarehouse\ImportWarehouseRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ImportWarehouseExport implements FromArray, WithHeadings
{
    protected $ids;
    protected $importWarehouseRepository;
    protected $userRepository;

    public function __construct($ids)
    {
        $this->ids                       = $ids;
        $this->importWarehouseRepository = new ImportWarehouseRepository();
        $this->userRepository            = new UserRepository();
    }

    public function headings(): array
    {
        return ['Mã', 'Tên phiếu', 'Mô tả', 'Trạng thái', 'Người nhập', 'Thời gian nhập'];
    }

    public function array(): array
    {
        $filters = [];
        if (!empty($this->ids)) {
            $filters['id'] = $this->ids;
        }

        $data    = $this->importWarehouseRepository->getListing($filters, ['code', 'name', 'description', 'status', 'created_by', 'created_at']);
        $userIds = $data->pluck('created_by')->toArray();
        $users   = [];
        if (!empty($userIds)) {
            $users = $this->userRepository->getListing(['id' => $userIds], ['id', 'name'])->keyBy('id')->toArray();
        }

        $importWarehouse = [];
        foreach ($data as $value) {
            $importWarehouse[] = [
                'code'        => $value->code,
                'name'        => $value->name,
                'description' => $value->description,
                'status'      => ImportWarehouse::STATUS_NAME[$value->status],
                'created_by'  => $users[$value->created_by]['name'] ?? '',
                'created_at'  => Carbon::parse($value->created_at)->format('d-m-Y'),
            ];
        }

        return $importWarehouse;
    }
}
