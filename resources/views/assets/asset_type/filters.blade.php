<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex flex-row align-items-end form-group">
                <div class="col-3">
                    <label class="tw-font-bold">Tên loại tài sản</label>
                    <input type="text" class="form-control" x-model="filters.name" placeholder="Nhập tên loại tài sản">
                </div>
                <div class="col-3">
                    <label class="tw-font-bold">Nhóm tài sản</label>
                    <select class="form-select select2" x-model="data.asset_type_group_id" id="filterAssetTypeGroup" multiple="multiple" data-placeholder="Chọn nhóm tài sản ...">
                        <template x-for="value in listAssetTypeGroup" :key="value.id">
                            <option :value="value.id" x-text="value.name"></option>
                        </template>
                    </select>
                </div>
                <div class="col-auto">
                    <button @click="list(filters)" type="button" class="btn btn-block btn-sc">Tìm kiếm</button>
                </div>
                <div class="col-auto">
                    <button @click="reloadPage()" type="button" class="btn btn-secondary">Xóa lọc</button>
                </div>
            </div>
        </div>
    </div>
</div>
