<div class="modal fade" id="modalUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="title + ' phiếu nhập kho'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <div class="mb-3 active-link tw-w-fit">Thông tin chung</div>
                    <div>
                        <div class="tw-grid tw-grid-cols-4 tw-gap-4 mb-3">
                            <div class="tw-col-span-1">
                                <label>Mã phiếu</label>
                                <input class="form-control" type="text" x-model="data.code" disabled>
                            </div>

                            <div class="tw-col-span-1">
                                <label>Tên phiếu</label>
                                <input class="form-control" type="text" x-model="data.name" placeholder="Tên phiếu">
                            </div>

                            <div class="tw-col-span-2">
                                <label>Đơn hàng</label>
                                @include('common.select_custom.extent.select_multiple', [
                                  'placeholder' => 'Chọn đơn hàng',
                                  'options' => "action === 'view' ? listOrders : listOrdersDelivered",
                                  'selected' => 'data.order_ids',
                                ])
                            </div>

                            <div class="tw-col-span-4">
                                <label>Ghi chú</label>
                                <textarea class="form-control tw-h-40" x-model="data.description" placeholder="Nhập ghi chú"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="mb-3 active-link tw-w-fit" x-text="`Tài sản nhập (${data.shopping_assets?.length})`"></div>
                    <div class="mt-3 overflow-auto custom-scroll" style="max-height: 26rem">
                        <table id="example2" class="table table-bordered dataTable dtr-inline" aria-describedby="example2_info">
                            <thead>
                            <tr>
                                <th style="width: 6rem;" x-show="+data.status === STATUS_IMPORT_WAREHOUSE_COMPLETE">Mã</th>
                                <th style="width: 23rem">Tên</th>
                                <th style="width: 9rem;">Bảo hành (tháng)</th>
                                <th style="width: 12rem;">Seri</th>
                                <th style="width: 8rem;">Đơn giá</th>
                                <th style="width: 8rem;">Giá trị</th>
                                <th style="width: 6rem;">Ngày mua</th>
                                <th>Loại tài sản</th>
                                <th style="width: 4rem;">ĐVT</th>
                                <th style="width: 30rem">NCC</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template x-for="(asset,index) in data.shopping_assets" :key="index">
                                <tr>
                                    <td x-show="+data.status === STATUS_IMPORT_WAREHOUSE_COMPLETE"
                                        x-text="asset.code"
                                        class="align-middle"
                                    ></td>
                                    <td>
                                        <input class="form-control" type="text" x-model="asset.name">
                                    </td>
                                    <td>
                                        <input class="form-control" type="number" min="1" x-model="asset.warranty_time">
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" x-model="asset.seri_number">
                                    </td>
                                    <td class="align-middle" x-text="formatCurrencyVND(asset.price)"></td>
                                    <td class="align-middle" x-text="formatCurrencyVND(asset.price_last)"></td>
                                    <td class="align-middle" x-text="formatDateVN(asset.date_purchase)"></td>
                                    <td class="align-middle" x-text="asset.asset_type_name"></td>
                                    <td class="align-middle" x-text="LIST_MEASURE[asset.measure]"></td>
                                    <td class="align-middle" x-text="asset.supplier_name"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="action === 'create' ? create() : update()" type="button" class="btn btn-sc">Lưu</button>
                <button @click="$('#modalConfirmComplete').modal('show')" type="button" class="btn btn-primary">Hoàn thành</button>
            </div>
        </div>
    </div>
</div>
<style>
    th, td {
        white-space: nowrap;
    }
</style>

