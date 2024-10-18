<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3 align-items-end">
                    <div class="form-group col-3">
                        <label class="tw-font-bold">Năm</label>
                        <input type="text" class="form-control yearpicker" id="filterYear" placeholder="Chọn năm" autocomplete="off">
                    </div>
                    <div class="form-group col-2">
                        <label class="tw-font-bold">Trạng thái</label>
                        <select class="form-control select2" id="filterStatus" multiple="multiple" data-placeholder="Chọn trạng thái ...">
                            <template x-for="(value, key) in listStatus">
                                <option :value="key" x-text="value"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <button @click="getList(filters)" type="button" class="btn btn-block btn-sc">Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
