<div class="row">
    <div class="col-12">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 table-responsive custom-scroll">
                    <table id="example2" class="table table-bordered dataTable dtr-inline"
                           aria-describedby="example2_info">
                        <thead>
                            <tr>
                                <th rowspan="1" colspan="1" class="text-center">Mã tài sản</th>
                                <th rowspan="1" colspan="1" class="text-center">Tên tài sản</th>
                                <th rowspan="1" colspan="1" class="text-center">Loại tài sản</th>
                                <th rowspan="1" colspan="1" class="text-center">Nhân viên sử dụng</th>
                                <th rowspan="1" colspan="1" class="text-center">Ngày BD gần nhất</th>
                                <th rowspan="1" colspan="1" class="text-center">Ngày BD tiếp theo</th>
                                <th rowspan="1" colspan="1" class="text-center">Vị trí tài sản</th>
                                <th rowspan="1" colspan="1" class="text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(value, index) in dataTable" :key="index">
                                <tr>
                                    <td x-text="value.code"></td>
                                    <td x-text="value.name"></td>
                                    <td x-text="value.asset_type_name"></td>
                                    <td>
                                        <span x-data="{data: value, key: 'user'}">
                                            @include('common.user_info')
                                        </span>
                                    </td>
                                    <td class="text-center align-middle" x-text="formatDateVN(value.recent_maintenance_date)"></td>
                                    <td class="text-center align-middle" x-text="formatDateVN(value.next_maintenance_date)"></td>
                                    <td class="text-center align-middle" x-text="LIST_LOCATION_ASSET[value.location]"></td>
                                    <td class="text-center align-middle" x-html="arrSvgStatus[value.status]"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @include('common.pagination')
    </div>
</div>

@include('assets.asset.common.commonSvg')




