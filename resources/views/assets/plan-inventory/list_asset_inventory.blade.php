<div class="table-responsive" x-data="{disabled: @json($disabled)}">
    <table id="example2" class="table table-bordered dataTable dtr-inline"
           aria-describedby="example2_info">
        <thead>
        {{--thong tin tai san--}}
        <tr>
            <th rowspan="2" colspan="1" class="text-center">Mã tài sản</th>
            <th rowspan="2" colspan="1" class="text-center" style="width: 10rem">Tên tài sản</th>
            <th rowspan="2" colspan="1" class="text-center">Số seri</th>
            <th rowspan="2" colspan="1" class="text-center">Loại tài sản</th>
            <th rowspan="2" colspan="1" class="text-center">Trạng thái kiểm kê</th>
            <th colspan="6" class="text-center">Sổ sách</th>
            <th colspan="7" class="text-center">Thực tế</th>
            <th colspan="4" class="text-center">Chênh lệch</th>
        </tr>
        <tr>
            {{--so sach--}}
            <th class="text-center">SL</th>
            <th class="text-center">Đơn vị</th>
            <th class="text-center">Người sử dụng</th>
            <th class="text-center">Người đại diện</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Vị trí</th>

            {{--thuc te--}}
            <th class="text-center">SL</th>
            <th class="text-center">Đơn vị</th>
            <th class="text-center">Người sử dụng</th>
            <th class="text-center">Người đại diện</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Vị trí</th>
            <th class="text-center">Ghi chú</th>

            {{--Chênh lệch--}}
            <th class="text-center">SL</th>
            <th class="text-center">Người sử dụng</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Vị trí</th>
        </tr>
        </thead>
        <tbody>
        <template x-for="value in data?.assets?.inventory" :key="value.id">
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

                {{--so sach--}}
                <td class="align-middle">1</td>
                <td class="align-middle" x-text="listOrganization.find(item => item.id === value.organization_id)?.name"></td>
                <td x-data="{userInfo: []}" x-effect="userInfo = listUser.find(item => item.id === value.user_id)">
                    @include('common.user.user_info', ['user' => 'userInfo'])
                </td>
                <td>
                    <template x-if="!value.user_id && value.organization_id && value.status !== ASSET_STATUS_PENDING">
                        <div x-data="{userManager: []}" x-effect="userManager = listUser.find(item => item.id === value.manager_id)">
                            @include('common.user.user_info', ['user' => 'userManager'])
                        </div>
                    </template>
                </td>
                <td class="align-middle">
                    @include('component.status.status_asset', ['status' => 'value.status_asset'])
                </td>
                <td class="align-middle" x-text="LIST_LOCATION_ASSET[value?.asset.location]"></td>

                {{--thuc te--}}
                <td class="align-middle">
                    <input class="form-control" type="number" max="1" x-model="value.total_present" :disabled="disabled">
                </td>
                <td>
                    @include('common.select_custom.extent.select_single', [
                       'selected' => 'value.organization_id_present',
                       'options' => 'listOrganization',
                       'disabled' => 'disabled'
                    ])
                </td>
                <td>
                    @include('common.select_custom.extent.select_single', [
                       'selected' => 'value.user_id_present',
                       'options' => 'listUser',
                       'disabled' => 'disabled'
                    ])
                </td>
                <td>
                    @include('common.select_custom.extent.select_single', [
                       'selected' => 'value.manager_id_present',
                       'options' => 'listUser',
                       'disabled' => 'disabled'
                    ])
                </td>
                <td>
                    @include('common.select_custom.simple.select_single', [
                       'selected' => 'value.status_asset_present',
                       'options' => 'LIST_STATUS_ASSET',
                       'disabled' => 'disabled'
                    ])
                </td>
                <td>
                    @include('common.select_custom.simple.select_single', [
                       'selected' => 'value.location_present',
                       'options' => 'LIST_LOCATION_ASSET',
                       'disabled' => 'disabled'
                    ])
                </td>
                <td>
                    <input class="form-control" type="text" x-model="value.note" :disabled="disabled">
                </td>

                {{--chenh lech--}}
                <td>X</td>
                <td>X</td>
                <td>X</td>
                <td>X</td>
            </tr>
        </template>
        </tbody>
    </table>
</div>
<style>
    td {
        text-wrap: nowrap;
    }
</style>
