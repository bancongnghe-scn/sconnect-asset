<div class="table-responsive">
    <table id="example2" class="table table-bordered dataTable dtr-inline"
           aria-describedby="example2_info">
        <thead>
        <tr>
            <th class="text-center">Mã tài sản</th>
            <th class="text-center" style="width: 10rem">Tên tài sản</th>
            <th class="text-center">Số seri</th>
            <th class="text-center">Loại tài sản</th>
            <th class="text-center">Trạng thái kiểm kê</th>
            <th class="text-center" x-show="+data.type_inventory === TYPE_INVENTORY_AUTO">Cấu hình</th>
            <th class="text-center">Đơn vị</th>
            <th class="text-center">Người sử dụng</th>
            <th class="text-center">Người đại diện</th>
            <th class="text-center">Tình trạng</th>
        </tr>
        </thead>
        <tbody>
        <template x-for="value in data.assets" :key="value.id">
            <tr>
                <td class="align-middle" x-text="value.code"></td>
                <td class="align-middle" x-text="value.name"></td>
                <td class="align-middle" x-text="value.seri_number"></td>
                <td class="align-middle" x-text="listAssetType.find(item => item.id === value.asset_type_id)?.name"></td>
                <td class="align-middle">
                    @include('component.status.status_inventory_asset', ['status' => 'STATUS_ASSET_NOT_INVENTORIED'])
                </td>
                <td class="align-middle" x-show="+data.type_inventory === TYPE_INVENTORY_AUTO" x-text="value.config_info"></td>
                <td class="align-middle" x-text="listOrganization.find(item => item.id === value.organization_id)?.name"></td>
                <td x-data="{userInfo: []}" x-effect="userInfo = listUser.find(item => item.id === value.user_id)">
                    @include('common.user.user_info', ['user' => 'userInfo'])
                </td>
                <td class="align-middle">
                    <template x-if="value.manager_id">
                        <div x-data="{userManager: []}" x-effect="userManager = listUser.find(item => item.id === value.manager_id)">
                            @include('common.user.user_info', ['user' => 'userManager'])
                        </div>
                    </template>
                </td>
                <td class="align-middle">
                    @include('component.status.status_asset', ['status' => 'value.status'])
                </td>
            </tr>
        </template>
        </tbody>
    </table>
</div>
