<div>
    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label tw-font-bold">Mã nhà cung cấp<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
            <input type="text" class="form-control" x-model="data.code" disabled>
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Tên nhà cung cấp<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
            <input type="text" class="form-control" x-model="data.name" placeholder="Nhập tên nhà cung cấp">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Mã số thuế</label>
            <input type="number" class="form-control" x-model="data.tax_code" placeholder="Nhập mã số thuế">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label tw-font-bold">Số điện thoại</label>
            <input type="text" class="form-control" x-model="data.contact" placeholder="Nhập số điện thoại">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Người liên hệ</label>
            <input type="text" class="form-control" x-model="data.contract_user" placeholder="Nhập người liên hệ">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Địa chỉ/Website</label>
            <input type="text" class="form-control" x-model="data.address" placeholder="Nhập địa chỉ/Website">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label tw-font-bold">Địa chỉ E-mail</label>
            <input type="text" class="form-control" x-model="data.email" placeholder="Nhập địa chỉ E-mail">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Ngành hàng<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
            <select class="form-select select2" x-model="data.industry_ids" id="industrySelect2" multiple="multiple" data-placeholder="Chọn ngành hàng">
                <template x-for="value in listIndustry" :key="value.id">
                    <option :value="value.id" x-text="value.name"></option>
                </template>
            </select>
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Loại tài sản cung ứng<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
            <select class="form-select select2" x-model="data.asset_type_ids" id="assetTypeSelect2" multiple="multiple" data-placeholder="Chọn loại tài sản">
                <template x-for="value in listAssetType" :key="value.id">
                    <option :value="value.id" x-text="value.name"></option>
                </template>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-8">
            <label class="form-label tw-font-bold">Ghi chú</label>
            <textarea class="form-control tw-h-40" x-model="data.description" placeholder="Nhập ghi chú"></textarea>
        </div>
    </div>
</div>
