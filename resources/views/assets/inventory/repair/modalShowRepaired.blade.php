<div class="modal fade" x-data="{ location: 'company' }"
    id="idModalShowRepaired" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <input type="text" class="form-control datepicker"
                                :value="dataShowRepair.date_repaired ? format(dataShowRepair.date_repaired, 'dd/MM/yyyy') : ''"
                                placeholder="Lựa chọn ngày" autocomplete="off" disabled
                            >
                            <span class="input-group-text">
                                <i class="fa-regular fa-calendar-days"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="form-label">Tài sản sửa chữa</label>
                </div>
                <div class="d-block overflow-x-auto tw-whitespace-nowrap">
                    <table class="table table-bordered table-hover dataSelectMulti dtr-inline tw-table-fixed tw-min-w-min"
                        aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <th style="width:100px">Mã tài sản</th>
                            <th style="width:100px">Tên tài sản</th>
                            <th style="width:130px">Giá trị</th>
                            <th style="width:150px">Ngày hỏng</th>
                            <th style="width:150px">Ngày sửa chữa</th>
                            <th style="width:130px">Chi phí sửa chữa</th>
                            <th style="width:200px">Tình trạng sửa chữa</th>
                            <th style="width:180px">Địa điểm sửa chữa</th>
                            <th style="width:150px">Người thực hiện/<br>Đơn vị sửa chữa</th>
                            <th style="width:200px">Địa chỉ</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="align-content-center ">
                                    <span x-text="dataShowRepair?.asset?.code || ''"></span>
                                </td>
                                <td class="align-content-center">
                                    <span x-text="dataShowRepair?.asset?.name || ''"></span>
                                </td>
                                <td class="align-content-center">
                                    <span x-text="dataShowRepair?.asset?.price || ''"></span>
                                </td>
                                <td class="align-content-center">
                                    <span x-text="dataShowRepair?.asset?.date || ''"></span>
                                </td>
                                <td class="align-content-center">
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker"
                                            placeholder="Chọn ngày" autocomplete="off"
                                            :value="dataShowRepair.date_repair ? format(dataShowRepair.date_repair, 'dd/MM/yyyy') : ''"
                                            disabled
                                            >
                                        <span class="input-group-text">
                                            <i class="fa-regular fa-calendar-days"></i>
                                        </span>
                                    </div>
                                </td>
                                <td class="align-content-center">
                                    <input type="text" class="form-control" 
                                        :value="dataShowRepair?.cost_repair"
                                        disabled>
                                </td>
                                <td class="align-content-center">
                                    <textarea disabled x-model="dataShowRepair.note_repair" class="form-control" style="min-height: auto !important;"></textarea>
                                </td>
                                <td>
                                    <select class="form-control" disabled>
                                        <template x-for="(value, key) in addressRepair" :key="key">
                                            <option :value="key" x-text="value" :selected="dataShowRepair.address_repair == key"></option>
                                        </template>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control" disabled>
                                        <!-- Nếu address_repair == 1, hiển thị performer -->
                                        <template x-if="dataShowRepair.address_repair == 1">
                                            <template x-for="(value, key) in performer" :key="key">
                                                <option :value="key" x-text="value" :selected="dataShowRepair.performer_supplier == key"></option>
                                            </template>
                                        </template>
                                
                                        <!-- Nếu address_repair == 2, hiển thị supplier -->
                                        <template x-if="dataShowRepair.address_repair == 2">
                                            <template x-for="(value, key) in supplier" :key="key">
                                                <option :value="key" x-text="value" :selected="dataShowRepair.performer_supplier == key"></option>
                                            </template>
                                        </template>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" disabled class="form-control" x-model="dataShowRepair.address">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>

