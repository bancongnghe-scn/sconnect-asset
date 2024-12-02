<div x-data="tableAssetCancel">
    <div>
        @include('assets.manage.cancel.filter')
    </div>

    <div class="row-12"
        @change-page.window="changePage($event.detail.page)"
        @change-limit.window="changLimit"
    >
        @include('assets.manage.cancel.table')
    </div>
</div>