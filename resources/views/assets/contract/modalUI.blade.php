<div class="modal fade" id="idModalUIContract" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <span x-data="{values: listTypeContract}">
                                @include('common.select2.modal.simple.select2_single_modal', ['placeholder' => 'Chọn loại hợp đồng', 'model' => 'data.type'])
                            </span>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Tên hợp đồng<label class="tw-text-red-600 mb-0">*</label></label>
                            <input type="text" class="form-control" x-model="data.name" placeholder="Nhập tên hợp đồng">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Nhà cung cấp<label class="tw-text-red-600 mb-0">*</label></label>
                            <template x-if="listSupplier.length > 0">
                                <span x-data="{values: listSupplier}">
                                    @include('common.select2.modal.extent.select2_single_modal', [
                                        'placeholder' => 'Chọn nhà cung cấp', 'model' => 'data.supplier_id'
                                    ])
                                </span>
                            </template>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Ngày ký<label class="tw-text-red-600 mb-0">*</label></label>
                            @include('common.datepicker.datepicker', ['placeholder'=>"Chọn ngày ký", 'model' => "data.signing_date"])
                        </div>
                        <div class="col-3">
                            <label class="form-label">Hiệu lực từ ngày<label
                                    class="tw-text-red-600 mb-0">*</label></label>
                            @include('common.datepicker.datepicker', ['placeholder'=>"Chọn ngày bắt đầu hiệu lực", 'model' => "data.from"])
                        </div>
                        <div class="col-3">
                            <label class="form-label">Hiệu lực đến ngày</label>
                            @include('common.datepicker.datepicker', ['placeholder'=>"Chọn ngày kết thúc hiệu lực", 'model' => "data.to"])
                        </div>
                        <div class="col-3">
                            <label class="form-label">Người theo dõi<label
                                    class="tw-text-red-600 mb-0">*</label></label>
                            <template x-if="listUser.length > 0">
                                <span x-data="{values: listUser}">
                                    @include('common.select2.modal.extent.select2_multiple_modal', [
                                        'placeholder' => 'Chọn người theo dõi',
                                        'model' => 'data.user_ids'
                                    ])
                                </span>
                            </template>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Tổng giá trị hợp đồng</label>
                            <input type="number" class="form-control" placeholder="Nhập tổng giá trị hợp đồng"
                                   x-model="data.contract_value">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Link đính kèm</label>
                            <input type="text" class="form-control" x-model="data.contract_link"
                                   placeholder="Nhập link đính kèm">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Ghi chú</label>
                            <input type="text" class="form-control" x-model="data.description"
                                   placeholder="Nhập ghi chú">
                        </div>
                    </div>
                    <div class="row">
                        <span class="form-label tw-font-bold"
                              x-text="'Tệp đính kèm('+data.files.length+') dung lượng tối đa 5MB'"></span>
                        <div>
                            <input class="form-control d-none" type="file" id="fileInputContract" multiple
                                   x-ref="fileInputContract" @change="handleFilesContract" accept=".pdf">
                            <label type="button" class="btn btn-sc" for="fileInputContract">Chọn tệp</label>

                            <div class="mt-2 d-flex flex-column tw-gap-y-2">
                                <template x-for="(file, index) in data.files" :key="index">
                                    <div>
                                        <button @click="data.files.splice(index, 1)" class="border-0 bg-white"><i
                                                class="fa-solid fa-circle-xmark tw-cursor-pointer"></i></button>
                                        <i class="fa-solid fa-file-pdf fa-xl" style="color: #74C0FC;"></i>
                                        <a x-text="file.name" class="tw-text-[#1484FF] tw-w-fit" :href="file.url ?? '#'"
                                           target="_blank"></a>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container mb-3">
                    <div class="mb-3 active-link tw-w-fit">Thanh toán</div>
                    <div class="tw-max-h-60 overflow-y-scroll custom-scroll">
                        <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
                               aria-describedby="example2_info">
                            <thead>
                            <tr>
                                <th class="tw-w-32">Lần thanh toán</th>
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
                                        @include('common.datepicker.datepicker', [
                                            'placeholder'=>"Chọn ngày thanh toán",
                                            'model' => "payment.payment_date",
                                            'id' => 'index'
                                        ])
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="Nhập số tiền thanh toán"
                                               x-model="payment.money">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" x-model="payment.description"
                                               placeholder="Nhập nội dung thanh toán">
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="border-0 bg-body" @click="data.payments.splice(index, 1)">
                                            <i class="fa-regular fa-trash-can" style="color: #cd1326;"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <button @click="addRowPayment" type="button" class="btn btn-sm btn-sc">Thêm hàng</button>
                </div>

                <template x-if="action === 'update'">
                    <div class="container">
                        <div class="mb-3 active-link tw-w-fit">Phụ lục hợp đồng</div>
                        <div class="tw-max-h-60 overflow-y-scroll custom-scroll">
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

