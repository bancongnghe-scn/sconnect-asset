<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\MoveAssetUser;
use App\Models\Org;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
            $query->whereRaw('LOWER(name) LIKE ?', ['%'.strtolower($request->nameCodeAsset).'%'])
            ->orWhereRaw('LOWER(code) LIKE ?', ['%'.strtolower($request->nameCodeAsset).'%']);
        }

        if ($request->userId) {
            $arrAssetIdOfUser = MoveAssetUser::where('user_id', $request->userId)->pluck('asset_id');

            $query->whereNotIn('id', $arrAssetIdOfUser);
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

        return $query->with(['organization', 'organization.deptType'])->where('status', 1)->paginate(10);
    }

    public function allocateAsset($request)
    {
        $arrAllocationAsset = [];

        foreach ($request->listAssetAllocate as $asset) {
            $arrAllocationAsset[] = [
                'user_id'    => $request->user['id'],
                'org_id'     => $request->user['org_last_parent'] ? $request->user['org_last_parent']['id'] : $request->user['dept_id'],
                'asset_id'   => $asset['id'],
                'type'       => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        MoveAssetUser::insert($arrAllocationAsset);

        return $this->getListAssetOfUser($request->user['id']);
    }

    public function getListAssetOfUser(int $userId)
    {
        $arrAssetIdOfUser = MoveAssetUser::where('user_id', $userId)->pluck('asset_id');

        return Asset::whereIn('id', $arrAssetIdOfUser)->get();
    }
}
