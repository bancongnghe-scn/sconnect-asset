<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Organization;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AssetService
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
        
        return $query->with(['user', 'user.organization', 'user.organization.deptType', 'assetType', 'organization', 'organization.manager', 'organization.deptType'])->paginate(10);
    }
}
