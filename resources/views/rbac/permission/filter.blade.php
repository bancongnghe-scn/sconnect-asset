<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3 align-items-end">
                    <div class="form-group col-3">
                        <label class="tw-font-bold">Tên quyền</label>
                        <input type="text" class="form-control" x-model="filters.name" placeholder="Nhập tên">
                    </div>
                    <div>
                        <button @click="getList(filters)" type="button" class="btn btn-block btn-sc">Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
