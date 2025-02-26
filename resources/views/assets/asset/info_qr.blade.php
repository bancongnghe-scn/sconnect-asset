<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Chi tiết</title>
    <link rel="icon" type="image/png" href="/images/fav-sc-icon.png"/>

    <script src="/js/const.js"></script>
    <script src='{{ asset('/js/jquery.js') }}'></script>
    <script src='{{ asset('/js/select2.full.js') }}'></script>
    @vite([
           'resources/css/app.css',
           'resources/sass/app.scss',
           'resources/css/custom.css',
           'resources/js/app.js',
           'resources/js/assets/api/asset/apiAsset.js'
    ])
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div x-data="asset_info" class="tw-p-[25px]">
    <div class="mb-3 active-link tw-w-fit">Thông tin chung</div>
    <div class="card card-body">
        <div>
            <span class="tw-font-bold">Mã tài sản:</span>
            <span x-text="data.code"></span>
        </div>
        <div>
            <span class="tw-font-bold">Tên tài sản:</span>
            <span x-text="data.name"></span>
        </div>
        <div>
            <span class="tw-font-bold">Loại tài sản:</span>
            <span x-text="data.asset_type"></span>
        </div>
        <div>
            <span class="tw-font-bold">Đơn vị tính:</span>
            <span x-text="LIST_MEASURE[data.measure]"></span>
        </div>
        <div>
            <span class="tw-font-bold">Nhà cung cấp:</span>
            <span x-text="data.supplier"></span>
        </div>
        <div>
            <span class="tw-font-bold">Giá:</span>
            <span x-text="formatCurrencyVND(data.price)"></span>
        </div>
        <div>
            <span class="tw-font-bold">Ngày mua:</span>
            <span x-text="formatDateVN(data.date_purchase)"></span>
        </div>
        <div>
            <span class="tw-font-bold">Số Serial:</span>
            <span x-text="data.seri_number"></span>
        </div>
        <div>
            <span class="tw-font-bold">Vị trí tài sản:</span>
            <span x-text="LIST_LOCATION_ASSET[data.location]"></span>
        </div>
        <div>
            <span class="tw-font-bold">Trạng thái:</span>
            <span x-text="LIST_STATUS_ASEET[data.status]"></span>
        </div>
    </div>
</div>
</body>
</html>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('asset_info', () => ({
            init() {
                this.getAssetInfo()
            },

            id: {{$id}},
            data: [],

            async getAssetInfo() {
                this.loading = true

                try {
                    const response = await window.apiGetAssetInfo(this.id)
                    if (!response.success) {
                        toast.error(response.message)
                        return
                    }

                    this.data = response.data.data
                } catch (e) {
                    toast.error(e)
                } finally {
                    this.loading = false
                }
            }
        }))
    })
</script>
