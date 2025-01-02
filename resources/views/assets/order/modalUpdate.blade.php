<div class="modal fade" id="modalUpdate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="title + ' đơn hàng'"></h4>
                <div>
                    <button x-show="action === 'update'" @click="update()" type="button" class="btn btn-primary">Lưu
                    </button>
                    <button type="button" class="btn btn-sc">Gửi NCC</button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-warning">Quay lại</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="d-flex tw-gap-x-4 h-100">
                    <div class="card col-10 mh-100 overflow-y-auto custom-scroll">
                        <div class="card-body" x-data="{disabled: false}" x-effect="disabled = action === 'view'">
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
                                               placeholder="Tên đơn hàng" :disabled="disabled">
                                    </div>
                                    <div>
                                        <label>Người phụ trách mua sắm<span
                                                class="tw-text-red-600 mb-0">*</span></label>
                                        <div>
                                            @include('common.select_custom.extent.select_single', [
                                                'selected' => 'data.purchasing_manager_id',
                                                'options' => 'listUser',
                                                'placeholder' => 'Chọn người phụ trách',
                                                'disabled' => 'disabled'
                                            ])
                                        </div>
                                    </div>
                                    <div>
                                        <label>Ngày giao hàng</label>
                                        @include('common.datepicker.datepicker', [
                                            'placeholder' => "Ngày giao hàng",
                                            'model' => "data.delivery_date",
                                            'disabled' => 'disabled'
                                        ])
                                    </div>
                                    <div>
                                        <label>Địa điểm giao hàng</label>
                                        <input type="text" class="form-control" x-model="data.delivery_location"
                                               placeholder="Địa điểm giao hàng" :disabled="disabled">
                                    </div>
                                    <div>
                                        <label>Người liên hệ</label>
                                        <input type="text" class="form-control" x-model="data.contact_person"
                                               placeholder="Người liên hệ" :disabled="disabled">
                                    </div>
                                    <div>
                                        <label>Thông tin liên hệ</label>
                                        <input type="text" class="form-control" x-model="data.contract_info"
                                               placeholder="Thông tin liên hệ" :disabled="disabled">
                                    </div>
                                    <div>
                                        <label>Thời gian thanh toán</label>
                                        @include('common.datepicker.datepicker', [
                                            'placeholder' => "Thời gian thanh toán",
                                            'model' => "data.payment_time",
                                            'disabled' => 'disabled'
                                        ])
                                    </div>
                                    <div>
                                        <label>Trạng thái</label>
                                        @include('common.select_custom.simple.select_single', [
                                             'selected' => 'data.status',
                                             'options' => 'listStatus',
                                             'placeholder' => 'Chọn trạng thái',
                                             'disabled' => 'disabled'
                                        ])
                                    </div>
                                </div>
                            </div>

                            {{--  thông tin mặt hàng--}}
                            <div class="mb-3">
                                <div class="mb-3 active-link tw-w-fit">Thông tin mặt hàng</div>
                                <div class="mt-3">
                                    <template x-if="+data.type === ORDER_TYPE_CREATE_WITH_PLAN">
                                        <table id="example2"
                                               class="table table-bordered table-hover dataTable dtr-inline"
                                               aria-describedby="example2_info">
                                            <thead>
                                            <tr>
                                                <th>Mã</th>
                                                <th>Tên</th>
                                                <th class="tw-w-40">Đơn giá</th>
                                                <th class="tw-w-24">VAT (%)</th>
                                                <th>Tiền VAT</th>
                                                <th>Thành tiền</th>
                                                <th>Loại tài sản</th>
                                                <th>ĐVT</th>
                                                <th>Đơn vị</th>
                                                <th class="tw-w-80">Mô tả</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <template x-for="(asset,index) in data.shopping_assets_order" :key="index">
                                                <tr>
                                                    <td x-text="asset.code"></td>
                                                    <td>
                                                        <input class="form-control" type="text" x-model="asset.name"
                                                               :disabled="disabled">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="number" min="1"
                                                               x-model="asset.price" :disabled="disabled">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="number" min="1"
                                                               x-model="asset.vat_rate" :disabled="disabled">
                                                    </td>
                                                    <td x-text="window.formatCurrencyVND(+asset.price * (+asset.vat_rate || 0) / 100)"></td>
                                                    <td x-text="window.formatCurrencyVND(+asset.price + (+asset.price * (+asset.vat_rate || 0) / 100))"></td>
                                                    <td x-text="asset.asset_type_name"></td>
                                                    <td x-text="LIST_MEASURE[asset.measure]"></td>
                                                    <td x-text="asset.organization_name"></td>
                                                    <td x-text="asset.description"></td>
                                                </tr>
                                            </template>
                                            </tbody>
                                        </table>
                                    </template>
                                    <template x-if="+data.type === ORDER_TYPE_CREATE_WITH_NOT_PLAN">
                                        <table id="example2"
                                               class="table table-bordered table-hover dataTable dtr-inline"
                                               aria-describedby="example2_info">
                                            <thead>
                                            <tr>
                                                <th>Mã</th>
                                                <th class="tw-min-w-60">Tên</th>
                                                <th class="tw-min-w-40">Đơn giá</th>
                                                <th>VAT (%)</th>
                                                <th>Tiền VAT</th>
                                                <th>Thành tiền</th>
                                                <th class="tw-min-w-52">Loại tài sản</th>
                                                <th>ĐVT</th>
                                                <th class="tw-min-w-60">Đơn vị</th>
                                                <th class="tw-min-w-60">Mô tả</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <template x-for="(asset,index) in data.shopping_assets_order" :key="index">
                                                <tr>
                                                    <td x-text="asset.code"></td>
                                                    <td>
                                                        <input class="form-control" type="text" x-model="asset.name"
                                                               :disabled="disabled">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="number" min="1"
                                                               x-model="asset.price" :disabled="disabled">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="number" min="1"
                                                               x-model="asset.vat_rate" :disabled="disabled">
                                                    </td>
                                                    <td x-text="window.formatCurrencyVND(+asset.price * (+asset.vat_rate || 0) / 100)"></td>
                                                    <td x-text="window.formatCurrencyVND(+asset.price + (+asset.price * (+asset.vat_rate || 0) / 100))"></td>
                                                    <td>
                                                        @include('common.select_custom.extent.select_single', [
                                                             'selected' => 'asset.asset_type_id',
                                                             'options' => 'listAssetType',
                                                             'placeholder' => 'Loại tài sản',
                                                             'disabled' => 'disabled',
                                                        ])
                                                    </td>
                                                    <td x-text="listAssetType.find((item) => +item.id === +asset.asset_type_id)?.measure">
                                                    </td>
                                                    <td>
                                                        @include('common.select_custom.extent.select_single', [
                                                             'selected' => 'asset.organization_id',
                                                             'options' => 'listOrganization',
                                                             'placeholder' => 'Đơn vị',
                                                             'disabled' => 'disabled',
                                                        ])
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="text"
                                                               x-model="asset.description" :disabled="disabled">
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <button class="border-0 bg-body"
                                                                @click="data.shopping_assets_order.splice(index, 1)">
                                                            <i class="fa-regular fa-trash-can"
                                                               style="color: #cd1326;"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                            </tbody>
                                        </table>
                                    </template>
                                </div>
                                <button x-show="+data.type === ORDER_TYPE_CREATE_WITH_NOT_PLAN && action !== 'view'"
                                        class="btn btn-sm btn-sc mt-3" @click="addRows()">Thêm hàng
                                </button>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between tw-pb-8">
                                <div class="col-4">
                                    <div class="mb-2">
                                        <label>Chi phí vận chuyển, lắp đặt</label>
                                        <input class="form-control" type="number" min="0" placeholder="Nhập số"
                                               x-model="data.shipping_costs" :disabled="disabled">
                                    </div>
                                    <div>
                                        <label>Chi phí khác</label>
                                        <input class="form-control" type="number" min="0" placeholder="Nhập số"
                                               x-model="data.other_costs" :disabled="disabled">
                                    </div>
                                </div>
                                <div class="col-4" x-data="{get totalPrice () {
                                            let totalPrice = 0
                                            data.shopping_assets_order.filter((item) => {
                                                totalPrice = totalPrice + (+item.price + (+item.price * (+item.vat_rate || 0) / 100))
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
                        </div>
                    </div>
                    <div class="card col-2">
                        @include('component.history_comment_v2')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>
