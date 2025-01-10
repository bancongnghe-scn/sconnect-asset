<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\MoveAssetOrg;
use App\Models\MoveAssetUser;
use App\Models\Org;
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
        }

        MoveAssetUser::insert($arrAllocationAsset);

        Asset::where('id', $asset['id'])->update([
            'status'  => Asset::STATUS_ACTIVE,
            'user_id' => $request->user['id'],
        ]);

        return $this->getListAssetOfUser($request->user['id']);
    }

    public function recoveryAsset($request)
    {
        $arrRecoveryAsset = [];

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
        }

        MoveAssetUser::insert($arrRecoveryAsset);

        Asset::where('id', $asset['id'])->update([
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
        }

        MoveAssetOrg::insert($arrAllocationAsset);

        Asset::where('id', $asset['id'])->update([
            'status'          => Asset::STATUS_ACTIVE,
            'organization_id' => $request->org['id'],
            'user_id'         => null,
        ]);

        return $this->getListAssetOfOrg($request->org['id']);
    }

    public function recoveryAssetOrg($request)
    {
        $arrRecoveryAsset = [];

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
        }

        MoveAssetOrg::insert($arrRecoveryAsset);

        Asset::where('id', $asset['id'])->update([
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
}
