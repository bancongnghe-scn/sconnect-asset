<div class="table-responsive">
    <table id="example2" class="table table-bordered dataTable dtr-inline"
           aria-describedby="example2_info">
        <thead>
        <tr>
            <th rowspan="2" colspan="1" class="text-center">Mã tài sản</th>
            <th rowspan="2" colspan="1" class="text-center" style="width: 10rem">Tên tài sản</th>
            <th rowspan="2" colspan="1" class="text-center">Số seri</th>
            <th rowspan="2" colspan="1" class="text-center">Loại tài sản</th>
            <th rowspan="2" colspan="1" class="text-center">Trạng thái kiểm kê</th>
            <th colspan="6" class="text-center">Sổ sách</th>
        </tr>
        <tr>
            <th class="text-center">SL</th>
            <th class="text-center">Đơn vị</th>
            <th class="text-center" style="min-width: 15rem">Người sử dụng</th>
            <th class="text-center">Người đại diện</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Đối tượng sử dụng</th>
        </tr>
        </thead>
        <tbody>
        <template x-for="value in data.assets" :key="value.id">
            <tr>
                <td class="align-middle" x-text="value.code"></td>
                <td class="align-middle" x-text="value.name"></td>
                <td class="align-middle" x-text="value.seri_number"></td>
                <td class="align-middle" x-text="listAssetType.find(item => item.id === value.asset_type_id)?.name"></td>
                <td class="align-middle" x-text="LIST_STATUS_PLAN_INVENTORY_ASSET[STATUS_ASSET_NOT_INVENTORIED]"></td>
                <td class="align-middle text-center">1</td>
                <td class="align-middle" x-text="listOrganization.find(item => item.id === value.organization_id)?.name"></td>
                <td x-data="{userInfo: []}" x-effect="userInfo = listUser.find(item => item.id === value.user_id)">
                    @include('common.user.user_info', ['user' => 'userInfo'])
                </td>
                <td></td>
                <td class="align-middle" x-text="LIST_STATUS_ASEET[value.status]"></td>
                <td class="align-middle" x-text="value.user_id ? 'Cá nhân' : 'Đơn vị'"></td>
            </tr>
        </template>
        </tbody>
    </table>

</div>
