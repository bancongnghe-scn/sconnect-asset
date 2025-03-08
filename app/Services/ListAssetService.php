<?php

namespace App\Services;

use App\Http\Resources\AssetInfoResource;
use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\MoveAssetOrg;
use App\Models\MoveAssetUser;
use App\Models\Org;
use App\Models\TransferAsset;
use App\Models\User;
use App\Repositories\AssetRepository;
use App\Support\Constants\AppErrorCode;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ListAssetService
{
    public const ID_HUE = 323;

    public function __construct(
        protected AssetRepository $assetRepository,
    ) {
    }

    public function getListAsset($request): LengthAwarePaginator
    {
        return $this->assetRepository->getListAsset($request);
    }

    public function getListUserAsset($request): LengthAwarePaginator
    {
        return $this->assetRepository->getListUserAsset($request);
    }

    public function allocateAsset($request)
    {
        DB::beginTransaction();
        try {
            $arrAllocationAsset = [];
            $arrAssetId         = [];

            $assetCurrent = Asset::find($request->listAssetAllocate[0]['id']);

            $dateChange = Carbon::parse($request->dateChange);

            $transferAsset = TransferAsset::create([
                'user_id'           => $request->user['id'],
                'org_id'            => $request->user['org_last_parent'] ? $request->user['org_last_parent']['id'] : $request->user['dept_id'],
                'type'              => 1,
                'created_by'        => auth()->user() ? auth()->user()->id : 1,
                'description'       => $request->description,
                'to_user_id'        => $assetCurrent->user_id,
                'to_org_id'         => $assetCurrent->organization_id,
                'date'              => $dateChange,
            ]);

            foreach ($request->listAssetAllocate as $asset) {
                $arrAllocationAsset[] = [
                    'user_id_after'           => $request->user['id'],
                    'org_id_after'            => $request->user['org_last_parent'] ? $request->user['org_last_parent']['id'] : $request->user['dept_id'],
                    'asset_id'                => $asset['id'],
                    'type'                    => 1,
                    'transfer_asset_id'       => $transferAsset->id,
                    'description'             => $request->description,
                    'date'                    => $dateChange->year.'0'.$dateChange->month.'0'.$dateChange->day,
                    'date_change'             => $dateChange,
                    'created_at'              => Carbon::now(),
                    'updated_at'              => Carbon::now(),
                ];

                $arrAssetId[] = $asset['id'];
            }

            MoveAssetUser::insert($arrAllocationAsset);

            Asset::whereIn('id', $arrAssetId)->update([
                'status'          => Asset::STATUS_ACTIVE,
                'user_id'         => $request->user['id'],
                'organization_id' => $request->user['org_last_parent'] ? $request->user['org_last_parent']['id'] : $request->user['dept_id'],
                'location'        => DB::connection('db_dev')->table('user_generals')->where('user_id', $request->user['id'])->first()->workplace_id,
            ]);
            $this->exportReport($transferAsset->id, $arrAssetId);
            DB::commit();

            return [
                'listAssetOfObj' => $this->getListAssetOfUser($request->user['id']),
                'linkReport'     => TransferAsset::find($transferAsset->id),
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    public function recoveryAsset($request)
    {
        DB::beginTransaction();
        try {
            $arrRecoveryAsset = [];
            $arrAssetId       = [];
            $orgIdAfter       = null;

            $orgIdAfter = $request->recoveryCompany
                ? null : ($request->user['org_last_parent'] ?
                    $request->user['org_last_parent']['id']
                    : $request->user['dept_id']);

            $dateChange = Carbon::parse($request->dateChange);

            $transferAsset = TransferAsset::create([
                'user_id'        => $request->user['id'],
                'org_id'         => $request->user['org_last_parent'] ? $request->user['org_last_parent']['id'] : $request->user['dept_id'],
                'type'           => 2,
                'created_by'     => auth()->user() ? auth()->user()->id : 1,
                'description'    => $request->description,
                'to_user_id'     => null,
                'to_org_id'      => $orgIdAfter,
                'date'           => $dateChange,
            ]);

            foreach ($request->listAssetRecovery as $asset) {
                $arrRecoveryAsset[] = [
                    'user_id'              => $request->user['id'],
                    'org_id'               => $request->user['org_last_parent'] ? $request->user['org_last_parent']['id'] : $request->user['dept_id'],
                    'asset_id'             => $asset['id'],
                    'type'                 => 2,
                    'user_id_after'        => null,
                    'org_id_after'         => $orgIdAfter,
                    'description'          => $request->description,
                    'transfer_asset_id'    => $transferAsset->id,
                    'date'                 => $dateChange->year.'0'.$dateChange->month.'0'.$dateChange->day,
                    'date_change'          => $dateChange,
                    'created_at'           => Carbon::now(),
                    'updated_at'           => Carbon::now(),
                ];

                $arrAssetId[] = $asset['id'];
            }

            MoveAssetUser::insert($arrRecoveryAsset);

            Asset::whereIn('id', $arrAssetId)->update([
                'status'          => Asset::STATUS_PENDING,
                'user_id'         => null,
                'organization_id' => $orgIdAfter,
                'location'        => $request->recoveryCompany ? 1 : DB::connection('db_dev')->table('org_infos')->where('org_id', $orgIdAfter)->first()->branch_id,
            ]);

            $this->exportReport($transferAsset->id, $arrAssetId);

            DB::commit();

            return [
                'listAssetOfObj' => $this->getListAssetOfUser($request->user['id']),
                'linkReport'     => TransferAsset::find($transferAsset->id),
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    public function getListAssetOfUser(int $userId)
    {
        return Asset::where('user_id', $userId)->with(['assetType'])->get();
    }

    public function getListOrgAsset($request): LengthAwarePaginator
    {
        return $this->assetRepository->getListOrgAsset($request);
    }

    public function allocateAssetOrg($request)
    {
        DB::beginTransaction();
        try {
            $arrAllocationAsset     = [];
            $arrAssetId             = [];
            $arrAllocationAssetUser = [];

            $dateChange = Carbon::parse($request->dateChange);

            $assetCurrent = Asset::find($request->listAssetAllocate[0]['id']);

            $transferAsset = TransferAsset::create([
                'user_id'        => null,
                'org_id'         => $request->org['id'],
                'type'           => 1,
                'created_by'     => auth()->user() ? auth()->user()->id : 1,
                'description'    => $request->description,
                'to_user_id'     => $assetCurrent->user_id,
                'to_org_id'      => $assetCurrent->organization_id,
                'date'           => $dateChange,
            ]);

            foreach ($request->listAssetAllocate as $asset) {
                $arrAllocationAsset[] = [
                    'user_id'     => null,
                    'org_id'      => $request->org['id'],
                    'asset_id'    => $asset['id'],
                    'type'        => 1,
                    'is_rotation' => 1,
                    'description' => $request->description,
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                ];

                $arrAllocationAssetUser[] = [
                    'user_id_after'        => null,
                    'org_id_after'         => $request->org['id'],
                    'asset_id'             => $asset['id'],
                    'type'                 => 1,
                    'transfer_asset_id'    => $transferAsset->id,
                    'description'          => $request->description,
                    'date'                 => $dateChange->year.'0'.$dateChange->month.'0'.$dateChange->day,
                    'date_change'          => $dateChange,
                    'created_at'           => Carbon::now(),
                    'updated_at'           => Carbon::now(),
                ];

                $arrAssetId[] = $asset['id'];
            }

            MoveAssetOrg::insert($arrAllocationAsset);

            MoveAssetUser::insert($arrAllocationAssetUser);

            Asset::whereIn('id', $arrAssetId)->update([
                'status'          => Asset::STATUS_ACTIVE,
                'organization_id' => $request->org['id'],
                'user_id'         => null,
                'location'        => DB::connection('db_dev')->table('org_infos')->where('org_id', $request->org['id'])->first()->branch_id,
            ]);
            $this->exportReport($transferAsset->id, $arrAssetId);
            DB::commit();

            return [
                'listAssetOfObj' => $this->getListAssetOfOrg($request->org['id']),
                'linkReport'     => TransferAsset::find($transferAsset->id),
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    public function recoveryAssetOrg($request)
    {
        DB::beginTransaction();
        try {
            $arrRecoveryAsset       = [];
            $arrAssetId             = [];
            $arrAllocationAssetUser = [];

            $dateChange = Carbon::parse($request->dateChange);

            $transferAsset = TransferAsset::create([
                'user_id'     => null,
                'org_id'      => $request->org['id'],
                'type'        => 2,
                'description' => $request->description,
                'created_by'  => auth()->user() ? auth()->user()->id : 1,
                'date'        => $dateChange,
            ]);

            foreach ($request->listAssetRecovery as $asset) {
                $arrRecoveryAsset[] = [
                    'user_id'     => null,
                    'org_id'      => $request->org['id'],
                    'asset_id'    => $asset['id'],
                    'type'        => 2,
                    'is_rotation' => 1,
                    'description' => $request->description,
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                ];

                $arrAllocationAssetUser[] = [
                    'user_id_after'        => null,
                    'org_id_after'         => null,
                    'asset_id'             => $asset['id'],
                    'type'                 => 2,
                    'org_id'               => $request->org['id'],
                    'transfer_asset_id'    => $transferAsset->id,
                    'description'          => $request->description,
                    'date'                 => $dateChange->year.'0'.$dateChange->month.'0'.$dateChange->day,
                    'date_change'          => $dateChange,
                    'created_at'           => Carbon::now(),
                    'updated_at'           => Carbon::now(),
                ];

                $arrAssetId[] = $asset['id'];
            }

            MoveAssetOrg::insert($arrRecoveryAsset);

            MoveAssetUser::insert($arrAllocationAssetUser);

            Asset::whereIn('id', $arrAssetId)->update([
                'status'          => Asset::STATUS_PENDING,
                'organization_id' => null,
                'user_id'         => null,
                'location'        => 1,
            ]);

            $this->exportReport($transferAsset->id, $arrAssetId);

            DB::commit();

            return [
                'listAssetOfObj' => $this->getListAssetOfOrg($request->org['id']),
                'linkReport'     => TransferAsset::find($transferAsset->id),
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    public function getListAssetOfOrg(int $orgId)
    {
        return Asset::whereNull('user_id')->where('organization_id', $orgId)->get();
    }

    public function getUserByUnit($request): Collection
    {
        return $this->assetRepository->getUserByUnit($request);
    }

    public function getListLog($request)
    {
        return $this->assetRepository->getListLog($request);
    }

    public function getListHistory($request)
    {
        return $this->assetRepository->getListHistory($request);
    }

    public function rotationAsset($request)
    {
        DB::beginTransaction();
        try {
            $transferAsset = null;
            $arrAssetId    = [];

            $dateChange = Carbon::parse($request->dateChange);

            foreach ($request->listAssetRotation as $assetRotation) {
                $orgId        = null;
                $userId       = null;
                $arrAssetId[] = $assetRotation['id'];

                $orgLastParentId = User::find($request->rotationToId)->org_last_parent?->id ?? User::find($request->rotationToId)->organization_id;

                $orgId  = 'unit' == $request->rotationToType ? $request->rotationToId : $orgLastParentId;
                $userId = 'unit' == $request->rotationToType ? null : $request->rotationToId * 1;

                $transferAsset = TransferAsset::create([
                    'user_id'       => $assetRotation['user_id'],
                    'org_id'        => $assetRotation['organization_id'],
                    'type'          => 3,
                    'to_user_id'    => $userId,
                    'to_org_id'     => $orgId,
                    'created_by'    => auth()->user() ? auth()->user()->id : 1,
                    'description'   => $request->descriptionRotation,
                    'date'          => $dateChange,
                ]);

                //thu hồi
                if ($assetRotation['user_id'] || $assetRotation['organization_id']) {
                    MoveAssetOrg::create([
                        'org_id'      => $assetRotation['organization_id'],
                        'user_id'     => $assetRotation['user_id'],
                        'asset_id'    => $assetRotation['id'],
                        'type'        => 2,
                        'is_rotation' => 1,
                        'description' => $request->descriptionRotation,
                        'created_at'  => Carbon::now(),
                        'updated_at'  => Carbon::now(),
                    ]);

                    MoveAssetUser::create([
                        'user_id_after'        => null,
                        'org_id_after'         => null,
                        'asset_id'             => $assetRotation['id'],
                        'type'                 => 2,
                        'org_id'               => $assetRotation['organization_id'],
                        'user_id'              => $assetRotation['user_id'],
                        'transfer_asset_id'    => $transferAsset->id,
                        'description'          => $request->descriptionRotation,
                        'date'                 => $dateChange->year.'0'.$dateChange->month.'0'.$dateChange->day,
                        'date_change'          => $dateChange,
                        'created_at'           => Carbon::now(),
                        'updated_at'           => Carbon::now(),
                    ]);
                }

                //cấp phát
                MoveAssetOrg::create([
                    'user_id'     => $userId,
                    'org_id'      => $orgId,
                    'asset_id'    => $assetRotation['id'],
                    'type'        => 1,
                    'is_rotation' => 1,
                    'description' => $request->descriptionRotation,
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                ]);

                MoveAssetUser::create([
                    'org_id'               => $assetRotation['organization_id'],
                    'user_id'              => $assetRotation['user_id'],
                    'user_id_after'        => $userId,
                    'org_id_after'         => $orgId,
                    'asset_id'             => $assetRotation['id'],
                    'type'                 => 1,
                    'transfer_asset_id'    => $transferAsset->id,
                    'description'          => $request->descriptionRotation,
                    'date'                 => $dateChange->year.'0'.$dateChange->month.'0'.$dateChange->day,
                    'date_change'          => $dateChange,
                    'created_at'           => Carbon::now(),
                    'updated_at'           => Carbon::now(),
                ]);

                Asset::where('id', $assetRotation['id'])->update([
                    'status'          => Asset::STATUS_ACTIVE,
                    'organization_id' => $orgId,
                    'user_id'         => $userId,
                    'location'        => $userId ?
                        DB::connection('db_dev')->table('user_generals')->where('user_id', $userId)->first()->workplace_id
                        : DB::connection('db_dev')->table('org_infos')->where('org_id', $orgId)->first()->branch_id,
                ]);
            }

            $this->exportReport($transferAsset->id, $arrAssetId);

            DB::commit();

            return TransferAsset::find($transferAsset->id);
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    public function liquidationAsset($request): void
    {
        Asset::where('id', $request->assetSelect['id'])->update([
            'status' => Asset::STATUS_PROPOSAL_LIQUIDATION,
        ]);

        AssetHistory::create([
            'asset_id'    => $request->assetSelect['id'],
            'date'        => $request->dateLiquidation,
            'action'      => Asset::STATUS_PROPOSAL_LIQUIDATION,
            'description' => $request->reasonLiquidation,
            'price'       => $request->priceLiquidation,
            'created_by'  => auth()->user() ? auth()->user()->id : 1,
        ]);
    }

    public function cancelAsset($request): void
    {
        Asset::where('id', $request->assetSelect['id'])->update([
            'status' => Asset::STATUS_CANCEL,
        ]);

        AssetHistory::create([
            'asset_id'    => $request->assetSelect['id'],
            'date'        => $request->dateLiquidation,
            'action'      => Asset::STATUS_CANCEL,
            'description' => $request->reasonLiquidation,
            'created_by'  => auth()->user() ? auth()->user()->id : 1,
        ]);
    }

    public function brokenAsset($request): void
    {
        Asset::where('id', $request->assetSelect['id'])->update([
            'status' => Asset::STATUS_DAMAGED,
        ]);

        AssetHistory::create([
            'asset_id'    => $request->assetSelect['id'],
            'date'        => $request->dateLiquidation,
            'action'      => Asset::STATUS_DAMAGED,
            'description' => $request->reasonLiquidation,
            'created_by'  => auth()->user() ? auth()->user()->id : 1,
        ]);
    }

    public function lostAsset($request): void
    {
        Asset::where('id', $request->assetSelect['id'])->update([
            'status' => Asset::STATUS_LOST,
        ]);

        AssetHistory::create([
            'asset_id'    => $request->assetSelect['id'],
            'date'        => $request->dateLiquidation,
            'action'      => Asset::STATUS_LOST,
            'description' => $request->reasonLiquidation,
            'created_by'  => auth()->user() ? auth()->user()->id : 1,
        ]);
    }

    public function updateAsset($request): void
    {
        Asset::where('id', $request->assetEdit['id'])->update([
            'name'                    => $request->assetEdit['name'],
            'asset_type_id'           => $request->typeAsset,
            'code'                    => $request->assetEdit['code'],
            'supplier_id'             => $request->supplier,
            'price'                   => $request->assetEdit['price'],
            'warranty_months'         => $request->assetEdit['warranty_months'],
            'recent_maintenance_date' => $request->assetEdit['recent_maintenance_date'],
            'next_maintenance_date'   => $request->assetEdit['next_maintenance_date'],
            'description'             => $request->assetEdit['description'],
            'seri_number'             => $request->assetEdit['seri_number'],
            'location'                => $request->location,
            'date_purchase'           => $request->assetEdit['date_purchase'],
        ]);
    }

    public function listAssetRepresent($request)
    {
        $listOrgIdOfUser = Org::where('manager_id', $request->userId)->pluck('id');

        if (count($listOrgIdOfUser) > 0) {
            return Asset::whereIn('organization_id', $listOrgIdOfUser)->with(['assetType'])->get();
        }

        return [];
    }

    public function getAssetInfo($id)
    {
        $data = $this->assetRepository->find($id);
        if (empty($data)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2106,
            ];
        }

        return [
            'success' => true,
            'data'    => AssetInfoResource::make($data)->resolve(),
        ];
    }

    public function exportReport($transferAssetId, $arrAssetId)
    {
        $nameFile    = '';
        $titleReport = '';

        $transferAsset = TransferAsset::find($transferAssetId);
        $userFrom      = $transferAsset->user_id ? User::where('id', $transferAsset->user_id)->with(['organization'])->first()
            : ($transferAsset->org_id ? Org::where('id', $transferAsset->org_id)->with(['manager', 'manager.organization'])->first()->manager : User::find(self::ID_HUE));
        $userTo = $transferAsset->to_user_id ? User::where('id', $transferAsset->to_user_id)->with(['organization'])->first()
            : ($transferAsset->to_org_id ? Org::where('id', $transferAsset->to_org_id)->with(['manager', 'manager.organization'])->first()->manager : User::find(self::ID_HUE));

        $userTemp = null;

        switch ($transferAsset->type) {
            case 1:
                $nameFile    = 'capphat';
                $titleReport = 'BIÊN BẢN CẤP PHÁT TÀI SẢN';

                $userTemp = $userFrom;
                $userFrom = $userTo;
                $userTo   = $userTemp;
                break;

            case 2:
                $nameFile    = 'thuhhoi';
                $titleReport = 'BIÊN BẢN THU HỒI TÀI SẢN';
                break;

            default:
                $nameFile    = 'luanchuyen';
                $titleReport = 'BIÊN BẢN LUÂN CHUYỂN TÀI SẢN';
                break;
        }

        $filePath    = resource_path('views/assets/asset/template-excel/template_report.xlsx');
        $spreadsheet = IOFactory::load($filePath);
        $sheet       = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A14', 'Hôm nay, vào lúc ….  Ngày ' . Carbon::now()->day . ' tháng ' . Carbon::now()->month . ' năm ' . Carbon::now()->year . ' tại Văn phòng Công ty TNHH Đầu tư Công nghệ và Dịch vụ S-Connect Việt Nam.');
        $sheet->setCellValue('A7', $titleReport);
        $sheet->setCellValue('D17', $userFrom?->name);
        $sheet->setCellValue('D18', '' == $userFrom->job_position && self::ID_HUE == $userFrom->id ? 'Chuyên viên Hành chính' : $userFrom?->job_position);
        $sheet->setCellValue('D19', $userFrom?->organization?->name);
        $sheet->setCellValue('D21', $userTo?->name);
        $sheet->setCellValue('D22', '' == $userTo->job_position && self::ID_HUE == $userTo->id ? 'Chuyên viên Hành chính' : $userTo?->job_position);
        $sheet->setCellValue('D23', $userTo?->organization?->name);

        $listAsset = Asset::whereIn('id', $arrAssetId)->with(['assetType'])->get();

        $startRow   = 26;
        $numNewRows = count($listAsset);

        $sheet->insertNewRowBefore($startRow + 1, $numNewRows);

        $currentRow = $startRow;
        foreach ($listAsset as $index => $asset) {
            $this->copyRowStyle($sheet, $startRow, $currentRow);

            $columnWidth = 40;
            $lineCount   = ceil(strlen($asset->name) / $columnWidth);

            $sheet->getRowDimension($currentRow)->setRowHeight($lineCount * 15);

            $sheet->setCellValue("A$currentRow", $index + 1);
            $sheet->setCellValue("B$currentRow", $asset->name);
            $sheet->setCellValue("E$currentRow", $asset->code);
            $sheet->setCellValue("F$currentRow", Asset::LIST_MEASURE[$asset->assetType->measure]);
            $sheet->setCellValue("G$currentRow", 1);
            $sheet->setCellValue("H$currentRow", $asset->price);
            $sheet->setCellValue("I$currentRow", Asset::STATUS_NAME[$asset->status]);
            $sheet->setCellValue("J$currentRow", $userFrom?->name);
            $sheet->setCellValue("K$currentRow", $userTo?->name);
            $sheet->getRowDimension($currentRow)->setRowHeight(-1);
            ++$currentRow;
        }

        $sheet->removeRow(26 + $numNewRows);

        $path = public_path('reports/');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $nameFile    = $nameFile . '_' . $transferAsset->id . '.xlsx';
        $newFilePath = public_path('reports/' . $nameFile);
        $writer      = new Xlsx($spreadsheet);
        $writer->save($newFilePath);

        TransferAsset::where('id', $transferAssetId)->update([
            'link_report' => 'reports/' . $nameFile,
        ]);
    }

    private function copyRowStyle(Worksheet $sheet, int $sourceRow, int $targetRow)
    {
        $sheet->duplicateStyle($sheet->getStyle("A$sourceRow:C$sourceRow"), "A$targetRow:C$targetRow");

        foreach ($sheet->getMergeCells() as $mergeRange) {
            if (preg_match("/([A-Z]+)$sourceRow:([A-Z]+)$sourceRow/", $mergeRange, $matches)) {
                $newMergeRange = "{$matches[1]}$targetRow:{$matches[2]}$targetRow";
                $sheet->mergeCells($newMergeRange);
            }
        }
    }
}
