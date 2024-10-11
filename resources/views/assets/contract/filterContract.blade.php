<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3 align-items-end form-group">
                    <div class="col-3">
                        <label class="tw-font-bold">Tên/mã hợp đồng</label>
                        <input type="text" class="form-control" x-model="filters.name_code" placeholder="Nhập tên/mã hợp đồng">
                    </div>
                    <div class="col-3">
                        <label class="tw-font-bold">Loại hợp đồng</label>
                        <select class="form-control select2" multiple="multiple" id="filterTypeContract" data-placeholder="Chọn loại hợp đồng">
                            <template x-for="(value, key) in listTypeContract">
                                <option :value="key" x-text="value"></option>
                            </template>
                        </select>
                    </div>
                    <div class="col-2">
                        <label class="tw-font-bold">Trạng thái</label>
                        <select class="form-control select2" multiple="multiple" id="filterStatusContract" data-placeholder="Chọn trạng thái">
                            <template x-for="(value, key) in listStatusContract">
                                <option :value="key" x-text="value"></option>
                            </template>
                        </select>
                    </div>
                    <div class="col-3">
                        <label class="tw-font-bold">Ngày ký</label>
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="filterSigningDate"
                                   placeholder="Ngày ký" autocomplete="off">
                            <span class="input-group-text">
                                <i class="fa-regular fa-calendar-days"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-3">
                        <label class="tw-font-bold">Ngày hiệu lực</label>
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="filterFrom"
                                   placeholder="Ngày hiệu lực" autocomplete="off">
                            <span class="input-group-text">
                                <i class="fa-regular fa-calendar-days"></i>
                            </span>
                        </div>
                    </div>
                    <div>
                        <button @click="getListContract(filters)" type="button" class="btn btn-block btn-sc">Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
