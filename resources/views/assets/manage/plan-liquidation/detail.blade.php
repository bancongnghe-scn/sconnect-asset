<div x-data="tableAssetPlanLiquidation" id="tableAssetPlanLiquidation">
    <div
        {{-- @change.window="handleExternalChange($event)" --}}
    >
        @include('assets.manage.plan-liquidation.filter')
    </div>

    <div
        @edit="handleEditModalUI($event.detail.id)"
        @get="showPlanLiquidation($event.detail.id)"
        @change-page.window="changePage($event.detail.page)"
        @change-limit.window="changeLimit"
        @change-limit-more.window="changeLimitMore"
    >
        @include('assets.manage.plan-liquidation.table')
    </div>

    {{-- Modal --}}
    <div
        @delete="modalRemoveSelectAsset($event.detail.id)"
        @post="modalSelectAsset(filterMore)"
    >
        @include('assets.manage.plan-liquidation.modalEditPlanLiquidation')
        @include('assets.manage.plan-liquidation.modalSelectAsset')
    </div>

    <div
        @approve="handleUpdateAssetOfPlan($event.detail.id, 'Đã duyệt')"
        @cancel="handleUpdateAssetOfPlan($event.detail.id, 'Từ chối')"
    >
        @include('assets.manage.plan-liquidation.modalShowPlanLiquidation')
    </div>


</div>