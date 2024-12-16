<div x-data="tableAssetDamaged">
    <div>
        @include('assets.inventory.damaged.filter')
    </div>

    <div
        @repaid="handleRepaidModalUI($event.detail.id)"
        @liquidation="handleLiquidationModalUI($event.detail.id)"
        @cancel="handleCancelModalUI($event.detail.id)"
        @change-page.window="changePage($event.detail.page)"
        @change-limit.window="changeLimit"
    >
        @include('assets.inventory.damaged.table')
    </div>

    {{-- Modal --}}
    <div
        @cancel="handleCancelOfModalRepairUI($event.detail.id)"
    >
        @include('assets.inventory.damaged.modalRepair')
    </div>

    <div
        @remove="handleRemoveLiquidationOfModalUI($event.detail.id)"
    >
        @include('assets.inventory.damaged.modalLiquidation')
    </div>

    <div
        @remove="handleRemoveCancelOfModalUI($event.detail.id)"
    >
        @include('assets.inventory.damaged.modalCancel')
    </div>
</div>