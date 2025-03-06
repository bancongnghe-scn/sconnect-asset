<div class="row">
    <div class="col-12">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 table-responsive custom-scroll">
                    <table id="example2" class="table table-bordered dataTable dtr-inline"
                           aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 3rem;">STT</th>
                            <th rowspan="1" colspan="1" class="text-center">Kế hoạch</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 13rem">Thời gian đăng ký</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 16rem">Người tạo</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 8rem">Ngày tạo</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 9rem">Trạng thái</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 6rem">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(value,index) in dataTable">
                            <tr>
                                <td class="text-center align-middle" x-text="from + index"></td>
                                <td class="align-middle" x-text="value.name"></td>
                                <td class="text-center align-middle"
                                    :class="!value.status_register ? 'text-red': ''"
                                    x-text="value.start_time + ' - ' + value.end_time">
                                </td>
                                <td>
                                    @include('common.user.user_info', ['user' => 'value.user'])
                                </td>
                                <td class="text-center align-middle" x-text="value.created_at"></td>
                                <td class="align-middle">
                                    @include('component.status.status_shopping_plan_organization', ['status' => 'value.status'])
                                </td>
                                <td class="align-middle">
                                    <a :href="`/shopping-plan-organization/quarter/detail/${value.id}`" class="tw-no-underline mr-2">
                                        <i class="bi bi-eye text-info"></i>
                                    </a>

                                    <template x-for="configBtnTable in configButtonsTable">
                                        <template x-if="configBtnTable.condition(+value.status, value.start_time, value.end_time)">
                                            <template x-for="configBtn in configBtnTable.buttons">
                                                <button class="border-0 bg-white"
                                                        @click="configBtn.action(value.id)">
                                                    <i :class="configBtn.icon"></i>
                                                </button>
                                            </template>
                                        </template>
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
