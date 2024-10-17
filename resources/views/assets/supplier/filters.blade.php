<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex flex-row align-items-end form-group">
                <div class="col-3">
                    <label class="tw-font-bold">Tên mã/nhà cung cấp</label>
                    <input type="text" class="form-control" x-model="filters.code_name" placeholder="Nhập tên mã/nhà cung cấp">
                </div>
                <div class="col-3">
                    <label class="tw-font-bold">Ngành hàng</label>
                    <div x-data="{data: []}" x-init="data = listIndustry; $watch('listIndustry', value => data = value)">
                        @include('common.select2' , ['multiple' => true, 'id' => 'industriesFilter', 'placeholder' => 'Chọn ngành hàng ...'])
                    </div>
                </div>
                <div class="col-3">
                    <label class="tw-font-bold">Đánh giá</label>
                    <div x-data="{data: status}">
                        @include('common.select2-simple', ['multiple' => true, 'id' => 'statusFilter', 'placeholder' => 'Chọn đánh giá ...'])
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
