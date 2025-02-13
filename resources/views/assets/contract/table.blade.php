<div class="row" x-data="table">
    <div class="col-12">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 table-responsive custom-scroll">
                    <table id="example2" class="table table-bordered dataTable dtr-inline"
                           aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <th class="text-center">
                                <input type="checkbox" @click="selectedAll">
                            </th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 4rem">STT</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 8rem">Mã hợp đồng</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 10rem">Loại hợp đồng</th>
                            <th rowspan="1" colspan="1" class="text-center">Tên hợp đồng</th>
                            <th rowspan="1" colspan="1" class="text-center">Tên nhà cung cấp</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 7rem">Ngày ký</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 10rem">Tổng giá trị</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 6rem">Hiệu lực</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 7rem">Trạng thái</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 7rem">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(value,index) in dataTable" x-data="{line: 1}">
                            <tr>
                                <td class="text-center align-middle">
                                    <input type="checkbox" x-model="selectedRow[value.id]" x-bind:checked="selectedRow[value.id]">
                                </td>
                                <td x-text="from + index" class="text-center align-middle"></td>
                                <td x-text="value.code" class="align-middle"></td>
                                <td x-text="value.type" class="text-center align-middle"></td>
                                <td x-text="value.name" class="align-middle"></td>
                                <td x-text="value.supplier_name" class="align-middle"></td>
                                <td x-text="value.signing_date" class="text-center align-middle"></td>
                                <td class="align-middle" x-text="formatCurrencyVND(value.contract_value) + ' đ'"></td>
                                <td class="text-center align-middle">
                                    <span class="tw-px-4 tw-py-1 tw-rounded-full"
                                          :class="value.validity ? 'tw-bg-[#54B435]' : 'tw-bg-slate-300'"
                                          x-text="value.validity ? 'On' : 'Off'">
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    @include('component.status.status_contract', ['status' => 'value.status'])
                                </td>
                                <td class="text-center align-middle">
                                    <button class="border-0 bg-white" @click="$dispatch('view', { id: value.id })">
                                        <i class="bi bi-eye" style="color: #63E6BE;"></i>
                                    </button>
                                    <button class="border-0 bg-white" @click="$dispatch('edit', { id: value.id })">
                                        <i class="bi bi-pencil-square color-sc"></i>
                                    </button>
                                    <button class="border-0 bg-white" @click="$dispatch('remove', { id: value.id })">
                                        <i class="bi bi-trash text-red"></i>
                                    </button>
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
                this.dataTable.forEach((item) => this.selectedRow[item.id] = this.checkedAll)
            }
        }
    }
</script>
