<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex flex-row align-items-end tw-gap-x-4">
                <div class="form-group col-3">
                    <label class="tw-font-bold">Tên nhà cung cấp</label>
                    <input type="text" class="form-control" x-model="filters.name">
                </div>
                <div class="form-group col-3">
                    <label class="tw-font-bold">Ngành hàng</label>
                    <select class="form-control" name="asset_type_group" x-model="filters.industry_id">
                        <option value="">Chọn ngành hàng ...</option>
                        <template x-for="industry in listIndustry" :key="industry.id">
                            <option :value="industry.id" x-text="industry.name"></option>
                        </template>
                    </select>
                </div>
                <div class="form-group col-3">
                    <label class="tw-font-bold">Xếp hạng</label>
                    <select x-data="{rating: 5}" class="form-control" name="asset_type_group" x-model="filters.level">
                        <option value="">Chọn xếp hạng ...</option>
                        <template x-for="key in rating" :key="key">
                            <option :value="key" x-text="'Hạng '+ key"></option>
                        </template>
                    </select>
                </div>

                <div class="">
                    <button @click="getListSupplier(filters)" type="button" class="btn btn-block btn-primary">Tìm kiếm</button>
                </div>
            </div>
        </div>
    </div>
</div>
