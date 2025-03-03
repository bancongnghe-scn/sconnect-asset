<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\User;
use App\Repositories\AssetRepository;
use App\Repositories\AssetTypeRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\BeforeImport;
use Modules\Service\Repositories\OrganizationRepository;
use Modules\Service\Repositories\OrgInfoRepository;
use Modules\Service\Repositories\UserGeneralRepository;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AssetImport implements ToCollection, WithHeadingRow, WithEvents
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

    public static function beforeImport(BeforeImport $event)
    {
        $spreadsheet = $event->getDelegate(); // Lấy đúng đối tượng Spreadsheet

        // Xóa cache công thức để đảm bảo đọc giá trị thực tế
        Calculation::getInstance($spreadsheet)->flushInstance();
        dd(1); // Kiểm tra xem có chạy vào đây không
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => [self::class, 'beforeImport'],
        ];
    }

    public function collection(Collection $collection)
    {
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

        $userCode = $collection->pluck('nguoi_su_dung_hien_tai')->unique()->toArray();
        $listUser = [];
        if (!empty($userCode)) {
            $listUser = $this->userRepository->getListing(['code' => $userCode])->keyBy('code');
        }

        $listUserGeneral = [];
        if ($listUser->isNotEmpty()) {
            $userIds         = $listUser->pluck('id')->toArray();
            $listUserGeneral = $this->userGeneralRepository->getListing(['user_id' => $userIds])->keyBy('user_id');
        }

        $organizationName = $collection->pluck($this->columnOrganizationName)->unique()->toArray();
        $listOrganization = collect();
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
        $data           = [];
        foreach ($collection as $stt => $row) {
            if ($stt < 2) {
                continue;
            }
            dd($row['stt']);
            try {
                if (empty($row['ma_tai_san'])) {
                    $this->listError[] = $this->getMessageError($row['mo_ta_chi_tiet_ve_tai_san'], 'cột mã tài sản không được để trống !');
                    continue;
                }

                if (!empty($listAssets[$row['ma_tai_san']])) {
                    $this->listError[] = $this->getMessageError($row['ma_tai_san'], 'tài sản đã tồn tại !');
                    continue;
                }

                if (!is_null($row['ten_tai_san']) && empty($listAssetType[Str::slug($row['ten_tai_san'])])) {
                    $this->listError[] = $this->getMessageError($row['ma_tai_san'], 'LTS chưa tồn tại, vui lòng tạo LTS !');
                    continue;
                }

                if (!is_null($row['thong_tin_ncc_ten_ncc_dia_chi_sdt']) && empty($listSupplier[Str::slug($row['thong_tin_ncc_ten_ncc_dia_chi_sdt'])])) {
                    $this->listError[] = $this->getMessageError($row['ma_tai_san'], 'NCC chưa tồn tại, vui lòng tạo NCC !');
                    continue;
                }

                $value = [
                    'code'          => $row['ma_tai_san'],
                    'asset_type_id' => $listAssetType[Str::slug($row['ten_tai_san'])]['id'] ?? null,
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

                if (!empty($value['user_id'])) {
                    $value['location'] = $listUserGeneral[$value['user_id']]['workplace_id'] ?? null;
                    $value['status']   = Asset::STATUS_ACTIVE;
                } else {
                    if (is_null($value['organization_id'])) {
                        $value['location'] = Asset::LOCATION_WAREHOUSE;
                        $value['status']   = Asset::STATUS_PENDING;
                    } else {
                        $value['location'] = $this->orgInfoRepository->find($value['organization_id'])?->branch;
                        $value['status']   = Asset::STATUS_ACTIVE;
                    }
                }

                $data[] = $value;
            } catch (\Throwable $exception) {
                $this->listError[] = $row['ma_tai_san'] . ' => ' .$exception->getMessage();
            }
        }

        dump($this->listError);
        dd($data);
    }

    private function getMessageError($key, $message)
    {
        return $key . ' => ' .$message;
    }
}
