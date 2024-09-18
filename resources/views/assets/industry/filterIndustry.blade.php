<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex flex-row align-items-end tw-gap-x-4">
                <div class="form-group col-3">
                    <label class="tw-font-bold">Tên ngành hàng</label>
                    <input type="text" class="form-control" x-model="filters.name">
                </div>
                <div class="">
                    <button @click="getListIndustry(filters)" type="button" class="btn btn-block btn-primary">Tìm kiếm</button>
                </div>
            </div>
        </div>
    </div>
</div>
