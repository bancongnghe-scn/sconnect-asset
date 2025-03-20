<div x-data="tableAssetLost">
    <div>
        @include('assets.manage.lost.filter')
    </div>

    <div>
        @include('assets.manage.lost.table')
    </div>

    {{-- Modal --}}
    @include('assets.manage.lost.modalBack')
    @include('assets.manage.lost.modalCancel')
    @include('assets.manage.lost.modalBackMulti')
    @include('assets.manage.lost.modalCancelMulti')
</div>

@vite([
    'resources/js/assets/manage/lost/assetLost.js',
    'resources/js/assets/manage/lost/api/apiAssetLost.js',
])
