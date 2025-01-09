<div class="modal fade" x-data="tableSelectDamaged" id="idModalDamagedMore" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="Close">
    <div class="modal-dialog modal-xl">
        <div class="modal-content tw-w-2/3 tw-max-w-2xl tw-overflow-auto tw-m-auto tw-max-h-96 tw-mt-28">
            <div class="modal-header">
                <h4 class="modal-title" x-text="'Thêm tài sản hỏng cần sửa chữa'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mb-3">
                    <div class="row mb-3">
                        <div class="col-5 fw-bold" style="text-decoration: underline;color: #28a745;">Danh sách tài sản hỏng</div>
                        <div class="col-7">
                            <input type="text" class="form-control" x-model="filterMore.name_code" placeholder="Tên/mã tài sản" @keydown.enter="getAssetDamagedModal('repair')">
                        </div>
                    </div>
                    <table id="" class="table table-bordered table-hover dataTable dtr-inline"
                            aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <th class="text-center">
                                <input type="checkbox" id="selectedAllAsset" @click="selectedAllAsset">
                            </th>
                            <template x-for="(columnName, key) in dataTheadModalDamagedMore">
                                <th rowspan="1" colspan="1" x-text="columnName"></th>
                            </template>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(data,index) in dataModalDamagedMore" x-data="{line: 1}">
                            <tr>
                                <td class="text-center align-middle">
                                    <input type="checkbox" x-model="selectedDamagedMore[data.id]" x-bind:checked="selectedDamagedMore[data.id]">
                                </td>
                                <td>
                                    <span x-text="data.code"></span>
                                </td>
                                <td>
                                    <span x-text="data.name"></span>
                                </td>
                                <td>
                                    <span x-text="formatDate(data.date)"></span>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="addRepairMore" type="button" class="btn btn-sc">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>
<script>
    function tableSelectDamaged() {
        return {
            checkedAll: false,

            selectedAllAsset() {
                
                this.checkedAll = !this.checkedAll
                this.dataModalDamagedMore.forEach(
                    (item) => {
                        this.selectedDamagedMore[item.id] = this.checkedAll
                    }
                )
            }
        }
    }
</script>
