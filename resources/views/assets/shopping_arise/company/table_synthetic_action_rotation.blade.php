<table id="example2" class="table table-bordered dataTable dtr-inline"
       aria-describedby="example2_info">
    <thead>
    <tr>
        <th class="text-center" style="width: 18rem">Loại tài sản</th>
        <th class="text-center" style="width: 22rem">Chức danh</th>
        <th class="text-center" style="width: 9rem">Thời gian cần</th>
        <th class="text-center" style="width: 4rem">SL</th>
        <th class="text-center">Mô tả</th>
    </tr>
    </thead>
    <tbody>
    <template x-for="(value, index) in assetSynthetic.rotation" :key="index">
        <tr>
            <td x-text="list_asset_type.find(item => item.id === value.asset_type_id)?.name"></td>
            <td x-text="list_job.find(item => item.id === value.job_id)?.name"></td>
            <td x-text="formatDateVN(value.receiving_time)" class="text-center"></td>
            <td x-text="value.quantity_registered" class="text-center"></td>
            <td x-text="value.description"></td>
        </tr>
    </template>
    </tbody>
</table>

