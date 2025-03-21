<?php

namespace App\Repositories;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\MoveAssetUser;
use App\Models\TransferAsset;
use App\Models\User;
use App\Repositories\Base\BaseRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Modules\Service\Models\Org;

class AssetRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return Asset::class;
    }

    public function changeStatusAsset($ids, $status)
    {
        $updatedRows = $this->_model->whereIn('id', Arr::wrap($ids))->update([
            'status' => $status,
        ]);

        return $updatedRows > 0;
    }

    public function getElementAssetByIds($ids, $columns = ['*'], $with = [])
    {
        return $this->_model->whereIn('id', $ids)
            ->with($with)
            ->select($columns)
            ->get();
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->select($columns)->with($with)->newQuery();

        if (!empty($filters['import_warehouse_id'])) {
            $query->where('import_warehouse_id', $filters['import_warehouse_id']);
        }

        if (!empty($filters['id'])) {
            $query->whereIn('id', Arr::wrap($filters['id']));
        }

        if (!empty($filters['code'])) {
            $query->whereIn('code', Arr::wrap($filters['code']));
        }

        if (!empty($filters['organization_id'])) {
            $query->whereIn('organization_id', Arr::wrap($filters['organization_id']));
        }

        if (!empty($filters['asset_type_id'])) {
            $query->whereIn('asset_type_id', Arr::wrap($filters['asset_type_id']));
        }

        return $query->get();
    }

    public function getAssetNeedMaintain($filters, $assetMaintainingIds = [], $columns = ['*'])
    {
        $date = Carbon::now()->addMonth()->format('Y-m-d');

        $query = $this->_model->select($columns)
            ->whereDate('next_maintenance_date', '<=', $date)
            ->whereIn('status', [Asset::STATUS_PENDING, Asset::STATUS_ACTIVE])
            ->whereNotIn('id', $assetMaintainingIds)
            ->orderBy('created_at', 'desc')
            ->newQuery();

        if (!empty($filters['name_code'])) {
            $query->where('name', $filters['name_code'])
                ->orWhere('code', $filters['name_code']);
        }

        if (!empty($filters['next_maintain_start']) && !empty($filters['next_maintain_end'])) {
            $query->whereBetween('next_maintenance_date', [$filters['next_maintain_start'], $filters['next_maintain_end']]);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['location'])) {
            $query->where('location', $filters['location']);
        }

        if (!empty($filters['organization_ids'])) {
            $query->whereIn('organization_id', $filters['organization_ids']);
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }

    public function getAssetNeedMaintainWithMonth($time)
    {
        $start = $time . '-01';
        $end   = $time . '-31';

        return $this->_model->whereBetween('next_maintenance_date', [$start, $end])
            ->whereIn('status', [Asset::STATUS_PENDING, Asset::STATUS_ACTIVE])
            ->get();
    }

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

        if ($request->unitSearch && 0 != $request->unitSearch) {
            $query->where('organization_id', $request->unitSearch);
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
                ->where('type', MoveAssetUser::TYPE_ALLOCATION)
                ->pluck('asset_id');

            $query->whereNotIn('id', $issuedAssetIds);
            $query->whereNull('user_id');
        }

        return $query->with([
            'user',
            'user.organization',
            'user.organization.deptType',
            'assetType',
            'organization',
            'organization.manager',
            'organization.deptType',
            'user.listAssetUse',
        ])->orderBy('id', 'desc')->paginate($request->limit);
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
        $listAsset = $query->with(['organization', 'organization.deptType', 'listAssetUse'])->where('status', 1)->paginate($request->limit);

        foreach ($listAsset->items() as $user) {
            $listOrgIdOfUser = Org::where('manager_id', $user->id)->pluck('id');

            $user->total_asset_represent = count($listOrgIdOfUser) > 0 ?
                Asset::whereIn('organization_id', $listOrgIdOfUser)->count()
                : 0;
        }

        return $listAsset;
    }

    public function getListOrgAsset($request): LengthAwarePaginator
    {
        $query = Org::query();

        if ($request->unit) {
            $query->where('id', $request->unit);
        }

        return $query->whereIn('parent_id', [0, 1])->with([
            'manager',
            'deptType',
            'listAsset',
        ])->paginate($request->limit);
    }

    public function getListHistory($request)
    {
        if ($request->userId) {
            return TransferAsset::where('user_id', $request->userId)->with([
                'user',
                'organization.manager',
                'organization.deptType',
                'createBy',
                'userTo',
                'organizationTo.manager',
                'organizationTo.deptType',
            ])->get();
        }

        if ($request->orgId) {
            return TransferAsset::where('org_id', $request->orgId)->whereNull('user_id')->with([
                'user',
                'organization.manager',
                'organization.deptType',
                'createBy',
                'userTo',
                'organizationTo.manager',
                'organizationTo.deptType',
            ])->get();
        }

        if ($request->assetId) {
            return MoveAssetUser::whereIn('id', function ($query) use ($request) {
                $query->selectRaw('MAX(id)')
                    ->from('move_asset_users')
                    ->where('asset_id', $request->assetId)
                    ->groupBy('transfer_asset_id');
            })
                ->with([
                    'transferAsset',
                    'transferAsset.user',
                    'transferAsset.organization.manager',
                    'transferAsset.organization.deptType',
                    'transferAsset.userTo',
                    'transferAsset.organizationTo.manager',
                    'transferAsset.organizationTo.deptType',
                    'transferAsset.createBy',
                ])
                ->get();
        }
    }

    public function getListLog($request)
    {
        if ($request->assetId) {
            $logRepair     = AssetHistory::where('asset_id', $request->assetId)->where('action', Asset::STATUS_DAMAGED)->with('assetRepair')->get();
            $logLostCancel = AssetHistory::where('asset_id', $request->assetId)
                ->whereIn('action', [
                    Asset::STATUS_LOST,
                    Asset::STATUS_CANCEL,
                    Asset::STATUS_PROPOSAL_LIQUIDATION,
                    Asset::STATUS_LIQUIDATED,
                ])->with('createBy')->get();

            return [
                'logRepair'     => $logRepair,
                'logLostCancel' => $logLostCancel,
            ];
        }
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

    public function checkOwnAssets(array $asset_ids)
    {
        return $this->_model
            ->whereIn('id', $asset_ids)
            ->pluck('user_id', 'id')
            ->mapWithKeys(fn ($user_id, $id) => [(int) $id => !is_null($user_id)])
            ->partition(fn ($hasUser) => $hasUser);
    }
}
