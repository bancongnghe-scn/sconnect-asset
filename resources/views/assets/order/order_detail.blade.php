@extends('layouts.app_v2',[
    'title' => 'Chi tiết đơn hàng'
])

@section('btn-header')
    <a class="btn btn-sc" :href="`/api/order/export/{{$id}}`" download>
        <i class="fa-solid fa-file-export"></i>Gửi NCC
    </a>
    <a class="btn btn-warning" href="/order/list">Quay lại</a>
@endsection

@section('content')
    <div>
        <div class="d-flex tw-gap-x-4 h-100" x-data="order_detail({{$id}})">
            <div class="card col-10 mh-100 overflow-y-auto custom-scroll">
                <div class="card-body">
                    @include('assets.order.order_info_general', ['disabled' => true])

                    <div class="mb-3">
                        <div class="mb-3 active-link tw-w-fit">Thông tin mặt hàng</div>
                        <div class="mt-3 table-responsive custom-scroll">
                            <table id="example2"
                                   class="table table-bordered dataTable dtr-inline"
                                   aria-describedby="example2_info">
                                <thead>
                                <tr>
                                    <th>Tên</th>
                                    <th class="tw-w-40">Đơn giá</th>
                                    <th class="tw-w-24">VAT (%)</th>
                                    <th>Tiền VAT</th>
                                    <th class="text-center" style="width: 4rem">SL</th>
                                    <th>Thành tiền</th>
                                    <th>Loại tài sản</th>
                                    <th>ĐVT</th>
                                    <th>Đơn vị</th>
                                    <th class="tw-w-80">Mô tả</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="(asset,index) in data.shopping_assets_order" :key="index">
                                    <tr x-data="{vat: +asset.price * ((+asset.vat_rate || 0) / 100)}">
                                        <td>
                                            <input class="form-control" type="text" x-model="asset.name" disabled>
                                        </td>
                                        <td>
                                            @include('common.input.input_price',[
                                                'model' => 'asset.price',
                                                'placeholder' => "Nhập số tiền đặt cọc",
                                                'disabled' => 'true'
                                            ])
                                        </td>
                                        <td>
                                            <input class="form-control" type="number" min="1" x-model="asset.vat_rate" disabled>
                                        </td>
                                        <td class="align-middle" x-text="window.formatCurrencyVND(vat)"></td>
                                        <td x-text="asset.total" class="text-center align-middle"></td>
                                        <td class="align-middle" x-text="window.formatCurrencyVND((+asset.price + vat) * asset.total)"></td>
                                        <td class="align-middle" x-text="asset.asset_type_name"></td>
                                        <td class="align-middle" x-text="LIST_MEASURE[asset.measure]"></td>
                                        <td class="align-middle" x-text="asset.organization_name"></td>
                                        <td class="align-middle" x-text="asset.description"></td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr>

                    @include('assets.order.costs_other', ['disabled' => true])
                </div>
            </div>
            <div class="card col-2">
                @include('assets.order.history_comment')
            </div>
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/order/order_detail.js',
        'resources/js/assets/api/order/apiOrder.js',
        'resources/js/app/api/apiUser.js',
        'resources/js/assets/api/apiShoppingAssetOrder.js',
    ])
@endsection
