<div class="row">
    <div class="col-12">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 table-responsive custom-scroll">
                    <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
                           aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <th rowspan="1" colspan="1" class="text-center">Mã tài sản</th>
                            <th rowspan="1" colspan="1" class="text-center">Tên tài sản</th>
                            <th rowspan="1" colspan="1" class="text-center">Đơn vị thực hiện BD</th>
                            <th rowspan="1" colspan="1" class="text-center">ĐVBD</th>
                            <th rowspan="1" colspan="1" class="text-center">Thời gian</th>
                            <th rowspan="1" colspan="1" class="text-center">Trạng thái</th>
                            <th rowspan="1" colspan="1" class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(value, index) in dataTable" :key="index">
                            <tr>
                                <td>
                                    <a x-text="value.code" class="tw-cursor-pointer"></a>
                                </td>
                                <td x-text="value.name">
                                <td x-text="value.suppliers.join(', ')">
                                <td x-text="value.organizations.join(', ')">
                                <td class="text-center align-middle" x-text="formatDateVN(value.start_time) + ' - ' + formatDateVN(value.start_time)">
                                <td class="text-center align-middle">
                                    @include('component.status.status_plan_maintain', ['status' => 'value.status'])
                                </td>
                                <td class="text-center align-middle">
                                    <template x-if="value.status === STATUS_COMPLETE_MAINTAIN">
                                        <span class="tw-cursor-pointer">
                                            <i class="bi bi-eye" style="color: #63E6BE;"></i>
                                        </span>
                                    </template>
                                    <template x-if="value.status === STATUS_MAINTAINING">
                                        <span>
                                            <span class="tw-cursor-pointer mr-1">
                                                <i class="bi bi-pencil-square color-sc"></i>
                                            </span>
                                            <span class="tw-cursor-pointer">
                                                <i class="bi bi-trash3 text-red"></i>
                                            </span>
                                        </span>
                                    </template>
                                </td>
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




