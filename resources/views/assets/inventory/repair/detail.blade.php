<div x-data="tableAssetRepair" class="repair">
    <div
        @complete="handleCompleteMultiModalUI()"
    >
        @include('assets.inventory.repair.filter')
    </div>

    <div
        @change-page.window="changePage($event.detail.page)"
        @change-limit.window="changeLimit"
    >
        @include('assets.inventory.repair.table')
    </div>

    {{-- Modal --}}
    <div
        @cancel="handleCancelComplete($event.detail.id)"
    >
        @include('assets.inventory.repair.modalRepaired')
    </div>
    <div>
        @include('assets.inventory.repair.modalShowRepaired')
    </div>


</div>