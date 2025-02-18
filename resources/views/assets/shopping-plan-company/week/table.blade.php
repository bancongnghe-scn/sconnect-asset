<div class="row" x-data="table">
    <div class="col-12">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 table-responsive custom-scroll">
                    <table id="example2" class="table table-bordered dataTable dtr-inline"
                           aria-describedby="example2_info">
                        <thead>
                        <tr>
                            @can('shopping_plan_company.crud')
                                <th class="text-center">
                                    <input type="checkbox" @click="selectedAll">
                                </th>
                            @endcan
                            <th rowspan="1" colspan="1" class="text-center">STT</th>
                            <th rowspan="1" colspan="1" class="text-center">Kế hoạch</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 13rem">Thời gian đăng ký</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 16rem">Người tạo</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 8rem">Ngày tạo</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 8rem">Trạng thái</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 6rem">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(value,index) in dataTable" :key="index">
                            <tr>
                                @can('shopping_plan_company.week.crud')
                                    <td class="text-center align-middle">
                                        <input type="checkbox" x-model="selectedRow[value.id]"
                                               x-bind:checked="selectedRow[value.id]"
                                               :disabled="+value.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                    </td>
                                @endcan
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
                                    @include('component.status.status_shopping_plan_company', ['status' => 'value.status'])
                                </td>
                                <td class="align-middle">
                                    <template x-for="configBtnTable in configButtonsTable">
                                        <template x-if="configBtnTable.condition(+value.status)">
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

<script>
    function table() {
        return {
            checkedAll: false,

            selectedAll() {
                this.checkedAll = !this.checkedAll
                this.dataTable.forEach((item) => {
                    if (+item.status === STATUS_SHOPPING_PLAN_COMPANY_NEW) {
                        this.selectedRow[item.id] = this.checkedAll
                    }
                })
            }
        }
    }
</script>
