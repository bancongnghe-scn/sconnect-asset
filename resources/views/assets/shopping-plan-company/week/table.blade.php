<div class="row" x-data="table">
    <div class="col-12">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 table-responsive custom-scroll">
                    <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
                           aria-describedby="example2_info">
                        <thead>
                        <tr>
                            @can('shopping_plan_company.week.crud')
                                <th class="text-center">
                                    <input type="checkbox" @click="selectedAll">
                                </th>
                            @endcan
                            <th rowspan="1" colspan="1">STT</th>
                            <template x-for="(columnName, key) in columns">
                                <th rowspan="1" colspan="1" x-text="columnName"></th>
                            </template>
                            <th rowspan="1" colspan="1" class="col-2 text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(value,index) in dataTable" :key="index">
                            <tr>
                                @can('shopping_plan_company.week.crud')
                                    <td class="text-center align-middle" x-show="+value.status === STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                        <input type="checkbox" x-model="selectedRow[value.id]" x-bind:checked="selectedRow[value.id]">
                                    </td>
                                    <td class="text-center align-middle" x-show="+value.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                        <input type="checkbox" disabled>
                                    </td>
                                @endcan
                                <td x-text="from + index"></td>
                                <template x-for="(columnName, key) in columns">
                                    <td>
                                        <template x-if="key !== 'register_time' && key !== 'status' && key !== 'user'">
                                            <span x-text="value[key]"></span>
                                        </template>
                                        <template x-if="key === 'register_time'">
                                            <span :class="!value.status_register ? 'tw-text-red-500': ''" x-text="value.start_time + ' - ' + value.end_time"></span>
                                        </template>
                                        <template x-if="key === 'status'">
                                            <div class="d-flex justify-content-center">
                                                @include('component.shopping_plan_company.status_shopping_plan_company', ['status' => 'value.status'])
                                            </div>
                                        </template>
                                        <template x-if="key === 'user'">
                                            <span x-data="{data: value}">
                                                @include('common.user_info')
                                            </span>
                                        </template>

                                    </td>
                                </template>
                                <td class="text-center align-middle">
                                    {{-- xem chi tiet --}}
                                    <button class="border-0 bg-body"
                                            @click="handleShowModal(value.id, 'view')">
                                        <i class="bi bi-eye" style="color: #63E6BE;"></i>
                                    </button>
                                    <template x-for="configBtnTable in configButtonsTable">
                                        <template x-if="configBtnTable.condition(+value.status)">
                                            <template x-if="permission.includes(configBtnTable.permission)">
                                                <template x-for="configBtn in configBtnTable.buttons">
                                                    <button class="border-0 bg-body"
                                                            @click="configBtn.action(value.id)">
                                                        <i :class="configBtn.icon"></i>
                                                    </button>
                                                </template>
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
                    if(+item.status === STATUS_SHOPPING_PLAN_COMPANY_NEW) {
                        this.selectedRow[item.id] = this.checkedAll
                    }
                })
            }
        }
    }
</script>
