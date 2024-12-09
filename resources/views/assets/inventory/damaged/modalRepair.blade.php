<div class="modal fade" x-data="{ location: 'company' }"
    id="idModalRepair" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="Close">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin sửa chữa tài sản</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mb-3">
                    <div class="row mb-3 d-flex">
                        <div class="col-6 mb-3">
                            <label class="form-label">Ngày sửa chữa</label>
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" id="dateRepair"
                                       placeholder="Lựa chọn ngày" autocomplete="off">
                                <span class="input-group-text">
                                    <i class="fa-regular fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Ngày hoàn thành</label>
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" id="dateRepaired"
                                       placeholder="Lựa chọn ngày" autocomplete="off">
                                <span class="input-group-text">
                                    <i class="fa-regular fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="address" placeholder="--Nhập--">
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="dynamic-content">
                                <template x-if="location === 'company'">
                                    <div>
                                        <label class="form-label">Người thực hiện</label>
                                        <select class="form-control select2" id="idPerformer" data-placeholder="Chọn trạng thái">
                                            <template x-for="(value, key) in performer">
                                                <option :value="key" x-text="value"></option>
                                            </template>
                                        </select>
                                    </div>
                                </template>
                                <template x-if="location === 'supplier'">
                                    <div>
                                        <label class="form-label">Nhà cung cấp</label>
                                        <select class="form-control select2" id="idSupplier" data-placeholder="Chọn trạng thái">
                                            <template x-for="(value, key) in supplier">
                                                <option :value="key" x-text="value"></option>
                                            </template>
                                        </select>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Địa điểm sửa chữa</label>
                            <div class="row">
                                <div class="col-3">
                                    <input type="radio" name="location" value="company" id="company" x-model="location"/>
                                    <label for="company">Tại công ty</label>
                                </div>
                                <div class="col-3">
                                    <input type="radio" name="location" value="supplier" id="supplier" x-model="location"/>
                                    <label for="supplier">Nhà cung cấp</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="modal-title">Tài sản sửa chữa</label>
                    <button class="mb-3 tw-w-fit border-0 position-absolute tw-right-4 tw-bg-transparent" style="color: #28a745;" @click="getAssetDamagedModal('repair')">+ Thêm</button>
                </div>
                <div>
                    <table class="table table-bordered table-hover dataSelectMulti dtr-inline"
                        aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <template x-for="(columnName, key) in columnsRepair">
                                <th rowspan="1" colspan="1" x-text="columnName"></th>
                            </template>
                            <th>Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(data,index) in dataRepair" x-data="{line: 1}">
                            <tr>
                                <td class="align-content-center">
                                    <span x-text="data.code"></span>
                                </td>
                                <td class="align-content-center">
                                    <span x-text="data.name"></span>
                                </td>
                                <td class="align-content-center">
                                    <span x-text="data.price"></span>
                                </td>
                                <td class="align-content-center">
                                    <span x-text="data.date"></span>
                                </td>
                                <td class="align-content-center">
                                    <input type="text" class="form-control" placeholder="--Nhập--"
                                            x-model.number="data.cost_repair"
                                            @input="data.cost_repair = $event.target.value.replace(/[^0-9.]/g, '')">
                                </td>
                                <td class="align-content-center">
                                    <textarea x-model="data.note_repair" class="form-control" style="min-height:2rem;"></textarea>
                                </td>
                                <td class="text-center align-middle">
                                    <button class="border-0 bg-body" x-show="showAction.cancel ?? true" @click="$dispatch('cancel', { id: data.id })">
                                        <i class="fa-solid fa-xmark" style="color: #cd1326;"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>

                    <div>
                        @include('assets.inventory.damaged.modalSelectRepair')
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="confirmRepair()" type="button" class="btn btn-sc">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>

