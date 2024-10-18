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
                    <select class="form-select select2" x-model="filters.industry_ids" id="industriesFilter" multiple="multiple" data-placeholder="Chọn ngành hàng ...">
                        <template x-for="value in listIndustry" :key="value.id">
                            <option :value="value.id" x-text="value.name"></option>
                        </template>
                    </select>
                </div>
                <div class="col-3">
                    <label class="tw-font-bold">Đánh giá</label>
                    <select class="form-control select2" x-model="filters.status" id="statusFilter" multiple="multiple" data-placeholder="Chọn đánh giá ...">
                        <template x-for="(value, key) in status">
                            <option :value="key" x-text="value"></option>
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
