<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\User;
use App\Repositories\AssetRepository;
use App\Repositories\AssetTypeRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\Service\Repositories\OrganizationRepository;
use Modules\Service\Repositories\OrgInfoRepository;
use Modules\Service\Repositories\UserGeneralRepository;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AssetImport implements ToArray, SkipsEmptyRows, WithHeadingRow
{
    public $listError                    = [];
    private $maxRows                     = 1000;
    private $columnOrganizationName      = 27;
    private $columnRecentMaintenanceDate = 35;
    private $columnNextMaintenanceDate   = 36;
    protected $assetRepository;
    protected $assetTypeRepository;
    protected $supplierRepository;
    protected $userRepository;
    protected $organizationRepository;
    protected $userGeneralRepository;
    protected $orgInfoRepository;

    public function __construct(
    ) {
        $this->assetRepository        = new AssetRepository();
        $this->assetTypeRepository    = new AssetTypeRepository();
        $this->supplierRepository     = new SupplierRepository();
        $this->userRepository         = new UserRepository();
        $this->organizationRepository = new OrganizationRepository();
        $this->userGeneralRepository  = new UserGeneralRepository();
        $this->orgInfoRepository      = new OrgInfoRepository();
    }

    public function array(array $array)
    {
        $collection = collect($array);
        if (count($collection) > $this->maxRows) {
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

            return;
        }

        $userCodeCurrent = $collection->pluck('nguoi_su_dung_hien_tai')->unique()->toArray();
        $userCodeAfter   = $collection->pluck('nguoi_su_dung_lien_ke_truoc_khi_ban_giao_cho_nguoi_moi')->unique()->toArray();
        $userCode        = array_merge($userCodeAfter, $userCodeCurrent);
        $listUser        = [];
        if (!empty($userCode)) {
            $listUser = $this->userRepository->getListing(['code' => $userCode])->keyBy('code');
        }

        $listUserGeneral = [];
        if ($listUser->isNotEmpty()) {
            $userIds         = $listUser->pluck('id')->toArray();
            $listUserGeneral = $this->userGeneralRepository->getListing(['user_id' => $userIds])->keyBy('user_id');
        }

        $organizationName = $collection->pluck($this->columnOrganizationName)->unique()->toArray();
        $listOrganization = [];
        if (!empty($organizationName)) {
            $listOrganization = $this->organizationRepository->getInfoOrganizationByFilters([])
                ->mapWithKeys(function ($organization) {
                    return [Str::slug($organization->name) => $organization];
                });
        }

        $listAssetType = $this->assetTypeRepository->getAssetTypeByName($assetTypeName)
            ->mapWithKeys(function ($assetType) {
                return [Str::slug($assetType->name) => $assetType];
            });

        $listSupplier  = $this->supplierRepository->getListSupplerByName($supplierName)
            ->mapWithKeys(function ($supplier) {
                return [Str::slug($supplier->name) => $supplier];
            });

        $listCodeAssets = $collection->pluck('ma_tai_san')->toArray();
        $listAssets     = $this->assetRepository->getListing(['code' => $listCodeAssets])->keyBy('code');
        foreach ($array as $stt => $row) {
            if ($stt < 2 || is_null($row['stt'])) {
                continue;
            }
            //            foreach ($row as $key => $value) {
            //                if ($value === 'SCN1106') {
            //                    dd($row);
            //                }
            //            }
            $validator = Validator::make($row, [
                'ma_tai_san'                => 'required',
                'ten_tai_san'               => 'required|string',
                'mo_ta_chi_tiet_ve_tai_san' => 'required|string',
            ]);

            if ($validator->fails()) {
                $this->setError($row['ma_tai_san'] ?? $row['mo_ta_chi_tiet_ve_tai_san'], $validator->errors()->all());
                continue;
            }

            if (!empty($listAssets[$row['ma_tai_san']])) {
                $this->setError($row['ma_tai_san'], 'tài sản đã tồn tại !');
                continue;
            }

            if (empty($listAssetType[Str::slug($row['ten_tai_san'])])) {
                $this->setError($row['ma_tai_san'], 'LTS chưa tồn tại, vui lòng tạo LTS !');
                continue;
            }

            if (!is_null($row['thong_tin_ncc_ten_ncc_dia_chi_sdt']) && empty($listSupplier[Str::slug($row['thong_tin_ncc_ten_ncc_dia_chi_sdt'])])) {
                $this->setError($row['ma_tai_san'], 'NCC chưa tồn tại, vui lòng tạo NCC !');
                continue;
            }

            DB::beginTransaction();
            try {
                $dataAsset = [
                    'code'          => $row['ma_tai_san'],
                    'asset_type_id' => $listAssetType[Str::slug($row['ten_tai_san'])]['id'],
                    'description'   => $row['thanh_phan_cua_tai_san'],
                    'name'          => $row['mo_ta_chi_tiet_ve_tai_san'],
                    'price'         => (int) $row['don_gia'],
                    'date_purchase' => !is_null($row['ngay_giao_hang_tren_hoa_don']) ?
                        Carbon::createFromFormat('d/m/Y', $row['ngay_giao_hang_tren_hoa_don'])->format('Y-m-d') : null,
                    'warranty_months'         => (int) $row['thoi_gian_bao_hanh_thang'],
                    'depreciation_months'     => (int) $row['don_gia'] > Asset::PRICE_DEPRECIATION ? Asset::MONTH_DEPRECIATION_36 : Asset::MONTH_DEPRECIATION_12,
                    'supplier_id'             => $listSupplier[Str::slug($row['thong_tin_ncc_ten_ncc_dia_chi_sdt'])]['id'] ?? null,
                    'user_id'                 => $listUser[$row['nguoi_su_dung_hien_tai']]['id'] ?? null,
                    'status'                  => is_null($row['nguoi_su_dung_hien_tai']) ? Asset::STATUS_PENDING : Asset::STATUS_ACTIVE,
                    'organization_id'         => $listOrganization[$row[$this->columnOrganizationName]]['id'] ?? null,
                    'created_by'              => User::USER_ADMIN,
                    'recent_maintenance_date' => isset($row[$this->columnRecentMaintenanceDate]) && is_numeric($row[$this->columnRecentMaintenanceDate])
                            ? Carbon::instance(Date::excelToDateTimeObject($row[$this->columnRecentMaintenanceDate]))->format('Y-m-d')
                            : null,
                    'next_maintenance_date' => isset($row[$this->columnNextMaintenanceDate]) && is_numeric($row[$this->columnNextMaintenanceDate])
                        ? Carbon::instance(Date::excelToDateTimeObject($row[$this->columnNextMaintenanceDate]))->format('Y-m-d')
                        : null,
                ];

                if (!empty($dataAsset['user_id'])) {
                    $dataAsset['location'] = $listUserGeneral[$dataAsset['user_id']]['workplace_id'] ?? null;
                    $dataAsset['status']   = Asset::STATUS_ACTIVE;
                } else {
                    if (is_null($dataAsset['organization_id'])) {
                        $dataAsset['location'] = Asset::LOCATION_WAREHOUSE;
                        $dataAsset['status']   = Asset::STATUS_PENDING;
                    } else {
                        $dataAsset['location'] = $this->orgInfoRepository->find($dataAsset['organization_id'])?->branch;
                        $dataAsset['status']   = Asset::STATUS_ACTIVE;
                    }
                }

                $asset                   = $this->assetRepository->create($dataAsset);
                $userCodeAllocationFirst = $row['nguoi_su_dung_lien_ke_truoc_khi_ban_giao_cho_nguoi_moi'];
                $userCodeAllocationLast  = $row['nguoi_su_dung_hien_tai'];

                // neu co cap phat ban dau
                if (!empty($userCodeAllocationFirst)) {

                }

            } catch (\Throwable $exception) {
                $row['ma_tai_san'] . ' => ' .$exception->getMessage();
            }
        }

        //        if (!empty($data)) {
        //            $this->assetRepository->insert($data);
        //        }
    }

    private function setError($key, $message)
    {
        $this->listError[$key] = $message;
    }
}
