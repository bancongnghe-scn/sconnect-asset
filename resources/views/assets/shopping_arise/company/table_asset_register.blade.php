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
            <td class="align-middle" x-text="list_asset_type.find(item => item.id === value.asset_type_id)?.name"></td>
            <td class="align-middle" x-text="value.quantity_registered"></td>
            <td class="align-middle" x-text="list_job.find(item => item.id === value.job_id)?.name"></td>
            <td class="align-middle" x-text="formatDateVN(value.receiving_time)"></td>
            <td class="align-middle" x-text="value.description"></td>
        </tr>
    </template>
    </tbody>
</table>
