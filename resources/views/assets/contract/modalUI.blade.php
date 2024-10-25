<div class="modal fade" id="idModalUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="title + ' hợp đồng'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mb-3">
                    <div class="mb-3 active-link tw-w-fit">Thông tin chung</div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Mã hợp đồng<label class="tw-text-red-600 mb-0">*</label></label>
                            <input type="text" class="form-control" x-model="data.code" placeholder="Nhập mã hợp đồng">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Loại hợp đồng<label class="tw-text-red-600 mb-0">*</label></label>
                            <select class="form-control select2" x-model="data.type" id="selectContractType">
                                <option value="">Chọn loại hợp đồng</option>
                                <template x-for="(value, key) in listTypeContract">
                                    <option :value="key" x-text="value"></option>
                                </template>
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Tên hợp đồng<label class="tw-text-red-600 mb-0">*</label></label>
                            <input type="text" class="form-control" x-model="data.name" placeholder="Nhập tên hợp đồng">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Nhà cung cấp<label class="tw-text-red-600 mb-0">*</label></label>
                            <select class="form-select select2" x-model="data.supplier_id" id="selectSupplier">
                                <option value="">Chọn nhà cung cấp</option>
                                <template x-for="value in listSupplier" :key="value.id">
                                    <option :value="value.id" x-text="value.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Ngày ký<label class="tw-text-red-600 mb-0">*</label></label>
                            @include('common.datepicker', ['placeholder'=>"Chọn ngày ký", 'id'=>"selectSigningDate", 'model' => "data.signing_date"])
                        </div>
                        <div class="col-3">
                            <label class="form-label">Hiệu lực từ ngày<label class="tw-text-red-600 mb-0">*</label></label>
                            @include('common.datepicker', ['placeholder'=>"Chọn ngày bắt đầu", 'id'=>"selectFrom", 'model' => "data.from"])
                        </div>
                        <div class="col-3">
                            <label class="form-label">Hiệu lực đến ngày</label>
                            @include('common.datepicker', ['placeholder'=>"Chọn ngày kết thúc", 'id'=>"selectTo", 'model' => "data.to"])
                        </div>
                        <div class="col-3">
                            <label class="form-label">Người theo dõi<label class="tw-text-red-600 mb-0">*</label></label>
                            <select class="form-select select2" multiple="multiple" id="selectUserId" data-placeholder="Chọn người theo dõi" x-model="data.user_ids">
                                <template x-for="user in listUser" :key="user.id">
                                    <option :value="user.id" x-text="user.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Tổng giá trị hợp đồng</label>
                            <input type="number" class="form-control" placeholder="Nhập tổng giá trị hợp đồng" x-model="data.contract_value">
                        </div>
                        <div class="col-5">
                            <label for="formFileMultiple" class="form-label">Ghi chú</label>
                            <textarea class="form-control tw-h-40" x-model="data.description" placeholder="Nhập ghi chú"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <span class="form-label tw-font-bold" x-text="'Tệp đính kèm('+data.files.length+') dung lượng tối đa 5MB'"></span>
                        <div>
                            <input class="form-control d-none" type="file" id="fileInput" multiple x-ref="fileInput" @change="handleFiles" accept=".pdf">
                            <label type="button" class="btn btn-sc" for="fileInput">Chọn tệp</label>

                            <div class="mt-2 d-flex flex-column tw-gap-y-2">
                                <template x-for="(file, index) in data.files" :key="index">
                                    <div>
                                        <button @click="data.files.splice(index, 1)" class="border-0 bg-white"><i class="fa-solid fa-circle-xmark tw-cursor-pointer"></i></button>
                                        <i class="fa-solid fa-file-pdf fa-xl" style="color: #74C0FC;"></i>
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
                                    <th>Lần thanh toán</th>
                                    <th>Ngày thanh toán</th>
                                    <th>Số tiền</th>
                                    <th>Nội dung thanh toán</th>
                                    <th class="col-2 text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(payment, index) in data.payments">
                                    <tr>
                                        <td x-text="'Lần ' + (index + 1)"></td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="selectPaymentDate"
                                                       placeholder="Chọn ngày thanh toán" autocomplete="off" x-model="payment.payment_date" :id="index">
                                                <span class="input-group-text"><i class="fa-regular fa-calendar-days"></i></span>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" placeholder="Nhập số tiền thanh toán" x-model="payment.money">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" x-model="payment.description" placeholder="Nhập nội dung thanh toán">
                                        </td>
                                        <td class="text-center align-middle">
                                            <button class="border-0 bg-body" @click="data.payments.splice(index, 1)">
                                                <i class="fa-solid fa-trash" style="color: #cd1326;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <button @click="addRowPayment" type="button" class="btn btn-sc tw-w-fit">Thêm hàng</button>
                </div>

                <template x-if="data.appendix">
                    <div class="container">
                        <div class="mb-3 active-link tw-w-fit">Phụ lục hợp đồng</div>
                    </div>
                </template>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="action === 'create' ? create() : edit()" type="button" class="btn btn-sc">Lưu</button>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>

