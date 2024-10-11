<div>
    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label tw-font-bold">Mã nhà cung cấp<span class="tw-ml-1 tw-text-red-600">*</span></label>
            <input type="text" class="form-control" x-model="supplier.code" disabled>
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Tên nhà cung cấp<span class="tw-ml-1 tw-text-red-600">*</span></label>
            <input type="text" class="form-control" x-model="supplier.name">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Mã số thuế</label>
            <input type="number" class="form-control" x-model="supplier.tax_code">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label tw-font-bold">Số điện thoại</label>
            <input type="number" class="form-control" x-model="supplier.contact">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Địa chỉ</label>
            <input type="text" class="form-control" x-model="supplier.address">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Website</label>
            <input type="text" class="form-control" x-model="supplier.website">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label tw-font-bold">Ngành hàng<span class="tw-ml-1 tw-text-red-600">*</span></label>
            <select class="form-select select2" id="industrySelect2" multiple="multiple" data-placeholder="Chọn ngành hàng ...">
                <template x-for="industry in listIndustry" :key="industry.id">
                    <option :value="industry.id" x-text="industry.name"></option>
                </template>
            </select>
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Loại tài sản cung ứng<span class="tw-ml-1 tw-text-red-600">*</span></label>
            <select class="form-select select2" id="assetTypeSelect2" multiple="multiple" data-placeholder="Chọn loại tài sản ...">
                <template x-for="assetType in listAssetType" :key="assetType.id">
                    <option :value="assetType.id" x-text="assetType.name"></option>
                </template>
            </select>
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Ghi chú</label>
            <input type="text" class="form-control" x-model="supplier.description">
        </div>
    </div>
</div>
