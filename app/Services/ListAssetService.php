<?php

namespace App\Services;

use App\Models\Asset;
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

        if ($request->status && $request->status != 0) {
            $query->where('status', $request->status);
        }

        if ($request->location && $request->location != 0) {
            $query->where('location', $request->location);
        }

        if ($request->type && $request->type != 0) {
            $query->where('asset_type_id', $request->type);
        }

        if ($request->nameCodeAsset) {
            $query->where('name', 'LIKE', "%{$request->nameCodeAsset}%")
                ->orWhere('code', 'LIKE', "%{$request->nameCodeAsset}%");
        }

        if ($request->userId) {
            $arrAssetIdOfUser = MoveAssetUser::where('user_id',  $request->userId)->pluck('asset_id');

            $query->whereNotIn('id', $arrAssetIdOfUser);
        }

        return $query->with(['user', 'user.organization', 'user.organization.deptType', 'assetType', 'organization', 'organization.manager', 'organization.deptType'])->paginate(10);
    }

    public function getListUserAsset($request): LengthAwarePaginator
    {
        $query = User::query();

        if ($request->unit && $request->unit != 0) {
            $arrOrg = Org::get();

            $arrChildOrg = Org::getAllChildIds($request->unit, $arrOrg);

            $arrChildOrg[] = $request->unit;

            $query->whereIn('dept_id', $arrChildOrg);
        }

        if ($request->nameUser) {
            $query->where('name', 'LIKE', "%{$request->nameUser}%")
                ->orWhere('code', 'LIKE', "%{$request->nameUser}%");
        }

        return $query->with(['organization', 'organization.deptType'])->where('status', 1)->paginate(10);
    }

    public function allocateAsset($request)
    {
        $arrAllocationAsset = [];

        foreach ($request->listAssetAllocate as $asset) {
            $arrAllocationAsset[] = [
                'user_id' => $request->user['id'],
                'org_id' => $request->user['org_last_parent'] ? $request->user['org_last_parent']['id'] : $request->user['dept_id'],
                'asset_id' => $asset['id'],
                'type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        MoveAssetUser::insert($arrAllocationAsset);

        return $this->getListAssetOfUser($request->user['id']);
    }

    public function getListAssetOfUser(int $userId)
    {
        $arrAssetIdOfUser = MoveAssetUser::where('user_id',  $userId)->pluck('asset_id');

        return Asset::whereIn('id', $arrAssetIdOfUser)->get();
    }
}
