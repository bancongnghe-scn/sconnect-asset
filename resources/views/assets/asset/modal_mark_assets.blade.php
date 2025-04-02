<div class="modal fade" id="modalMarkAsset" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="title ?? 'Đánh dấu'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <label class="tw-font-bold">Ngày<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                    @include('common.datepicker.datepicker', [
                        'placeholder'=>"Chọn ngày",
                        'model' => "payment.payment_date"
                    ])
                </div>
                <div>
                    <div class="active-link tw-w-fit" x-text="`Tài sản ${idsMark.length}`"></div>
                    <table id="example2" class="table table-bordered dataTable dtr-inline"
                           aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 4rem">Mã tài sản</th>
                            <th class="text-center" style="width: 8rem">Tên tài sản</th>
                            <th class="text-center" style="width: 10rem">Đơn vị tính</th>
                            <th class="text-center">Người sử dụng</th>
                            <th class="text-center">Vị trí</th>
                            <th class="text-center" style="width: 7rem">Lí do</th>
                            <th class="text-center" style="width: 10rem"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(value,index) in idsMark">
                            <tr>
                                <td x-text="from + index" class="text-center align-middle"></td>
                                <td x-text="value.code" class="align-middle"></td>
                                <td x-text="value.type" class="text-center align-middle"></td>
                                <td x-text="value.name" class="align-middle"></td>
                                <td x-text="value.supplier_name" class="align-middle"></td>
                                <td x-text="value.signing_date" class="text-center align-middle"></td>
                                <td class="align-middle" x-text="formatCurrencyVND(value.contract_value) + ' đ'"></td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="markAssets" type="button" class="btn btn-sc">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
