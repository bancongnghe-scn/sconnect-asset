<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AssetService
{
    public function getListAsset($request): LengthAwarePaginator
    {
        $query = Asset::query();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        return $query->with(['user', 'user.organization', 'assetType', 'organization', 'organization.manager'])->paginate(10);
    }
}
