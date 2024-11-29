<div class="modal fade" id="idModalInfo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chi tiết</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mb-3">
                    <div class="mb-3 active-link tw-w-fit">Thông tin phụ lục</div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Hợp đồng</label>
                            <template x-if="listContract.length > 0">
                                <span x-data="{values: listContract}">
                                    @include('common.select2.modal.extent.select2_single_modal', [
                                        'placeholder' => 'Chọn hợp đồng',
                                        'model' => 'data.contract_id',
                                        'disabled' => true
                                    ])
                                </span>
                            </template>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Mã phụ lục</label>
                            <input type="text" class="form-control" x-model="data.code" disabled>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Tên phụ lục</label>
                            <input type="text" class="form-control" x-model="data.name" disabled>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Link đính kèm</label>
                            <input type="text" class="form-control" x-model="data.link" disabled>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Ngày ký</label>
                            <input type="text" class="form-control datepicker" x-model="data.signing_date" disabled>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Hiệu lực từ ngày</label>
                            <input type="text" class="form-control datepicker" x-model="data.from" disabled>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Hiệu lực đến ngày</label>
                            <input type="text" class="form-control datepicker" x-model="data.to" disabled>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Người theo dõi</label>
                            <template x-if="listUser.length > 0">
                                <span x-data="{values: listUser}">
                                    @include('common.select2.modal.extent.select2_multiple_modal', [
                                        'placeholder' => 'Chọn người theo dõi',
                                        'model' => 'data.user_ids',
                                        'disabled' => true
                                    ])
                                </span>
                            </template>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
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
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>

