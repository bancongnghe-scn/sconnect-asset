<div class="modal fade" id="modalContractUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="titleModal + ' hợp đồng'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mb-3">
                    <div class="mb-3 active-link tw-w-fit">Thông tin chung</div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Mã hợp đồng<label class="tw-text-red-600">*</label></label>
                            <input type="text" class="form-control" x-model="contract.code">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Loại hợp đồng<label class="tw-text-red-600">*</label></label>
                            <select class="form-control" x-model="contract.type">
                                <option value="">Chọn loại hợp đồng ...</option>
                                <template x-for="(value, key) in listTypeContract" :key="key">
                                    <option :value="key" x-text="value"></option>
                                </template>
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Tên hợp đồng<label class="tw-text-red-600">*</label></label>
                            <input type="text" class="form-control" x-model="contract.name">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Nhà cung cấp<label class="tw-text-red-600">*</label></label>
                            <select class="form-control" x-model="contract.supplier_id">
                                <option value="">Chọn nhà cung cấp ...</option>
                                <template x-for="supplier in listSupplier" :key="supplier.id">
                                    <option :value="supplier.id" x-text="supplier.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Ngày ký<label class="tw-text-red-600">*</label></label>
                            <input type="text" class="form-control datepicker" placeholder="Chọn ngày" id="selectSigningDate" autocomplete="off" x-model="contract.signing_date">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Hiệu lực từ ngày<label class="tw-text-red-600">*</label></label>
                            <input type="text" class="form-control datepicker" placeholder="Chọn ngày" id="selectFrom" autocomplete="off" x-model="contract.from">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Hiệu lực đến ngày</label>
                            <input type="text" class="form-control datepicker" placeholder="Chọn ngày" id="selectTo" autocomplete="off" x-model="contract.to">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Người theo dõi<label class="tw-text-red-600">*</label></label>
                            <select class="form-control select2" multiple="multiple" id="selectUserId" data-placeholder="Chọn ..." x-model="contract.user_ids">
                                <template x-for="user in listUser" :key="user.id">
                                    <option :value="user.id" x-text="user.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Tổng giá trị hợp đồng</label>
                            <input type="number" class="form-control" placeholder="Nhập ..." x-model="contract.contract_value">
                        </div>
                        <div class="col-5">
                            <label for="formFileMultiple" class="form-label">Ghi chú</label>
                            <textarea class="form-control tw-h-40" x-model="contract.description"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <span class="form-label tw-font-bold" x-text="'Tệp đính kèm('+contract.files.length+') dung lượng tối đa 5MB'"></span>
                        <div>
                            <input class="form-control d-none" type="file" id="fileInput" multiple x-ref="fileInput" @change="handleFiles" accept=".pdf">
                            <label type="button" class="btn btn-primary" for="fileInput">Chọn tệp</label>

                            <div class="d-flex flex-wrap mt-2 tw-gap-x-2">
                                <template x-for="(file, index) in contract.files" :key="index">
                                    <div class="tw-flex gap-x-1">
                                        <i class="fa-solid fa-circle-xmark tw-cursor-pointer" @click="contract.files.splice(index, 1)"></i>
                                        <a x-text="file.name" class="tw-text-[#1484FF] tw-w-fit" :href="file.url ?? '#'" target="_blank"></a>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container mb-3">
                    <div class="mb-3 active-link tw-w-fit">Thanh toán</div>
                    <div class="mb-3 tw-max-h-60 overflow-y-scroll">
                        <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
                               aria-describedby="example2_info">
                            <thead>
                                <tr>
                                    <th rowspan="1" colspan="1">Lần thanh toán</th>
                                    <th rowspan="1" colspan="1">Ngày thanh toán</th>
                                    <th rowspan="1" colspan="1">Số tiền</th>
                                    <th rowspan="1" colspan="1">Nội dung thanh toán</th>
                                    <th rowspan="1" colspan="1" class="col-2 text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(payment, index) in contract.payments">
                                    <tr>
                                        <td x-text="'Lần ' + (index + 1)"></td>
                                        <td>
                                            <input type="text" class="form-control datepicker" placeholder="Chọn ngày" name="selectPaymentDate" :id="index" x-model="payment.payment_date">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" placeholder="Số tiền thanh toán" x-model="payment.money">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" x-model="payment.description">
                                        </td>
                                        <td class="text-center align-middle">
                                            <button class="border-0 bg-body" @click="contract.payments.splice(index, 1)">
                                                <i class="fa-solid fa-trash" style="color: #cd1326;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <button @click="addRowPayment" type="button" class="btn btn-primary tw-w-fit">Thêm hàng</button>
                </div>

                <template x-if="contract.appendix">
                    <div class="container">
                        <div class="mb-3 active-link tw-w-fit">Phụ lục hợp đồng</div>
                    </div>
                </template>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="action === 'create' ? createContract() : editContract()" type="button" class="btn btn-primary">Lưu</button>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>

