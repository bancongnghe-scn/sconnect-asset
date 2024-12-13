<div class="modal fade" x-data x-init="$store.globalData.dataSelectMulti" id="idModalCancelMultiple" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="'Xác nhận hủy tài sản hàng loạt'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mb-3">
                    <div class="mb-3 active-link tw-w-fit">Thông tin ghi nhận tài sản đã hủy</div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label class="tw-font-bold">Ngày hủy</label>
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" id="selectSigningDate"
                                       placeholder="Lựa chọn ngày" autocomplete="off">
                                <span class="input-group-text">
                                    <i class="fa-regular fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="tw-font-bold">Lý do hủy</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="reasonCancel" placeholder="Nhập lý do" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="tw-font-bold">Tài sản hủy (<span id="numbAssetCancel"></span>)</label>
                        </div>
                    </div>
                    <table class="table table-bordered table-hover dataSelectMulti dtr-inline"
                        aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <template x-for="(columnName, key) in dataColumnsMultiCancel">
                                <th rowspan="1" colspan="1" x-text="columnName"></th>
                            </template>
                            <th rowspan="1" colspan="1" class="col-2 text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(data,index) in $store.globalData.dataSelectMulti" x-data="{line: 1}">
                            <tr>
                                <template x-for="(columnName, key) in dataColumnsMultiCancel">
                                    <td>
                                        <template x-if="key !== 'find_reason' && key !== 'status'">
                                            <span x-text="data[key]"></span>
                                        </template>
                                        <template x-if="key == 'find_reason'">
                                            <textarea x-model="data[key]" placeholder="Nhập mô tả" class="border-0 rounded-1"></textarea>
                                        </template>
                                    </td>
                                </template>
                                <td class="text-center align-middle">
                                    <button class="border-0 bg-body" x-show="showAction.delete ?? true" @click="$dispatch('delete', { id: data.id })">
                                        <i class="fa-solid fa-xmark tw-text-red-600"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="cancelMulti()" type="button" class="btn btn-sc">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>

