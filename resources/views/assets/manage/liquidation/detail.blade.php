<div x-data="tableAssetLiquidation">
    <div
        @create="handleCreatePlanLiquidation()"
    >
        @include('assets.manage.liquidation.filter')
    </div>

    <div
        @edit="handleBackModalUI($event.detail.id)"
        @cancel="handleCancelModalUI($event.detail.id)"
        @change-page.window="changePage($event.detail.page)"
        @change-limit.window="changLimit"
    >
        @include('assets.manage.liquidation.table')
    </div>

    {{-- Modal --}}
    <div
        @delete="handleDeleteOfMultiModalCancelUI($event.detail.id)"
    >
        @include('assets.manage.liquidation.modalCreatePlan')
    </div>


</div>