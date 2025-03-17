<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\TransferAsset;
use App\Models\User;
use App\Repositories\AssetRepository;
use App\Repositories\AssetTypeRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\UserRepository;
use App\Services\AssetService;
use App\Services\TransferAssetService;
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
    public $listError                         = [];
    private $maxRows                          = 1000;
    private $columnOrganizationNameAfter      = 27; // cot don vi dang su dung
    private $columnRecentMaintenanceDate      = 35; // thoi gian bao duong gan nhat
    private $columnNextMaintenanceDate        = 36; // thoi gian bao duong tiep theo
    private $columnOrganizationNameBefore     = 20; // cot don vi cap phat truoc do
    private $columnDeliveryDateBefore         = 22; // cot ngay ban giao truoc
    private $columnDeliveryDateAfter          = 29; // cot ngay ban giao sau
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

        $organizationName = $collection->pluck($this->columnOrganizationNameAfter)->unique()->toArray();
        $listOrganization = [];
        if (!empty($organizationName)) {
            $listOrganization = $this->organizationRepository->getInfoOrganizationByFilters([])
                ->mapWithKeys(function ($organization) {
                    return [Str::slug($organization->name) => $organization];
                });
        }

        $assetTypeName = $collection->pluck('ten_tai_san')->unique()->toArray();
        $listAssetType = $this->assetTypeRepository->getAssetTypeByName($assetTypeName)
            ->mapWithKeys(function ($assetType) {
                return [Str::slug($assetType->name) => $assetType];
            });
        $supplierName  = $collection->pluck('thong_tin_ncc_ten_ncc_dia_chi_sdt')->unique()->toArray();
        $listSupplier  = $this->supplierRepository->getListSupplerByName($supplierName)
            ->mapWithKeys(function ($supplier) {
                return [Str::slug($supplier->name) => $supplier];
            });

        $listCodeAssets = $collection->pluck('ma_tai_san')->toArray();
        $listAssets     = $this->assetRepository->getListing(['code' => $listCodeAssets])->keyBy('code');
        foreach ($array as $stt => $row) {
            if ($stt < 1 || is_null($row['stt'])) {
                continue;
            }
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

            if (!is_null($row['nguoi_su_dung_lien_ke_truoc_khi_ban_giao_cho_nguoi_moi']) && is_null($row[$this->columnOrganizationNameBefore])) {
                $this->setError($row['ma_tai_san'], 'BU/ Ban/ Học viện của người sử dụng liền kề không tồn tại !');
                continue;
            }

            if (!is_null($row['nguoi_su_dung_hien_tai']) && is_null($row[$this->columnOrganizationNameAfter])) {
                $this->setError($row['ma_tai_san'], 'BU/ Ban/ Học viện của người sử dụng hiện tại không tồn tại !');
                continue;
            }

            $transferAssetService = resolve(TransferAssetService::class);
            $assetService         = resolve(AssetService::class);
            DB::beginTransaction();
            try {
                // luu thong tin tai san
                $dataAsset = [
                    'code'                    => $row['ma_tai_san'],
                    'asset_type_id'           => $listAssetType[Str::slug($row['ten_tai_san'])]['id'],
                    'description'             => $row['thanh_phan_cua_tai_san'] ?? null,
                    'name'                    => $row['mo_ta_chi_tiet_ve_tai_san'],
                    'price'                   => (int) $row['don_gia'],
                    'date_purchase'           => $this->formatDate($row['ngay_giao_hang_tren_hoa_don']),
                    'warranty_months'         => (int) $row['thoi_gian_bao_hanh_thang'],
                    'depreciation_months'     => (int) $row['don_gia'] > Asset::PRICE_DEPRECIATION ? Asset::MONTH_DEPRECIATION_36 : Asset::MONTH_DEPRECIATION_12,
                    'supplier_id'             => $listSupplier[Str::slug($row['thong_tin_ncc_ten_ncc_dia_chi_sdt'])]['id'],
                    'user_id'                 => $listUser[$row['nguoi_su_dung_hien_tai']]['id'] ?? null,
                    'status'                  => is_null($row['nguoi_su_dung_hien_tai']) ? Asset::STATUS_PENDING : Asset::STATUS_ACTIVE,
                    'organization_id'         => $listOrganization[Str::slug($row[$this->columnOrganizationNameBefore])]['id'] ?? null,
                    'created_by'              => User::USER_ADMIN,
                    'recent_maintenance_date' => $this->formatDate($row[$this->columnRecentMaintenanceDate]),
                    'next_maintenance_date'   => $this->formatDate($row[$this->columnNextMaintenanceDate]),
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

                // luu thong tin cap phat thu hoi
                $asset                   = $this->assetRepository->create($dataAsset);
                $assetService->generalQrCodeAsset($asset->id);
                $userCodeAllocationFirst = $row['nguoi_su_dung_lien_ke_truoc_khi_ban_giao_cho_nguoi_moi'];
                $userCodeAllocationLast  = $row['nguoi_su_dung_hien_tai'];

                // neu co cap phat ban dau
                if (!empty($userCodeAllocationFirst)) {
                    $allocation = $transferAssetService->assetTransferFormCompany(TransferAsset::TYPE_ALLOCATION, [
                        'user_id_to' => $listUser[$userCodeAllocationFirst]['id'] ?? null,
                        'org_id_to'  => $listOrganization[Str::slug($row[$this->columnOrganizationNameBefore])]['id'] ?? null,
                        'asset_id'   => $asset->id,
                        'created_at' => $this->formatDate($row[$this->columnDeliveryDateBefore]),
                    ]);
                    if (!$allocation['success']) {
                        DB::rollBack();
                        $this->setError($row['ma_tai_san'], 'Cấp phát thất bại !');
                        continue;
                    }
                    // thu hoi + cap phat
                    if (!empty($userCodeAllocationLast)) {
                        // thu hoi lai tai san
                        $allocation = $transferAssetService->assetTransferFormCompany(TransferAsset::TYPE_RECOVERY, [
                            'user_id_from' => $listUser[$userCodeAllocationFirst]['id'] ?? null,
                            'org_id_from'  => $listOrganization[Str::slug($row[$this->columnOrganizationNameBefore])]['id'] ?? null,
                            'asset_id'     => $asset->id,
                            'created_at'   => $this->formatDate($row[$this->columnDeliveryDateAfter]),
                        ]);
                        if (!$allocation['success']) {
                            DB::rollBack();
                            $this->setError($row['ma_tai_san'], 'Thu hồi thất bại !');
                            continue;
                        }
                    }
                }

                if (!empty($userCodeAllocationLast)) {
                    $allocation = $transferAssetService->assetTransferFormCompany(TransferAsset::TYPE_ALLOCATION, [
                        'user_id_to' => $listUser[$userCodeAllocationLast]['id'] ?? null,
                        'org_id_to'  => $listOrganization[Str::slug($row[$this->columnOrganizationNameAfter])]['id'] ?? null,
                        'asset_id'   => $asset->id,
                        'created_at' => $this->formatDate($row[$this->columnDeliveryDateAfter]),
                    ]);
                    if (!$allocation['success']) {
                        DB::rollBack();
                        $this->setError($row['ma_tai_san'], 'Thu hồi thất bại !');
                        continue;
                    }
                }

                DB::commit();

            } catch (\Throwable $exception) {
                DB::rollBack();
                $this->setError($row['ma_tai_san'], $exception->getMessage());
            }
        }
    }

    private function setError($key, $message)
    {
        $this->listError[$key] = $message;
    }

    private function formatDate($date)
    {
        if (is_null($date)) {
            return null;
        }

        return is_numeric($date)
            ? Carbon::instance(Date::excelToDateTimeObject($date))->format('Y-m-d')
            : Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d') ?? null;
    }
}
