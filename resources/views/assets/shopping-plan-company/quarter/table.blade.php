<div class="row" x-data="table">
    <div class="col-12">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12">
                    <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
                           aria-describedby="example2_info">
                        <thead>
                        <tr>
                            @can('shopping_plan_company.crud')
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
                        <template x-for="(data,index) in dataTable" :key="index">
                            <tr>
                                @can('shopping_plan_company.crud')
                                    <td class="text-center align-middle" x-show="+data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                        <input type="checkbox" x-model="selectedRow[data.id]" x-bind:checked="selectedRow[data.id]">
                                    </td>
                                    <td class="text-center align-middle" x-show="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                        <input type="checkbox" disabled>
                                    </td>
                                @endcan
                                <td x-text="from + index"></td>
                                <template x-for="(columnName, key) in columns">
                                    <td>
                                        <template x-if="key !== 'register_time' && key !== 'status' && key !== 'user'">
                                            <span x-text="data[key]"></span>
                                        </template>
                                        <template x-if="key === 'register_time'">
                                            <span :class="!data.status_register ? 'tw-text-red-500': ''" x-text="data.start_time + ' - ' + data.end_time"></span>
                                        </template>
                                        <template x-if="key === 'status'">
                                            <div class="d-flex justify-content-center">
                                                @include('component.shopping_plan_company.status_shopping_plan_company', ['status' => 'data.status'])
                                            </div>
                                        </template>
                                        <template x-if="key === 'user'">
                                            @include('common.user_info')
                                        </template>

                                    </td>
                                </template>
                                <td class="text-center align-middle">
                                    {{-- xem chi tiet --}}
                                    <button class="border-0 bg-body"
                                            @click="window.location.href = `/shopping-plan-company/quarter/view/${data.id}`">
                                        <i class="fa-solid fa-eye" style="color: #63E6BE;"></i>
                                    </button>

                                    {{-- sua va xoa --}}
                                    @can('shopping_plan_company.crud')
                                        <template x-if="[STATUS_SHOPPING_PLAN_COMPANY_NEW,STATUS_SHOPPING_PLAN_COMPANY_REGISTER].includes(+data.status)">
                                            <button class="border-0 bg-body"
                                                    @click="window.location.href = `/shopping-plan-company/quarter/update/${data.id}`">
                                                <i class="fa-regular fa-pen-to-square color-sc"></i>
                                            </button>
                                        </template>
                                        <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                            <button class="border-0 bg-body"
                                                    @click="$dispatch('remove', { id: data.id })">
                                                <i class="fa-regular fa-trash-can" style="color: #cd1326;"></i>
                                            </button>
                                        </template>
                                    @endcan

                                    {{-- ke toan va giam doc duyet --}}
                                    <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL">
                                        @can('shopping_plan_company.accounting_approval')
                                            <button class="border-0 bg-body"
                                                    @click="window.location.href = `/shopping-plan-company/quarter/update/${data.id}`">
                                                <i class="fa-solid fa-pen-to-square" style="color: #74C0FC;"></i>
                                            </button>
                                        @endcan
                                    </template>
                                    <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL">
                                        @can('shopping_plan_company.general_approval')
                                            <button class="border-0 bg-body"
                                                    @click="window.location.href = `/shopping-plan-company/quarter/update/${data.id}`">
                                                <i class="fa-solid fa-pen-to-square" style="color: #74C0FC;"></i>
                                            </button>
                                        @endcan
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
