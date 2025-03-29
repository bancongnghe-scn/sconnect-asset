<div>
    <div class="mb-3 active-link tw-w-fit">Thông tin chung</div>
    <div>
        <label>Nội dung<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
        <textarea class="form-control" x-model="data.name" placeholder="Nhập nội dung mua sắm"></textarea>
    </div>
</div>

<div class="mt-3">
    <div class="mb-3 active-link tw-w-fit">Chi tiết kế hoạch</div>
    <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
        <table id="example2" class="table table-bordered dataTable dtr-inline" aria-describedby="example2_info">
            <thead>
            <tr>
                <th class="text-center" style="width: 18rem;">Loại tài sản<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></th>
                <th class="text-center" style="width: 6rem;">Số lượng<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></th>
                <th class="text-center" style="width: 20rem;">Vị trí chức danh<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></th>
                <th class="text-center" style="width: 11rem;">Thời gian cần</th>
                <th class="text-center">Mô tả</th>
                <th class="text-center" style="width: 3rem;"></th>
            </tr>
            </thead>
            <tbody>
            <template x-for="(value, index) in data.assets" :key="index">
                <tr>
                    <td class="align-middle">
                        @include('common.select_custom.extent.select_single', [
                              'placeholder' => 'Chọn loại tài sản',
                              'selected' => 'value.asset_type_id',
                              'options' => 'list_asset_type',
                        ])
                    </td>
                    <td class="align-middle">
                        <input class="form-control" type="number" min="1"
                               x-model="value.quantity_registered">
                    </td>
                    <td class="align-middle">
                        @include('common.select_custom.extent.select_single', [
                            'placeholder' => 'Chọn chức danh',
                            'selected' => 'value.job_id',
                            'options' => 'list_job',
                        ])
                    </td>
                    <td class="align-middle">
                        @include('common.datepicker.datepicker', ['placeholder'=>"Thời gian cần", 'model' => "value.receiving_time"])
                    </td>
                    <td class="align-middle">
                        <input class="form-control" type="text"
                               x-model="value.description">
                    </td>
                    <td class="text-center align-middle">
                        <button class="border-0 bg-white" @click="data.assets.splice(index,1)">
                            <i class="bi bi-trash3 text-red"></i>
                        </button>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>
    </div>
    <button @click="addRowAsset" type="button" class="btn btn-sm btn-sc">Thêm hàng</button>
</div>
