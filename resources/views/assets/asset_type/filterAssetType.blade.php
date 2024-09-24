<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex flex-row align-items-end tw-gap-x-4">
                <div class="form-group col-3">
                    <label class="tw-font-bold">Tên loại tài sản</label>
                    <input type="text" class="form-control" x-model="filters.name">
                </div>
                <div class="form-group col-3">
                    <label class="tw-font-bold">Nhóm tài sản</label>
                    <select class="form-control select2" multiple="multiple" name="asset_type_group" data-placeholder="Chọn nhóm tài sản ...">
                        <template x-for="assetTypeGroup in listAssetTypeGroup">
                            <option :value="assetTypeGroup.id" x-text="assetTypeGroup.name"></option>
                        </template>
                    </select>
                </div>
                <div class="">
                    <button @click="searchAssetType" type="button" class="btn btn-block btn-primary">Tìm kiếm
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
