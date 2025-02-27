{{--Thong tin chung--}}
<div class="mb-3">
    <div class="active-link tw-w-fit">Thông tin chung</div>
    <div class="tw-grid tw-grid-cols-4 mt-3 gap-3">
        <div x-show="+data.type === +ORDER_TYPE_CREATE_WITH_PLAN">
            <label>Lập đơn hàng từ<span class="tw-text-red-600 mb-0">*</span></label>
            <input type="text" x-model="data.plan_name" class="form-select" disabled>
        </div>
        <div>
            <label>Nhà cung cấp<span class="tw-text-red-600 mb-0">*</span></label>
            <input type="text" x-model="data.supplier_name" class="form-select" disabled>
        </div>
        <div class="tw-col-span-2">
            <label>Tên đơn hàng<span class="tw-text-red-600 mb-0">*</span></label>
            <input class="form-control" type="text" x-model="data.name"
                   placeholder="Tên đơn hàng" disabled>
        </div>
        <div>
            <label>Người phụ trách mua sắm<span
                    class="tw-text-red-600 mb-0">*</span></label>
            <div>
                @include('common.user.select_single', [
                    'selected' => 'data.purchasing_manager_id',
                    'options' => 'listUser',
                    'placeholder' => 'Chọn người phụ trách',
                    'disabled' => 'true'
                ])
            </div>
        </div>
        <div>
            <label>Ngày giao hàng</label>
            @include('common.datepicker.datepicker', [
                'placeholder' => "Ngày giao hàng",
                'model' => "data.delivery_date",
                'disabled' => 'true'
            ])
        </div>
        <div>
            <label>Địa điểm giao hàng</label>
            <input type="text" class="form-control" x-model="data.delivery_location"
                   placeholder="Địa điểm giao hàng" disabled>
        </div>
        <div>
            <label>Người liên hệ</label>
            <input type="text" class="form-control" x-model="data.contact_person"
                   placeholder="Người liên hệ" disabled>
        </div>
        <div>
            <label>Thông tin liên hệ</label>
            <input type="text" class="form-control" x-model="data.contract_info"
                   placeholder="Thông tin liên hệ" disabled>
        </div>
        <div>
            <label>Thời gian thanh toán</label>
            @include('common.datepicker.datepicker', [
                'placeholder' => "Thời gian thanh toán",
                'model' => "data.payment_time",
                'disabled' => 'true'
            ])
        </div>
        <div>
            <label>Trạng thái</label>
            @include('common.select_custom.simple.select_single', [
                 'selected' => 'data.status',
                 'options' => 'LIST_STATUS_ORDER',
                 'placeholder' => 'Chọn trạng thái',
                 'disabled' => 'true'
            ])
        </div>
    </div>
</div>

{{--  thông tin mặt hàng--}}
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

{{-- thong tin chi phi khac--}}
<div class="d-flex justify-content-between tw-pb-8">
    <div class="col-4">
        <div class="mb-2">
            <label>Chi phí vận chuyển, lắp đặt</label>
            @include('common.input.input_price', [
                'model' => 'data.shipping_costs',
                'placeholder' => "Nhập số",
                'disabled' => 'true'
            ])
        </div>
        <div>
            <label>Chi phí khác</label>
            @include('common.input.input_price', [
                'model' => 'data.other_costs',
                'placeholder' => "Nhập số",
                'disabled' => 'true'
            ])
        </div>
    </div>
    <div class="col-4" x-data="{get totalPrice () {
                                            if (data.shopping_assets_order === undefined) {
                                                return 0
                                            }
                                            let totalPrice = 0
                                            data.shopping_assets_order.filter((item) => {
                                                const vat = +item.price * (+item.vat_rate || 0) / 100
                                                totalPrice = totalPrice + ((+item.price + vat) * item.total)
                                            })

                                            return totalPrice
                                        }}">
        <div>
            <div class="d-flex justify-content-between">
                <label>Tổng tiền hàng</label>
                <label x-text="window.formatCurrencyVND(totalPrice)"></label>
            </div>
            <div class="d-flex justify-content-between">
                <label>Tổng chi phí</label>
                <label
                    x-text="window.formatCurrencyVND(+data.shipping_costs + (+data.other_costs))"></label>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            <label>Tổng tiền thanh toán</label>
            <label
                x-text="window.formatCurrencyVND(totalPrice + (+data.shipping_costs) + (+data.other_costs))"></label>
        </div>
    </div>
</div>
