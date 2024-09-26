<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex flex-row align-items-end tw-gap-x-4">
                <div class="form-group col-3">
                    <label class="tw-font-bold">Tên/mã hợp đồng</label>
                    <input type="text" class="form-control" x-model="filters.name_code" placeholder="Nhập tên/mã hợp đồng">
                </div>
                <div class="form-group col-3">
                    <label class="tw-font-bold">Loại hợp đồng</label>
                    <select class="form-control select2" multiple="multiple" id="filterTypeContract" data-placeholder="Chọn loại hợp đồng">
                        <template x-for="(value, key) in listTypeContract">
                            <option :value="key" x-text="value"></option>
                        </template>
                    </select>
                </div>
                <div class="form-group col-3">
                    <label class="tw-font-bold">Trạng thái</label>
                    <select class="form-control select2" multiple="multiple" id="filterStatusContract" data-placeholder="Chọn trạng thái">
                        <template x-for="(value, key) in listStatusContract">
                            <option :value="key" x-text="value"></option>
                        </template>
                    </select>
                </div>
                <div class="">
                    <button @click="searchContract" type="button" class="btn btn-block btn-primary">Tìm kiếm
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
