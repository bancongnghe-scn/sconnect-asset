<div class="modal fade" id="idModalUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="titleModal + ' phụ lục'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mb-3">
                    <div class="mb-3 active-link tw-w-fit">Thông tin phụ lục</div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Hợp đồng<label class="tw-text-red-600">*</label></label>
                            <select class="form-control" x-model="appendix.contract_id">
                                <option value="">Chọn hợp đồng ...</option>
                                <template x-for="contract in listContract" :key="contract.id">
                                    <option :value="contract.id" x-text="contract.name"></option>
                                </template>
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Mã phụ lục<label class="tw-text-red-600">*</label></label>
                            <input type="text" class="form-control" x-model="appendix.code">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tên phụ lục<label class="tw-text-red-600">*</label></label>
                            <input type="text" class="form-control" x-model="appendix.name">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <label class="form-label">Ngày ký<label class="tw-text-red-600">*</label></label>
                            <input type="text" class="form-control datepicker" placeholder="Chọn ngày" id="selectSigningDate" autocomplete="off" x-model="appendix.signing_date">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Hiệu lực từ ngày<label class="tw-text-red-600">*</label></label>
                            <input type="text" class="form-control datepicker" placeholder="Chọn ngày" id="selectFrom" autocomplete="off" x-model="appendix.from">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Hiệu lực đến ngày</label>
                            <input type="text" class="form-control datepicker" placeholder="Chọn ngày" id="selectTo" autocomplete="off" x-model="appendix.to">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Người theo dõi<label class="tw-text-red-600">*</label></label>
                            <select class="form-control select2" multiple="multiple" id="selectUserId" data-placeholder="Chọn ..." x-model="appendix.user_ids">
                                <template x-for="user in listUser" :key="user.id">
                                    <option :value="user.id" x-text="user.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="formFileMultiple" class="form-label">Ghi chú</label>
                            <textarea class="form-control tw-h-40" x-model="appendix.description"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <span class="form-label tw-font-bold" x-text="'Tệp đính kèm('+appendix.files.length+') dung lượng tối đa 5MB'"></span>
                        <div>
                            <input class="form-control d-none" type="file" id="fileInput" multiple x-ref="fileInput" @change="handleFiles" accept=".pdf">
                            <label type="button" class="btn btn-primary" for="fileInput">Chọn tệp</label>

                            <div class="d-flex flex-wrap mt-2 tw-gap-x-2">
                                <template x-for="(file, index) in appendix.files" :key="index">
                                    <div class="tw-flex gap-x-1">
                                        <i class="fa-solid fa-circle-xmark tw-cursor-pointer" @click="appendix.files.splice(index, 1)"></i>
                                        <a x-text="file.name" class="tw-text-[#1484FF] tw-w-fit" :href="file.url ?? '#'" target="_blank"></a>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="action === 'create' ? create() : edit()" type="button" class="btn btn-primary">Lưu</button>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>

