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
                            <th rowspan="1" colspan="1" class="text-center" style="width: 6rem">Mã</th>
                            <th rowspan="1" colspan="1" class="text-center">Tên nhà cung cấp</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 10rem">Ngành hàng</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 9rem">Số điện thoại</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 14rem">Địa chỉ/Website</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 8rem">Đánh giá</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 11rem">Người liên hệ</th>
                            <th rowspan="1" colspan="1" class="col-2 text-center" style="width: 6rem">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(value,index) in dataTable" x-data="{line: 1}">
                            <tr>
                                <td class="text-center align-middle">
                                    <input type="checkbox" x-model="selectedRow[value.id]" x-bind:checked="selectedRow[value.id]">
                                </td>
                                <td x-text="from + index" class="text-center"></td>
                                <td x-text="value.code" class="text-center"></td>
                                <td x-text="value.name"></td>
                                <td x-text="value.industries"></td>
                                <td x-text="value.contact"></td>
                                <td x-text="value.address"></td>
                                <td>
                                    @include('component.status.status_supplier', ['status' => 'value.status'])
                                </td>
                                <td x-text="value.contract_user">
                                </td>
                                <td class="text-center align-middle">
                                    <button class="border-0 bg-white" @click="$dispatch('edit', { id: value.id })">
                                        <i class="bi bi-pencil-square color-sc"></i>
                                    </button>
                                    <button class="border-0 bg-white" @click="$dispatch('remove', { id: value.id })">
                                        <i class="fa-regular fa-trash-can" style="color: #cd1326;"></i>
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
