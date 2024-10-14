<div>
    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label tw-font-bold">Mã nhà cung cấp<span class="tw-ml-1 tw-text-red-600">*</span></label>
            <input type="text" class="form-control" x-model="supplier.code" disabled>
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Tên nhà cung cấp<span class="tw-ml-1 tw-text-red-600">*</span></label>
            <input type="text" class="form-control" x-model="supplier.name" placeholder="Nhập tên nhà cung cấp">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Mã số thuế</label>
            <input type="number" class="form-control" x-model="supplier.tax_code" placeholder="Nhập mã số thuế">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label tw-font-bold">Số điện thoại</label>
            <input type="number" class="form-control" x-model="supplier.contact" placeholder="Nhập số điện thoại">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Địa chỉ</label>
            <input type="text" class="form-control" x-model="supplier.address" placeholder="Nhập địa chỉ">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Website</label>
            <input type="text" class="form-control" x-model="supplier.website" placeholder="Nhập địa chỉ website">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label tw-font-bold">Ngành hàng<span class="tw-ml-1 tw-text-red-600">*</span></label>
            <div x-data="{data: []}" x-init="data = listIndustry; $watch('listIndustry', value => data = value)">
                @include('common.select2', ['multiple' => true, 'placeholder' => 'Chọn ngành hàng ...', 'id' => 'industrySelect2'])
            </div>
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Loại tài sản cung ứng<span class="tw-ml-1 tw-text-red-600">*</span></label>
            <div x-data="{data: []}" x-init="data = listAssetType; $watch('listAssetType', value => data = value)">
                @include('common.select2', ['multiple' => true, 'placeholder' => 'Chọn loại tài sản ...', 'id' => 'assetTypeSelect2'])
            </div>
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Ghi chú</label>
            <input type="text" class="form-control" x-model="supplier.description" placeholder="Nhập ghi chú">
        </div>
    </div>
</div>
