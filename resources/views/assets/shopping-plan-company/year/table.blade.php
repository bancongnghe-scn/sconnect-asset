<div class="row" x-data="table">
    <div class="col-12">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 table-responsive custom-scroll">
                    <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
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
                            <th rowspan="1" colspan="1" class="text-center" style="width: 10rem">Trạng thái</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 8rem">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                            <template x-for="(value,index) in dataTable" :key="index">
                                <tr>
                                    @can('shopping_plan_company.crud')
                                        <td class="text-center align-middle">
                                            <input type="checkbox" x-model="selectedRow[value.id]"
                                                   x-bind:checked="selectedRow[value.id]"
                                                   :disabled="+value.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                        </td>
                                    @endcan
                                    <td class="text-center align-middle" x-text="from + index"></td>
                                    <td class="align-middle" x-text="value.name"></td>
                                    <td class="text-center align-middle"
                                        :class="!value.status_register ? 'tw-text-red-500': ''"
                                        x-text="value.start_time + ' - ' + value.end_time">
                                    </td>
                                    <td x-data="{data: value, key: 'user'}">
                                        @include('common.user_info')
                                    </td>
                                    <td class="text-center align-middle" x-text="value.created_at"></td>
                                    <td class="text-center align-middle">
                                        @include('component.shopping_plan_company.status_shopping_plan_company', ['status' => 'value.status'])
                                    </td>
                                    <td class="text-center align-middle">
                                        {{-- xem chi tiet --}}
                                        <button class="border-0 bg-body"
                                                @click="window.location.href = `/shopping-plan-company/year/view/${data.id}`">
                                            <i class="bi bi-eye" style="color: #63E6BE;"></i>
                                        </button>

                                        {{-- sua va xoa --}}
                                        @can('shopping_plan_company.crud')
                                            <template x-if="[STATUS_SHOPPING_PLAN_COMPANY_NEW,STATUS_SHOPPING_PLAN_COMPANY_REGISTER].includes(+data.status)">
                                                <button class="border-0 bg-body"
                                                        @click="window.location.href = `/shopping-plan-company/year/update/${data.id}`">
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
                                                        @click="window.location.href = `/shopping-plan-company/year/update/${data.id}`">
                                                    <i class="fa-solid fa-pen-to-square" style="color: #74C0FC;"></i>
                                                </button>
                                            @endcan
                                        </template>
                                        <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL">
                                            @can('shopping_plan_company.general_approval')
                                                <button class="border-0 bg-body"
                                                        @click="window.location.href = `/shopping-plan-company/year/update/${data.id}`">
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
