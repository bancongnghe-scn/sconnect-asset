<?php

namespace App\Imports;

use App\Repositories\AssetRepository;
use App\Repositories\AssetTypeRepository;
use App\Repositories\SupplierRepository;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssetImport implements ToCollection, WithHeadingRow
{
    public $error     = false;
    public $listError = [];
    private $maxRows  = 1000;
    protected $assetRepository;
    protected $assetTypeRepository;
    protected $supplierRepository;

    public function __construct(
    ) {
        $this->assetRepository     = new AssetRepository();
        $this->assetTypeRepository = new AssetTypeRepository();
        $this->supplierRepository  = new SupplierRepository();
    }

    public function collection(Collection $collection)
    {
        if (count($collection) > $this->maxRows) {
            $this->error       = true;
            $this->listError[] = 'Kích thước file không được quá 1000 dòng';

            return;
        }
        $assetTypeName = $collection->pluck('ten_tai_san')->unique()->toArray();
        if (empty($assetTypeName)) {
            $this->listError[] = 'Không có loại tài sản nào tồn tại, vui lòng tạo trước';

            return;
        }
        $supplierName = $collection->pluck('thong_tin_ncc_ten_ncc_dia_chi_sdt')->unique()->toArray();
        if (empty($supplierName)) {
            $this->listError[] = 'Không có NCC nào tồn tại, vui lòng tạo trước';
        }

        $listAssetType = $this->assetTypeRepository->getAssetTypeByName($assetTypeName)->keyBy('name');
        $listSupplier  = $this->supplierRepository->getListSupplerByName($supplierName)->keyBy('name');

        $listCodeAssets = $collection->pluck('ma_tai_san')->toArray();
        $listAssets     = $this->assetRepository->getListing(['code' => $listCodeAssets])->keyBy('code');
        $data           = [];
        foreach ($collection as $stt => $row) {
            if ($stt < 2) {
                continue;
            }

        }
    }
}
