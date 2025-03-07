<div class="row" x-data="table">
    <div class="col-12">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 table-responsive custom-scroll">
                    <table id="example2" class="table table-bordered dataTable dtr-inline"
                           aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <th class="text-center" x-show="[ORDER_STATUS_NEW, ORDER_STATUS_TRANSIT].includes(tab_status)">
                                <input type="checkbox" @click="selectedAll">
                            </th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 4rem">STT</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 18rem">Tên đơn hàng</th>
                            <th rowspan="1" colspan="1" class="text-center">Số đơn hàng</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 18rem">NCC</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 8rem">Ngày đơn hàng</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 8rem">Ngày giao hàng</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 18rem">Người phụ trách</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 9rem">Trạng thái</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 8rem">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                            <template x-for="(value,index) in dataTable" :key="index">
                                <tr>
                                    <td class="text-center align-middle" x-show="[ORDER_STATUS_NEW, ORDER_STATUS_TRANSIT].includes(tab_status)">
                                        <input type="checkbox" x-model="selectedRow[value.id]" x-bind:checked="selectedRow[value.id]">
                                    </td>
                                    <td class="text-center align-middle" x-text="from + index"></td>
                                    <td class="align-middle text-wrap tw-no-underline">
                                        <a x-text="value.name" class="tw-no-underline tw-cursor-pointer" :href="`/order/detail/${value.id}`"></a>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span x-text="value.code"></span>
                                    </td>
                                    <td class="align-middle text-wrap">
                                        <span x-text="value.supplier_name"></span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span x-text="formatDateVN(value.created_at)"></span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span x-text="formatDateVN(value.delivery_date)"></span>
                                    </td>
                                    <td class="text-center align-middle">
                                        @include('common.user.user_info', ['user' => 'value.purchasing_manager'])
                                    </td>
                                    <td class="text-center align-middle">
                                        @include('component.status.status_order', ['status' => 'value.status'])
                                    </td>
                                    <td class="text-center align-middle">
                                        <a :href="`/order/detail/${value.id}`" class="tw-no-underline mr-2">
                                            <i class="bi bi-eye text-info"></i>
                                        </a>
                                        <a x-show="[ORDER_STATUS_NEW, ORDER_STATUS_TRANSIT].includes(tab_status)" :href="`/order/update/${value.id}`" class="tw-no-underline mr-2">
                                            <i class="bi bi-pencil-square color-sc"></i>
                                        </a>
                                        <a x-show="tab_status === ORDER_STATUS_DELIVERED" :href="`/import-warehouse/list?order_id=${value.id}`" class="tw-no-underline mr-2">
                                            <i class="bi bi-arrow-down-right-square color-sc"></i>
                                        </a>
                                        <span class="border-0 bg-white" x-show="[ORDER_STATUS_NEW, ORDER_STATUS_TRANSIT].includes(tab_status)" @click="confirmRemove(false, value.id)">
                                            <i class="bi bi-trash text-red"></i>
                                        </span>
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
                    if(+item.status === ORDER_STATUS_NEW || +item.status === ORDER_STATUS_TRANSIT) {
                        this.selectedRow[item.id] = this.checkedAll
                    }
                })
            }
        }
    }
</script>
