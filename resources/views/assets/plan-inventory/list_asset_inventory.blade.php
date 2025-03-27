<div class="table-responsive" x-data="{disabled: @json($disabled)}">
    <table id="example2" class="table table-bordered dataTable dtr-inline"
           aria-describedby="example2_info">
        <thead>
        {{--thong tin tai san--}}
        <tr>
            <th rowspan="2" colspan="1" class="text-center">Mã tài sản</th>
            <th rowspan="2" colspan="1" class="text-center" style="min-width: 20rem">Tên tài sản</th>
            <th rowspan="2" colspan="1" class="text-center" style="min-width: 7rem">Số seri</th>
            <th rowspan="2" colspan="1" class="text-center" style="min-width: 10rem">Loại tài sản</th>
            <th rowspan="2" colspan="1" class="text-center" style="min-width: 10rem">Trạng thái kiểm kê</th>
            <th colspan="6" class="text-center">Thực tế</th>
            <th colspan="5" class="text-center">Sổ sách</th>
            <th colspan="4" class="text-center">Chênh lệch</th>
        </tr>
        <tr>
            {{--thuc te--}}
            <th class="text-center" style="min-width: 5rem" x-show="+data.type_inventory === TYPE_INVENTORY_NOT_AUTO">SL</th>
            <th class="text-center" style="min-width: 15rem" x-show="+data.type_inventory === TYPE_INVENTORY_AUTO">Cấu hình</th>
            <th class="text-center" style="min-width: 13rem">Đơn vị</th>
            <th class="text-center" style="min-width: 16rem">Người sử dụng</th>
            <th class="text-center" style="min-width: 16rem">Người đại diện</th>
            <th class="text-center" style="min-width: 12rem">Tình trạng</th>
            <th class="text-center" style="min-width: 15rem">Ghi chú</th>

            {{--so sach--}}
            <th class="text-center" x-show="+data.type_inventory === TYPE_INVENTORY_NOT_AUTO">SL</th>
            <th class="text-center" style="min-width: 15rem" x-show="+data.type_inventory === TYPE_INVENTORY_AUTO">Cấu hình</th>
            <th class="text-center" style="min-width: 13rem">Đơn vị</th>
            <th class="text-center" style="min-width: 16rem">Người sử dụng</th>
            <th class="text-center" style="min-width: 16rem">Người đại diện</th>
            <th class="text-center" style="min-width: 8rem">Tình trạng</th>

            {{--Chênh lệch--}}
            <th class="text-center" x-show="+data.type_inventory === TYPE_INVENTORY_NOT_AUTO">SL</th>
            <th class="text-center" x-show="+data.type_inventory === TYPE_INVENTORY_AUTO">Cấu hình</th>
            <th class="text-center">Người sử dụng</th>
            <th class="text-center">Tình trạng</th>
        </tr>
        </thead>
        <tbody>
        <template x-for="value in data?.assets" :key="value.id">
            <tr>
                {{--thong tin tai san--}}
                <td class="align-middle" x-text="value?.asset.code"></td>
                <td class="align-middle" x-text="value?.asset.name"></td>
                <td class="align-middle" x-text="value?.asset.seri_number"></td>
                <td class="align-middle" x-text="listAssetType.find(item => item.id === value?.asset.asset_type_id)?.name"></td>
                <td class="align-middle">
                    @include('common.select_custom.simple.select_single', [
                        'selected' => 'value.status',
                        'options' => 'LIST_STATUS_PLAN_INVENTORY_ASSET',
                        'disabled' => 'disabled'
                    ])
                </td>

                {{--thuc te--}}
                <td class="align-middle" x-show="+data.type_inventory === TYPE_INVENTORY_NOT_AUTO">
                    <input class="form-control" type="number" max="1" x-model="value.total_present" :disabled="disabled">
                </td>
                <td class="align-middle" x-show="+data.type_inventory === TYPE_INVENTORY_AUTO">
                    <input class="form-control" type="text" x-model="value.config_info_present" :disabled="disabled">
                </td>
                <td class="align-middle">
                    @include('common.select_custom.extent.select_single', [
                       'selected' => 'value.organization_id_present',
                       'options' => 'listOrganization',
                       'disabled' => 'disabled'
                    ])
                </td>
                <td class="align-middle">
                    @include('common.select_custom.extent.select_single', [
                       'selected' => 'value.user_id_present',
                       'options' => 'listUser',
                       'disabled' => 'disabled'
                    ])
                </td>
                <td class="align-middle">
                    @include('common.select_custom.extent.select_single', [
                       'selected' => 'value.manager_id_present',
                       'options' => 'listUser',
                       'disabled' => 'disabled'
                    ])
                </td>
                <td class="align-middle">
                    @include('common.select_custom.simple.select_single', [
                       'selected' => 'value.status_asset_present',
                       'options' => 'LIST_STATUS_ASSET',
                       'disabled' => 'disabled'
                    ])
                </td>
                <td class="align-middle">
                    <input class="form-control" type="text" x-model="value.note" :disabled="disabled">
                </td>

                {{--so sach--}}
                <td class="align-middle text-center" x-show="+data.type_inventory === TYPE_INVENTORY_NOT_AUTO">1</td>
                <td class="align-middle text-center" x-text="value.config_info" x-show="+data.type_inventory === TYPE_INVENTORY_AUTO"></td>
                <td class="align-middle" x-text="listOrganization.find(item => item.id === value.organization_id)?.name"></td>
                <td x-data="{userInfo: []}" x-effect="userInfo = listUser.find(item => item.id === value.user_id)">
                    @include('common.user.user_info', ['user' => 'userInfo'])
                </td>
                <td>
                    <div x-data="{userManager: []}" x-effect="userManager = listUser.find(item => item.id === value.manager_id)">
                        @include('common.user.user_info', ['user' => 'userManager'])
                    </div>
                </td>
                <td class="align-middle">
                    @include('component.status.status_asset', ['status' => 'value.status_asset'])
                </td>

                {{--chenh lech--}}
                <td class="align-middle text-center" x-text="+value.total_present !== 1 ? 'x': ''" x-show="+data.type_inventory === TYPE_INVENTORY_NOT_AUTO"></td>
                <td class="align-middle text-center" x-text="value.config_info !== value.config_info_present ? 'x': ''" x-show="+data.type_inventory === TYPE_INVENTORY_AUTO"></td>
                <td class="align-middle text-center" x-text="+value.user_id !== +value.user_id_present ? 'x': ''"></td>
                <td class="align-middle text-center" x-text="+value.status_asset !== +value.status_asset_present ? 'x': ''"></td>
            </tr>
        </template>
        </tbody>
    </table>
</div>

