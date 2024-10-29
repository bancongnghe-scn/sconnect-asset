<div class="modal fade" id="idModalInfoContract" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <input type="text" class="form-control" x-model="data.code" disabled>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Loại hợp đồng</label>
                            <select class="form-control" x-model="data.type" disabled>
                                <template x-for="(value, key) in listTypeContract" :key="key">
                                    <option :value="key" x-text="value"></option>
                                </template>
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Tên hợp đồng</label>
                            <input type="text" class="form-control" x-model="data.name" disabled>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Nhà cung cấp</label>
                            <select class="form-select select2" disabled>
                                <template x-for="value in listSupplier" :key="value.id">
                                    <option :value="value.id" x-text="value.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Ngày ký</label>
                            <input type="text" class="form-control" x-model="data.signing_date" disabled>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Hiệu lực từ ngày</label>
                            <input type="text" class="form-control" x-model="data.from" disabled>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Hiệu lực đến ngày</label>
                            <input type="text" class="form-control" x-model="data.to" disabled>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Người theo dõi</label>
                            <select class="form-select select2" x-model="data.user_ids" multiple="multiple" disabled>
                                <template x-for="value in listUser" :key="value.id">
                                    <option :value="value.id" x-text="value.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Tổng giá trị hợp đồng</label>
                            <input type="number" class="form-control" x-model="data.contract_value" disabled>
                        </div>
                        <div class="col-5">
                            <label class="form-label">Ghi chú</label>
                            <textarea class="form-control tw-h-40" x-model="data.description" disabled></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <span class="form-label tw-font-bold" x-text="'Tệp đính kèm('+data.files.length+') dung lượng tối đa 5MB'"></span>
                        <div class="mt-2 d-flex flex-column tw-gap-y-2">
                            <template x-for="(file, index) in data.files" :key="index">
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
                                <template x-for="(payment, index) in data.payments">
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

                <div class="container">
                    <div class="mb-3 active-link tw-w-fit">Phụ lục hợp đồng</div>
                    <div class="tw-max-h-60 overflow-y-scroll">
                        <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
                               aria-describedby="example2_info">
                            <thead>
                            <tr>
                                <th>Mã phụ lục</th>
                                <th>Tên phụ lục</th>
                                <th>Ngày ký</th>
                                <th>Ngày hiệu lực</th>
                                <th>Nội dung thay đổi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template x-for="(appendix, index) in data.appendixes">
                                <tr>
                                    <td x-text="appendix.code"></td>
                                    <td x-text="appendix.name"></td>
                                    <td x-text="appendix.signing_date"></td>
                                    <td x-text="appendix.from"></td>
                                    <td x-text="appendix.description"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


