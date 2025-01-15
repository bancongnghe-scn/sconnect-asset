<div class="modal fade" id="modalUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"
         x-data="{disabled: false}"
         x-effect="disabled = action === 'view'"
    >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="title + ' phiếu nhập kho'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mb-3">
                     <div>
                         <div class="mb-3 active-link tw-w-fit">Thông tin chung</div>
                         <div>
                             <div class="tw-grid tw-grid-cols-2 tw-gap-x-4 mb-3">
                                 <div>
                                     <label>Mã phiếu</label>
                                     <input class="form-control" type="text" x-model="data.code" disabled>
                                 </div>
                                 <div>
                                     <label>Tên phiếu</label>
                                     <input class="form-control" type="text" x-model="data.name" placeholder="Tên phiếu" :disabled="disabled">
                                 </div>
                             </div>

                             <div class="mb-3">
                                 <label>Đơn hàng</label>
                                 @include('common.select2.modal.extent.select2_multiple_modal', [
                                         'model' => 'data.order_ids',
                                         'values' => "action === 'view' ? listOrders : listOrdersDelivered",
                                         'placeholder' => 'Chọn đơn hàng',
                                         'disabled' => 'disabled'
                                 ])
                             </div>

                             <div class="mb-3">
                                 <label>Ghi chú</label>
                                 <textarea class="form-control tw-h-40" x-model="data.description" placeholder="Nhập ghi chú" :disabled="disabled"></textarea>
                             </div>
                         </div>
                     </div>

                    <div>
                        <div class="mb-3 active-link tw-w-fit" x-text="`Tài sản nhập (${data.shopping_assets?.length})`"></div>
                        <div class="mt-3 overflow-auto custom-scroll tw-max-h-56">
                            <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                                <thead>
                                <tr>
                                    <th>Mã</th>
                                    <th>Tên</th>
                                    <th>Bảo hành (tháng)</th>
                                    <th>Seri</th>
                                    <th>Đơn giá</th>
                                    <th>Giá trị</th>
                                    <th>Ngày mua</th>
                                    <th>Loại tài sản</th>
                                    <th>ĐVT</th>
                                    <th>NCC</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="(asset,index) in data.shopping_assets" :key="index">
                                    <tr>
                                        <td x-text="asset.code"></td>
                                        <td>
                                            <input class="form-control tw-w-fit" type="text" x-model="asset.name" :disabled="disabled">
                                        </td>
                                        <td>
                                            <input class="form-control tw-w-[8rem]" type="number" min="1" x-model="asset.warranty_time" :disabled="disabled">
                                        </td>
                                        <td>
                                            <input class="form-control tw-w-[9rem]" type="text" x-model="asset.seri_number" :disabled="disabled">
                                        </td>
                                        <td x-text="asset.price"></td>
                                        <td x-text="asset.price_last"></td>
                                        <td x-text="formatDateVN(asset.date_purchase)"></td>
                                        <td x-text="asset.asset_type_name"></td>
                                        <td x-text="LIST_MEASURE[asset.measure]"></td>
                                        <td x-text="asset.supplier_name"></td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div x-show="action !== 'view'" class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="action === 'create' ? create() : update()" type="button" class="btn btn-sc">Lưu</button>
                <button @click="$('#modalConfirmComplete').modal('show')" type="button" class="btn btn-primary">Hoàn thành</button>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }

    th, td {
        white-space: nowrap;
    }
</style>

