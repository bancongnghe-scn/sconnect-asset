<div class="modal fade" id="modalUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="title + ' đơn hàng'"></h4>
                <div>
                    <button @click="action === 'create' ? create() : edit()" type="button" class="btn btn-primary">Lưu</button>
                    <button type="button" class="btn btn-sc">Gửi NCC</button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-warning">Quay lại</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="d-flex tw-gap-x-4 h-100">
                    <div class="card col-10">
                        <div class="card-body">
                            {{--Thong tin chung--}}
                            <div class="mb-3">
                                <div class="active-link tw-w-fit">Thông tin chung</div>
                                <div class="tw-grid tw-grid-cols-4 mt-3 gap-3">
                                    <div x-show="+typeCreateOrder === +ORDER_TYPE_CREATE_WITH_PLAN">
                                        <label>Lập đơn hàng từ<span class="tw-text-red-600 mb-0">*</span></label>
                                        <div>
                                            @include('common.select_custom.extent.select_single', [
                                                'selected' => 'data.shopping_plan_company_id',
                                                'options' => 'listShoppingPlanCompany',
                                                'placeholder' => 'Chọn kế hoạch',
                                            ])
                                        </div>
                                    </div>
                                    <div>
                                        <label>Nhà cung cấp<span class="tw-text-red-600 mb-0">*</span></label>
                                        <div>
                                            @include('common.select_custom.extent.select_single', [
                                                'selected' => 'data.supplier_id',
                                                'options' => 'listSupplier',
                                                'placeholder' => 'Chọn nhà cung cấp',
                                            ])
                                        </div>
                                    </div>
                                    <div class="tw-col-span-2">
                                        <label>Tên đơn hàng<span class="tw-text-red-600 mb-0">*</span></label>
                                        <input class="form-control" type="text" x-model="data.name" placeholder="Tên đơn hàng">
                                    </div>
                                    <div>
                                        <label>Người phụ trách mua sắm<span class="tw-text-red-600 mb-0">*</span></label>
                                        <div>
                                            @include('common.select_custom.extent.select_single', [
                                                'selected' => 'data.purchasing_manager_id',
                                                'options' => 'listUser',
                                                'placeholder' => 'Chọn người phụ trách',
                                            ])
                                        </div>
                                    </div>
                                    <div>
                                        <label>Ngày giao hàng</label>
                                        @include('common.datepicker.datepicker', [
                                            'placeholder' => "Ngày giao hàng",
                                            'model' => "data.delivery_date",
                                        ])
                                    </div>
                                    <div>
                                        <label>Địa điểm giao hàng</label>
                                        <input type="text" class="form-control" x-model="data.delivery_location" placeholder="Địa điểm giao hàng">
                                    </div>
                                    <div>
                                        <label>Người liên hệ</label>
                                        <input type="text" class="form-control" x-model="data.contact_person" placeholder="Người liên hệ">
                                    </div>
                                    <div>
                                        <label>Thông tin liên hệ</label>
                                        <input type="text" class="form-control" x-model="data.contract_info" placeholder="Thông tin liên hệ">
                                    </div>
                                    <div>
                                        <label>Thời gian thanh toán</label>
                                        @include('common.datepicker.datepicker', [
                                            'placeholder' => "Thời gian thanh toán",
                                            'model' => "data.payment_time",
                                        ])
                                    </div>
                                    <div>
                                        <label>Trạng thái</label>
                                        @include('common.select_custom.simple.select_single', [
                                             'selected' => 'data.status',
                                             'options' => 'LIST_STATUS_ORDER',
                                             'placeholder' => 'Chọn trạng thái',
                                        ])
                                    </div>
                                </div>
                            </div>

                            {{--  thông tin mặt hàng--}}
                            <div class="mb-3">
                                <div class="mb-3 active-link tw-w-fit">Thông tin mặt hàng</div>
                                <div class="mt-3 overflow-auto custom-scroll tw-max-h-72">
                                    <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                                        <thead>
                                        <tr>
                                            <th>Mã</th>
                                            <th>Tên</th>
                                            <th>Đơn giá</th>
                                            <th>VAT (%)</th>
                                            <th>Tiền VAT</th>
                                            <th>Thành tiền</th>
                                            <th>Loại tài sản</th>
                                            <th>ĐVT</th>
                                            <th>Mô tả</th>
                                            <th>Đơn vị</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(asset,index) in data.shopping_assets_order" :key="index">
                                                <tr>
                                                    <td x-text="asset.code"></td>
                                                    <td>
                                                        <input class="form-control" type="text" x-model="asset.name">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="number" min="1" x-model="asset.price">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="number" min="1" x-model="asset.vat_rate">
                                                    </td>
                                                    <td x-text="window.formatCurrencyVND(+asset.price * (+asset.vat_rate || 0) / 100)"></td>
                                                    <td x-text="window.formatCurrencyVND(+asset.price + (+asset.price * (+asset.vat_rate || 0) / 100))"></td>
                                                    <td x-text="asset.asset_type_id"></td>
                                                    <td x-text="asset.asset_type_id"></td>
                                                    <td x-text="asset.description"></td>
                                                    <td x-text="asset.organization_id"></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between">
                                <div class="col-4">
                                    <div class="mb-2">
                                        <label>Chi phí vận chuyển, lắp đặt</label>
                                        <input class="form-control" type="number" min="0" placeholder="Nhập số" x-model="data.shipping_costs">
                                    </div>
                                    <div>
                                        <label>Chi phí khác</label>
                                        <input class="form-control" type="number" min="0" placeholder="Nhập số" x-model="data.other_costs">
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
                                            <label x-text="window.formatCurrencyVND(+data.shipping_costs + (+data.other_costs))"></label>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <label>Tổng tiền thanh toán</label>
                                        <label x-text="window.formatCurrencyVND(totalPrice + (+data.shipping_costs) + (+data.other_costs))"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card col-2">
{{--                        @include('component.shopping_plan_company.history_comment')--}}
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
