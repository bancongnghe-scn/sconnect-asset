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
                        <div x-data="{data: listTypeContract}">
                            @include('common.select2-simple', ['multiple' => true, 'id' => 'filterTypeContract', 'placeholder' => 'Chọn loại hợp đồng'])
                        </div>
                    </div>
                    <div class="col-2">
                        <label class="tw-font-bold">Trạng thái</label>
                        <div x-data="{data: listStatusContract}">
                            @include('common.select2-simple', ['multiple' => true, 'id' => 'filterStatusContract', 'placeholder' => 'Chọn trạng thái'])
                        </div>
                    </div>
                    <div class="col-3">
                        <label class="tw-font-bold">Ngày ký</label>
                        @include('common.datepicker',['placeholder' => "Ngày ký", 'id' => 'filterSigningDate'])
                    </div>
                    <div class="col-3">
                        <label class="tw-font-bold">Ngày hiệu lực</label>
                        @include('common.datepicker', ['placeholder' => "Ngày hiệu lực", 'id' => "filterFrom"])
                    </div>
                    <div class="col-auto">
                        <button @click="getListContract(filters)" type="button" class="btn btn-block btn-sc">Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
