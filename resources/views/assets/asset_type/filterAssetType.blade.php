<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex flex-row align-items-end tw-gap-x-4 form-group">
                <div class="col-3">
                    <label class="tw-font-bold">Tên loại tài sản</label>
                    <input type="text" class="form-control" x-model="filters.name" placeholder="Nhập tên loại tài sản">
                </div>
                <div class="col-3">
                    <label class="tw-font-bold">Nhóm tài sản</label>
                    <select class="form-control select2" multiple="multiple" id="filterAssetTypeGroup" data-placeholder="Chọn nhóm tài sản ...">
                        <template x-for="assetTypeGroup in listAssetTypeGroup">
                            <option :value="assetTypeGroup.id" x-text="assetTypeGroup.name"></option>
                        </template>
                    </select>
                </div>
                <div class="">
                    <button @click="getListAssetType(filters)" type="button" class="btn btn-block btn-sc">Tìm kiếm
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
