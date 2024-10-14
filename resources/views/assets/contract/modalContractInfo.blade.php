<div class="modal fade" id="modalContractInfo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin hợp đồng</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mb-3">
                    <div class="mb-3 active-link tw-w-fit">Thông tin chung</div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Mã hợp đồng</label>
                            <input type="text" class="form-control" x-model="contract.code" disabled>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Loại hợp đồng</label>
                            <select class="form-control" x-model="contract.type" disabled>
                                <template x-for="(value, key) in listTypeContract" :key="key">
                                    <option :value="key" x-text="value"></option>
                                </template>
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Tên hợp đồng</label>
                            <input type="text" class="form-control" x-model="contract.name" disabled>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Nhà cung cấp</label>
                            <select class="form-control" x-model="contract.supplier_id" disabled>
                                <template x-for="supplier in listSupplier" :key="supplier.id">
                                    <option :value="supplier.id" x-text="supplier.name"></option>
                                </template>
                            </select>
                            <div x-data="{data: []}" x-init="data = listSupplier; $watch('listSupplier', value => data = value)">
                                @include('common.select2' , ['multiple' => true, 'id' => 'filterContract', 'placeholder' => 'Chọn hợp đồng'])
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Ngày ký</label>
                            <input type="text" class="form-control" x-model="contract.signing_date" disabled>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Hiệu lực từ ngày</label>
                            <input type="text" class="form-control" x-model="contract.from" disabled>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Hiệu lực đến ngày</label>
                            <input type="text" class="form-control" x-model="contract.to" disabled>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Người theo dõi</label>
                            <div x-data="{data: []}" x-init="data = listUser; $watch('listUser', value => data = value)">
                                @include('common.select2' , ['multiple' => true, 'model' => 'contract.user_ids', 'disabled' => true])
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Tổng giá trị hợp đồng</label>
                            <input type="number" class="form-control" x-model="contract.contract_value" disabled>
                        </div>
                        <div class="col-5">
                            <label for="formFileMultiple" class="form-label">Ghi chú</label>
                            <textarea class="form-control tw-h-40" x-model="contract.description" disabled></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <span class="form-label tw-font-bold" x-text="'Tệp đính kèm('+contract.files.length+') dung lượng tối đa 5MB'"></span>
                        <div class="mt-2 d-flex flex-column tw-gap-y-2">
                            <template x-for="(file, index) in contract.files" :key="index">
                                <div>
                                    <i class="fa-solid fa-file-pdf fa-xl" style="color: #74C0FC;"></i>
                                    <a x-text="file.name" class="tw-text-[#1484FF] tw-w-fit" :href="file.url ?? '#'" target="_blank"></a>
                                </div>
                            </template>
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
                            </tr>
                            </thead>
                            <tbody>
                                <template x-for="(payment, index) in contract.payments">
                                    <tr>
                                        <td x-text="'Lần ' + (index + 1)"></td>
                                        <td>
                                            <input type="text" class="form-control" x-model="payment.payment_date" disabled>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" x-model="payment.money" disabled>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" x-model="payment.description" disabled>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <template x-if="contract.appendix">
                    <div class="container">
                        <div class="mb-3 active-link tw-w-fit">Phụ lục hợp đồng</div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>


