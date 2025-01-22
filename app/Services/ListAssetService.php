<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\MoveAssetOrg;
use App\Models\MoveAssetUser;
use App\Models\Org;
use App\Models\TransferAsset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ListAssetService
{
    public function getListAsset($request): LengthAwarePaginator
    {
        $query = Asset::query();

        if ($request->status && 0 != $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->location && 0 != $request->location) {
            $query->where('location', $request->location);
        }

        if ($request->type && 0 != $request->type) {
            $query->where('asset_type_id', $request->type);
        }

        if ($request->nameCodeAsset) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->nameCodeAsset) . '%'])
                ->orWhereRaw('LOWER(code) LIKE ?', ['%' . strtolower($request->nameCodeAsset) . '%']);
        }

        if ($request->userId) {
            $arrAssetIdOfUser = MoveAssetUser::where('user_id', $request->userId)
                ->select('asset_id', \DB::raw('MAX(id) as latest_move_id'))
                ->groupBy('asset_id');

            $issuedAssetIds = MoveAssetUser::whereIn('id', $arrAssetIdOfUser->pluck('latest_move_id'))
                ->where('type', 1)
                ->pluck('asset_id');

            $query->whereNotIn('id', $issuedAssetIds);
            $query->whereNull('user_id');
        }

        return $query->with(['user', 'user.organization', 'user.organization.deptType', 'assetType', 'organization', 'organization.manager', 'organization.deptType'])->orderBy('created_at', 'desc')->paginate($request->limit);
    }

    public function getListUserAsset($request): LengthAwarePaginator
    {
        $query = User::query();

        if ($request->unit && 0 != $request->unit) {
            $arrOrg = Org::get();

            $arrChildOrg = Org::getAllChildIds($request->unit, $arrOrg);

            $arrChildOrg[] = $request->unit;

            $query->whereIn('dept_id', $arrChildOrg);
        }

        if ($request->nameUser) {
            $query->where('name', 'LIKE', "%{$request->nameUser}%")
                ->orWhere('code', 'LIKE', "%{$request->nameUser}%");
        }

        return $query->with(['organization', 'organization.deptType', 'listAssetUse'])->where('status', 1)->paginate($request->limit);
    }

    public function allocateAsset($request)
    {
        $arrAllocationAsset = [];
        $arrAssetId         = [];

        foreach ($request->listAssetAllocate as $asset) {
            $arrAllocationAsset[] = [
                'user_id'           => $request->user['id'],
                'org_id'            => $request->user['org_last_parent'] ? $request->user['org_last_parent']['id'] : $request->user['dept_id'],
                'asset_id'          => $asset['id'],
                'type'              => 1,
                'description'       => $request->description,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ];

            $arrAssetId[] = $asset['id'];
        }

        TransferAsset::create([
            'user_id'           => $request->user['id'],
            'org_id'            => $request->user['org_last_parent'] ? $request->user['org_last_parent']['id'] : $request->user['dept_id'],
            'type'              => 1,
            'created_by'        => auth()->user() ? auth()->user()->id : null,
            'description'       => $request->description,
        ]);

        MoveAssetUser::insert($arrAllocationAsset);

        Asset::where('id', $arrAssetId)->update([
            'status'  => Asset::STATUS_ACTIVE,
            'user_id' => $request->user['id'],
        ]);

        return $this->getListAssetOfUser($request->user['id']);
    }

    public function recoveryAsset($request)
    {
        $arrRecoveryAsset = [];
        $arrAssetId       = [];

        foreach ($request->listAssetRecovery as $asset) {
            $arrRecoveryAsset[] = [
                'user_id'     => $request->user['id'],
                'org_id'      => $request->user['org_last_parent'] ? $request->user['org_last_parent']['id'] : $request->user['dept_id'],
                'asset_id'    => $asset['id'],
                'type'        => 2,
                'description' => $request->description,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ];

            $arrAssetId[] = $asset['id'];
        }

        TransferAsset::create([
            'user_id'     => $request->user['id'],
            'org_id'      => $request->user['org_last_parent'] ? $request->user['org_last_parent']['id'] : $request->user['dept_id'],
            'type'        => 2,
            'created_by'  => auth()->user() ? auth()->user()->id : null,
            'description' => $request->description,
        ]);

        MoveAssetUser::insert($arrRecoveryAsset);

        Asset::where('id', $arrAssetId)->update([
            'status'  => Asset::STATUS_PENDING,
            'user_id' => null,
        ]);

        return $this->getListAssetOfUser($request->user['id']);
    }

    public function getListAssetOfUser(int $userId)
    {
        $arrAssetIdOfUser = MoveAssetUser::where('user_id', $userId)
            ->select('asset_id', \DB::raw('MAX(id) as latest_move_id'))
            ->groupBy('asset_id');

        $issuedAssetIds = MoveAssetUser::whereIn('id', $arrAssetIdOfUser->pluck('latest_move_id'))
            ->where('type', 1)
            ->pluck('asset_id');

        return Asset::whereIn('id', $issuedAssetIds)->get();
    }

    public function getListOrgAsset($request): LengthAwarePaginator
    {
        $query = Org::query();

        return $query->whereIn('parent_id', [0, 1])->with(['manager'])->paginate($request->limit);
    }

    public function allocateAssetOrg($request)
    {
        $arrAllocationAsset = [];
        $arrAssetId         = [];

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

            $arrAssetId[] = $asset['id'];
        }

        TransferAsset::create([
            'user_id'     => null,
            'org_id'      => $request->org['id'],
            'type'        => 1,
            'created_by'  => auth()->user() ? auth()->user()->id : null,
            'description' => $request->description,
        ]);

        MoveAssetOrg::insert($arrAllocationAsset);

        Asset::where('id', $arrAssetId)->update([
            'status'          => Asset::STATUS_ACTIVE,
            'organization_id' => $request->org['id'],
            'user_id'         => null,
        ]);

        return $this->getListAssetOfOrg($request->org['id']);
    }

    public function recoveryAssetOrg($request)
    {
        $arrRecoveryAsset = [];
        $arrAssetId       = [];

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

            $arrAssetId[] = $asset['id'];
        }

        TransferAsset::create([
            'user_id'     => null,
            'org_id'      => $request->org['id'],
            'type'        => 2,
            'description' => $request->description,
            'created_by'  => auth()->user() ? auth()->user()->id : null,
        ]);

        MoveAssetOrg::insert($arrRecoveryAsset);

        Asset::where('id', $arrAssetId)->update([
            'status'          => Asset::STATUS_PENDING,
            'organization_id' => null,
        ]);

        return $this->getListAssetOfOrg($request->org['id']);
    }

    public function getListAssetOfOrg(int $orgId)
    {
        $arrAssetIdOfOrg = MoveAssetOrg::where('org_id', $orgId)
            ->select('asset_id', \DB::raw('MAX(id) as latest_move_id'))
            ->groupBy('asset_id');

        $issuedAssetIds = MoveAssetOrg::whereIn('id', $arrAssetIdOfOrg->pluck('latest_move_id'))
            ->where('type', 1)
            ->pluck('asset_id');

        return Asset::whereIn('id', $issuedAssetIds)->whereNull('user_id')->get();
    }

    public function getUserByUnit($request): Collection
    {
        $query = User::query();

        if ($request->orgId) {
            $arrOrg = Org::get();

            $arrChildOrg = Org::getAllChildIds($request->orgId, $arrOrg);

            $arrChildOrg[] = $request->orgId;

            $query->whereIn('dept_id', $arrChildOrg);
        }

        return $query->limit(2000)->get();
    }

    public function getListHistory($request)
    {
        if ($request->userId) {
            return TransferAsset::where('user_id', $request->userId)->with(['user', 'organization.manager', 'organization.deptType'])->get();
        }

        if ($request->orgId) {
            return TransferAsset::where('org_id', $request->orgId)->whereNull('user_id')->with(['user', 'organization.manager', 'organization.deptType'])->get();
        }
    }

    public function rotationAsset($request)
    {

        dd($request->all());
    }

    public function liquidationAsset($request): void
    {
        Asset::where('id', $request->assetSelect['id'])->update([
            'status' => Asset::STATUS_PROPOSAL_LIQUIDATION,
        ]);

        AssetHistory::create([
            'asset_id' => $request->assetSelect['id'],
            'date' => $request->dateLiquidation,
            'action' => Asset::STATUS_PROPOSAL_LIQUIDATION,
            'description' => $request->reasonLiquidation,
            'price' => $request->priceLiquidation,
            'created_by' => auth()->user() ? auth()->user()->id : 1,
        ]);
    }

    public function cancelAsset($request): void
    {
        Asset::where('id', $request->assetSelect['id'])->update([
            'status' => Asset::STATUS_CANCEL,
        ]);

        AssetHistory::create([
            'asset_id' => $request->assetSelect['id'],
            'date' => $request->dateLiquidation,
            'action' => Asset::STATUS_CANCEL,
            'description' => $request->reasonLiquidation,
            'created_by' => auth()->user() ? auth()->user()->id : 1,
        ]);
    }

    public function brokenAsset($request): void
    {
        Asset::where('id', $request->assetSelect['id'])->update([
            'status' => Asset::STATUS_DAMAGED,
        ]);

        AssetHistory::create([
            'asset_id' => $request->assetSelect['id'],
            'date' => $request->dateLiquidation,
            'action' => Asset::STATUS_DAMAGED,
            'description' => $request->reasonLiquidation,
            'created_by' => auth()->user() ? auth()->user()->id : 1,
        ]);
    }

    public function lostAsset($request): void
    {
        Asset::where('id', $request->assetSelect['id'])->update([
            'status' => Asset::STATUS_LOST,
        ]);

        AssetHistory::create([
            'asset_id' => $request->assetSelect['id'],
            'date' => $request->dateLiquidation,
            'action' => Asset::STATUS_LOST,
            'description' => $request->reasonLiquidation,
            'created_by' => auth()->user() ? auth()->user()->id : 1,
        ]);
    }
}
