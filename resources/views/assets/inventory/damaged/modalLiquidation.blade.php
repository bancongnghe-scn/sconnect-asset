<div class="modal fade" x-data id="idModalLiquidation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="'Đề nghị thanh lý tài sản'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mb-3">
                    <div class="mb-3 active-link tw-w-fit">Thông tin tài sản đề nghị thanh lý</div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label class="tw-font-bold">Ngày thanh lý</label>
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" id="dateLiquidation"
                                       placeholder="Lựa chọn ngày" autocomplete="off">
                                <span class="input-group-text">
                                    <i class="fa-regular fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="tw-font-bold">Lý do thanh lý</label>
                            <div class="input-group">
                                <textarea type="text" class="form-control" id="reasonLiquidation" placeholder="Nhập lý do" style="min-height:2rem;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="tw-font-bold">Tài sản thanh lý</label>
                            <button class="mb-3 tw-w-fit border-0 position-absolute tw-right-4 tw-bg-transparent" style="color: #28a745;" @click="getAssetDamagedModal('liquidation')">+ Thêm</button>
                        </div>
                    </div>
                    <table class="table table-bordered table-hover dataSelectMulti dtr-inline"
                        aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <template x-for="(columnName, key) in dataColumnsLiquidation">
                                <th rowspan="1" colspan="1" x-text="columnName"></th>
                            </template>
                            <th rowspan="1" colspan="1" class="col-2 text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(data,index) in dataLiquidation" x-data="{line: 1}">
                            <tr>
                                <template x-for="(columnName, key) in dataColumnsLiquidation">
                                    <td>
                                        <template x-if="key !== 'find_reason' && key !== 'status' && key !== 'price_liquidation'">
                                            <span x-text="data[key]"></span>
                                        </template>
                                        <template x-if="key == 'price_liquidation'">
                                            <input type="text" 
                                                placeholder="Nhập giá trị thanh lý" 
                                                class="form-control"
                                                x-model.number="data.price_liquidation"
                                                @input="data.price_liquidation = $event.target.value.replace(/[^0-9.]/g, '')"
                                            >
                                        </template>
                                        <template x-if="key == 'find_reason'">
                                            <textarea x-model="data[key]" placeholder="Nhập mô tả" class="border-0 rounded-1"></textarea>
                                        </template>
                                    </td>
                                </template>
                                <td class="text-center align-middle">
                                    <button class="border-0 bg-body" x-show="showAction.remove ?? true" @click="$dispatch('remove', { id: data.id })">
                                        <i class="fa-solid fa-xmark" style="color: #cd1326;"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>

                    <div>
                        @include('assets.inventory.damaged.modalSelectLiquidation')
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="completeLiquidation()" type="button" class="btn btn-sc">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>
