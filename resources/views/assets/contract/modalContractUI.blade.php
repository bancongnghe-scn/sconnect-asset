<div class="modal fade" id="modalContractUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="titleModal + ' hợp đồng'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
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
                            <input type="text" class="form-control datepicker" placeholder="Chọn ngày" id="selectSigningDate">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Hiệu lực từ ngày<label class="tw-text-red-600">*</label></label>
                            <input type="text" class="form-control datepicker" placeholder="Chọn ngày" id="selectFrom">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Hiệu lực đến ngày</label>
                            <input type="text" class="form-control datepicker" placeholder="Chọn ngày" id="selectTo">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Người theo dõi<label class="tw-text-red-600">*</label></label>
                            <select class="form-control select2" multiple="multiple" id="selectUserId" data-placeholder="Chọn ...">
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
                        <div class="col-4">
                            <label for="formFileMultiple" class="form-label">Tệp đính kèm (Dung lượng tối đa 5MB)<label class="tw-text-red-600">*</label></label>
                            <input class="form-control" type="file" id="formFileMultiple" multiple x-model="contract.files">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            <label for="formFileMultiple" class="form-label">Ghi chú</label>
                            <textarea class="form-control tw-h-40" x-model="contract.description"></textarea>
                        </div>
                    </div>
                </div>

                <div class="container"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="$dispatch('save')" type="button" class="btn btn-primary">Lưu</button>
            </div>
        </div>
    </div>
</div>

<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>
