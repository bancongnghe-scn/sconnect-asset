<div class="col-12" x-data="tableLost">
    <div id="" class="dataTables_wrapper dt-bootstrap4">
        <div class="row">
            <div class="col-sm-12">
                <table id="" class="table table-bordered dataTable dtr-inline"
                        aria-describedby="example2_info">
                    <thead>
                    <tr :class="'position-sticky tw-top-0'">
                        <th class="text-center" style="width: 3rem;">
                            <input type="checkbox" id="selectedAll" @click="selectedAll">
                        </th>
                        <th rowspan="1" colspan="1" style="width: 8rem">Mã tài sản</th>
                        <th rowspan="1" colspan="1">Tên tài sản</th>
                        <th rowspan="1" colspan="1" class="text-center" style="width: 17rem">Nhân viên sử dụng</th>
                        <th rowspan="1" colspan="1" class="text-center" style="width: 7rem">Tình trạng</th>
                        <th rowspan="1" colspan="1" style="width: 10rem">Ngày mất</th>
                        <th rowspan="1" colspan="1" style="width: 11rem">Vị trí tài sản</th>
                        <th rowspan="1" colspan="1">Lý do mất</th>
                        <th rowspan="1" colspan="1" class="text-center" style="width: 6rem">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template x-for="(value,index) in dataTable" x-data="{line: 1}">
                        <tr>
                            <td class="text-center align-middle">
                                <input type="checkbox" x-model="selectedRow[value.id]" x-bind:checked="selectedRow[value.id]">
                            </td>
                            <td x-text="value.code" class="align-middle"></td>
                            <td x-text="value.name" class="align-middle"></td>
                            <td>
                                @include('common.user.user_info', ['user' => 'value.user'])
                            </td>
                            <td class="align-middle">
                                @include('common.table-status-asset', ['status' => 'value.status'])
                            </td>
                            <td x-text="formatDateVN(value.date)" class="align-middle"></td>
                            <td x-text="LIST_LOCATION_ASSET[value.location]" class="align-middle"></td>
                            <td x-text="value.reason" class="align-middle"></td>
                            <td class="text-center align-middle">
                                <button class="border-0 position-relative" style="background-color: unset;" @click="handleBackModalUI(value.id)">
                                    <i class="fa-solid fa-arrow-rotate-left">&#xF117;</i>
                                    <span class="tooltip-text">Tìm thấy</span>
                                </button>
                                <button class="border-0 position-relative" style="background-color: unset;" @click="handleCancelModalUI(value.id)">
                                    <i class="fa-solid fa-xmark"></i>
                                    <span class="tooltip-text">Hủy</span>
                                </button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div
        @change-page.windows.stop="changePage($event.detail.page)"
        @change-limit.window.stop="changeLimit($event.detail.limit)">
        @include('common.pagination')
    </div>
</div>

<script>
    function tableLost() {
        return {
            checkedAll: false,

            selectedAll() {

                this.checkedAll = !this.checkedAll
                this.dataTable.forEach(
                    (item) => {
                        this.selectedRow[item.id] = this.checkedAll
                    }
                )
            }
        }
    }
</script>
