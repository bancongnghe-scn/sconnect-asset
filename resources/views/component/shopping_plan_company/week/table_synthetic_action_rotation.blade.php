<table id="example2" class="table table-bordered dataTable dtr-inline"
       aria-describedby="example2_info">
    <thead class="position-sticky z-1" style="top: -1px">
    <tr>
        <th class="text-center">Đơn vị</th>
        <th class="text-center">Loại tài sản</th>
        <th class="text-center">Chức danh</th>
        <th class="text-center">Thời gian cần</th>
        <th class="text-center">SL</th>
        <th class="text-center">Mô tả</th>
    </tr>
    </thead>
    <tbody>
    <template x-for="(organization, index) in shoppingAssetWithAction" :key="index">
        <template x-for="(assetRegister, stt) in organization.asset_register.rotation" :key="index + '_' + stt">
            <tr>
                <td x-show="stt === 0" :rowspan="stt === 0 ? organization.asset_register.rotation.length : 1" class="tw-font-bold">
                    <span x-text="organization.name"></span>
                    @include('component.shopping_plan_organization.status_shopping_plan_organization', [
                        'status' => 'organization.status',
                        'tooltip' => 'organization.note'
                    ])
                </td>
                <td x-text="assetRegister.asset_type_name ?? '-'"></td>
                <td x-text="assetRegister.job_name ?? '-'"></td>
                <td x-text="assetRegister.receiving_time ?? '-'" class="text-center"></td>
                <td x-text="assetRegister.quantity_registered ?? '-'" class="text-center"></td>
                <td x-text="assetRegister.description ?? '-'"></td>
            </tr>
        </template>
    </template>
    </tbody>
</table>
<style>
    th, td {
        white-space: nowrap;
    }
</style>
