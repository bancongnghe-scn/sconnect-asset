<table id="example2" class="table table-bordered dataTable dtr-inline" aria-describedby="example2_info">
    <thead>
    <tr>
        <th class="text-center" style="width: 18rem;">Loại tài sản<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></th>
        <th class="text-center" style="width: 6rem;">Số lượng<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></th>
        <th class="text-center" style="width: 20rem;">Vị trí chức danh<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></th>
        <th class="text-center" style="width: 11rem;">Thời gian cần</th>
        <th class="text-center">Mô tả</th>
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
                      'disabled' => true
                ])
            </td>
            <td class="align-middle">
                <input class="form-control" type="number" min="1"
                       x-model="value.quantity_registered" disabled>
            </td>
            <td class="align-middle">
                @include('common.select_custom.extent.select_single', [
                    'placeholder' => 'Chọn chức danh',
                    'selected' => 'value.job_id',
                    'options' => 'list_job',
                    'disabled' => true
                ])
            </td>
            <td class="align-middle">
                @include('common.datepicker.datepicker', [
                    'placeholder'=>"Thời gian cần",
                    'model' => "value.receiving_time",
                    'disabled' => true
                ])
            </td>
            <td class="align-middle">
                <input class="form-control" type="text" x-model="value.description" disabled>
            </td>
        </tr>
    </template>
    </tbody>
</table>
