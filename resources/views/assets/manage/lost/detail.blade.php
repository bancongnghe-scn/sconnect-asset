<div x-data="tableAssetLost">
    <div
        @back="handleBackMultiModalUI()"
        @cancel="handleCancelMultiModalUI()"
    >
        @include('assets.manage.lost.filter')
    </div>

    <div
        @edit="handleBackModalUI($event.detail.id)"
        @cancel="handleCancelModalUI($event.detail.id)"
        @change-page.window="changePage($event.detail.page)"
        @change-limit.window="changLimit"
    >
        @include('assets.manage.lost.table')
    </div>

    {{-- Modal --}}
    @include('assets.manage.lost.modalBack')
    @include('assets.manage.lost.modalCancel')
    <div
        @delete="handleDeleteOfMultiModalBackUI($event.detail.id)"
    >
        @include('assets.manage.lost.modalBackMulti')
    </div>
    <div
        @delete="handleDeleteOfMultiModalCancelUI($event.detail.id)"
    >
        @include('assets.manage.lost.modalCancelMulti')
    </div>


</div>