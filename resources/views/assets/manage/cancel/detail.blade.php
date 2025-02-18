<div x-data="tableAssetCancel">
    <div>
        @include('assets.manage.cancel.filter')
    </div>

    <div class="row-12"
        @change-page.window="changePage($event.detail.page)"
        @change-limit.window="changeLimit"
    >
        @include('assets.manage.cancel.table')
    </div>
</div>
@vite([
    'resources/js/assets/manage/cancel/assetCancel.js',
    'resources/js/assets/manage/cancel/api/apiAssetCancel.js',
])
