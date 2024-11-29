<div class="modal fade" id="idModalUIAppendix" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="title + ' phụ lục'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mb-3">
                    <div class="mb-3 active-link tw-w-fit">Thông tin phụ lục</div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Hợp đồng<label class="tw-text-red-600 mb-0">*</label></label>
                            <template x-if="listContract.length > 0">
                                <span x-data="{values: listContract}">
                                    @include('common.select2.modal.extent.select2_single_modal', [
                                        'placeholder' => 'Chọn hợp đồng',
                                        'model' => 'data.contract_id'
                                    ])
                                </span>
                            </template>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Mã phụ lục<label class="tw-text-red-600 mb-0">*</label></label>
                            <input type="text" class="form-control" x-model="data.code" placeholder="Nhập mã phụ lục">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Tên phụ lục<label class="tw-text-red-600 mb-0">*</label></label>
                            <input type="text" class="form-control" x-model="data.name" placeholder="Nhập tên phụ lục">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Link đính kèm</label>
                            <input type="text" class="form-control" x-model="data.link"  placeholder="Nhập link đính kèm">
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
                        <div class="col-6">
                            <label class="form-label">Ghi chú</label>
                            <textarea class="form-control tw-h-40" x-model="data.description"
                                      placeholder="Nhập ghi chú"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <span class="form-label tw-font-bold"
                              x-text="'Tệp đính kèm('+data.files.length+') dung lượng tối đa 5MB'"></span>
                        <div>
                            <input class="form-control d-none" type="file" id="fileInput" multiple x-ref="fileInput"
                                   @change="handleFiles" accept=".pdf">
                            <label type="button" class="btn btn-sc" for="fileInput">Chọn tệp</label>

                            <div class="mt-2 d-flex flex-column tw-gap-y-2">
                                <template x-for="(file, index) in data.files" :key="index">
                                    <div>
                                        <i class="fa-solid fa-circle-xmark tw-cursor-pointer"
                                           @click="data.files.splice(index, 1)"></i>
                                        <i class="fa-solid fa-file-pdf fa-xl" style="color: #74C0FC;"></i>
                                        <a x-text="file.name" class="tw-text-[#1484FF] tw-w-fit" :href="file.url ?? '#'"
                                           target="_blank"></a>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
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

