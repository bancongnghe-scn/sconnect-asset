<div x-data="tableAssetCancel">
    <div>
        @include('assets.manage.cancel.filter')
    </div>

    <div class="row-12">
        @include('assets.manage.cancel.table')
    </div>
</div>
@vite([
    'resources/js/assets/manage/cancel/assetCancel.js',
    'resources/js/assets/manage/cancel/api/apiAssetCancel.js',
])
