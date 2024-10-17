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
                    <div x-data="{data: []}" x-init="data = listAssetTypeGroup; $watch('listAssetTypeGroup', value => data = value)">
                        @include('common.select2', [
                            'multiple' => true,
                            'id' => 'filterAssetTypeGroup',
                            'placeholder' => 'Chọn nhóm tài sản ...'
                        ])
                    </div>
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
