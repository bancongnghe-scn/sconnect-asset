<div class="modal fade" x-data="{ location: 'company' }"
    id="idModalRepaired" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Hoàn thành sửa chữa tài sản</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
 
                <div class="row mb-3">
                    <div class="col-4">
                        <label class="form-label">Ngày hoàn thành</label>
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="repaired"
                                placeholder="Lựa chọn ngày" autocomplete="off">
                            <span class="input-group-text">
                                <i class="fa-regular fa-calendar-days"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="form-label">Tài sản sửa chữa</label>
                    <button class="mb-3 tw-w-fit border-0 tw-text-green-600 tw-bg-transparent tw-right-4 tw-absolute" @click="getAssetRepair()">+ Thêm</button>
                </div>
                <div class="d-block overflow-x-auto tw-whitespace-nowrap">
                    <table class="table table-bordered table-hover dataSelectMulti dtr-inline tw-table-fixed tw-min-w-min"
                        aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <th class="tw-w-24">Mã tài sản</th>
                            <th class="tw-w-24">Tên tài sản</th>
                            <th class="tw-w-32" >Giá trị</th>
                            <th class="tw-w-36">Ngày hỏng</th>
                            <th class="tw-w-40">Ngày sửa chữa</th>
                            <th class="tw-w-32">Chi phí sửa chữa</th>
                            <th class="tw-w-48">Tình trạng sửa chữa</th>
                            <th class="tw-w-44">Địa điểm sửa chữa</th>
                            <th class="tw-w-36">Người thực hiện/<br>Đơn vị sửa chữa</th>
                            <th class="tw-w-48">Địa chỉ</th>
                            <th class="tw-w-24">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(data,index) in dataRepair" x-data="{line: 1}">
                            <tr>
                                <td class="align-content-center ">
                                    <span x-text="data.asset.code"></span>
                                </td>
                                <td class="align-content-center">
                                    <span x-text="data.asset.name"></span>
                                </td>
                                <td class="align-content-center">
                                    <span x-text="data.asset.price"></span>
                                </td>
                                <td class="align-content-center">
                                    <span x-text="data.date"></span>
                                </td>
                                <td class="align-content-center">
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker"
                                            placeholder="Chọn ngày" autocomplete="off"
                                            :value="data.date_repair ? format(data.date_repair, 'dd/MM/yyyy') : ''"
                                            x-init="
                                                const airDatePicker = new AirDatepicker($el, {
                                                        autoClose: true,
                                                        clearButton: true,
                                                        locale: localeEn,
                                                        dateFormat: 'dd/MM/yyyy',
                                                        onSelect: ({date}) => {
                                                            data.date_repair = date != null ? format(date, 'yyyy-MM-dd') : null
                                                        }
                                                });

                                                $el.addEventListener('keydown', (e) => {
                                                if (e.key === 'Backspace' || e.key === 'Delete') {
                                                    setTimeout(() => {
                                                        data.date_repair = $el.value
                                                        if (!$el.value) {
                                                        data.date_repair = null
                                                        }}, 0)}
                                                })
                                            "
                                            >
                                        <span class="input-group-text">
                                            <i class="fa-regular fa-calendar-days"></i>
                                        </span>
                                    </div>
                                </td>
                                <td class="align-content-center">
                                    <input type="text" class="form-control" 
                                        x-model.number="data.cost_repair"
                                        @input="data.cost_repair = $event.target.value.replace(/[^0-9.]/g, '')">
                                </td>
                                <td class="align-content-center">
                                    <textarea x-model="data.note_repair" class="form-control"></textarea>
                                </td>
                                <td class="align-content-center">
                                    <select class="form-control select2"
                                        @change="handleAddressRepairChange(index, $event)"
                                    >
                                        <template x-for="(value, key) in addressRepair" :key="key">
                                            <option :value="key" x-text="value" :selected="data.address_repair == key"></option>
                                        </template>
                                    </select>
                                </td>
                                <td class="align-content-center">
                                    <select class="form-control select2"
                                        @change="handleSupplierPerformerChange(index, $event)"
                                    >
                                        <!-- Nếu address_repair == 1, hiển thị performer -->
                                        <template x-if="data.address_repair == 1">
                                            <template x-for="(value, key) in performer" :key="key">
                                                <option :value="key" x-text="value" :selected="data.performer_supplier == key"></option>
                                            </template>
                                        </template>
                                
                                        <!-- Nếu address_repair == 2, hiển thị supplier -->
                                        <template x-if="data.address_repair == 2">
                                            <template x-for="(value, key) in supplier" :key="key">
                                                <option :value="key" x-text="value" :selected="data.performer_supplier == key"></option>
                                            </template>
                                        </template>
                                    </select>
                                </td>
                                <td class="align-content-center">
                                    <input type="text" class="form-control" x-model.number="data.address">
                                </td>
                                <td class="text-center align-middle">
                                    <button class="border-0 bg-body" x-show="showAction.cancel ?? true" @click="$dispatch('cancel', { id: data.id })">
                                        <i class="fa-solid fa-xmark tw-text-red-600"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>

                    <div>
                        @include('assets.inventory.repair.modalSelectRepair')
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="confirmRepaired()" type="button" class="btn btn-sc">Hoàn thành</button>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>

