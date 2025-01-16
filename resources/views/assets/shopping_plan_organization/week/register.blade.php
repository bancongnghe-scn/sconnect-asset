<div class="modal fade" id="modalRegister" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Kế hoạch mua sắm tuần</h4>
                <div>
                    <template x-if="+data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER ||
                     +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED">
                        <button class="btn btn-primary" @click="sentRegister">Đăng ký</button>
                    </template>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-warning">Quay lại</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="d-flex tw-gap-x-4 h-100">
                    <div class="card card-body col-10 overflow-auto custom-scroll">
                        <div class="mb-3">
                            <div class="d-flex tw-gap-x-4 mb-3">
                                <div class="active-link tw-w-fit">Thông tin chung</div>
                                @include('component.shopping_plan_organization.status_shopping_plan_organization', ['status' => 'data.status'])
                            </div>
                            <div class="tw-grid tw-grid-cols-3 tw-gap-4">
                                <div>
                                    <label class="tw-font-bold">Tên</label>
                                    <div class="form-control" style="background-color: #E5E7EB" x-text="data.name"></div>
                                </div>

                                <div>
                                    <label class="tw-font-bold">Đơn vị</label>
                                    <div class="form-control" style="background-color: #E5E7EB" x-text="data.organization_name"></div>
                                </div>

                                <div>
                                    <label class="tw-font-bold">Thời gian đăng ký</label>
                                    @include('common.datepicker.datepicker_range', [
                                             'placeholder' => 'Chọn thời gian đăng ký',
                                             'disabled' => true,
                                             'start' => 'data.start_time',
                                             'end' => 'data.end_time',
                                    ])
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="mb-3 active-link tw-w-fit">Chi tiết</div>
                            <div>
                                <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                                    <thead>
                                        <tr class="tw-text-nowrap">
                                            <th rowspan="1" colspan="1">Loại tài sản</th>
                                            <th rowspan="1" colspan="1">Đơn vị tính</th>
                                            <th rowspan="1" colspan="1">Chức danh</th>
                                            <th rowspan="1" colspan="1">SL</th>
                                            <th rowspan="1" colspan="1">Thời gian cần</th>
                                            <th rowspan="1" colspan="1">Mô tả</th>
                                            <th rowspan="1" colspan="1"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(register, index) in registers" :key="`asset_${register.id || register.id_fake}`">
                                            <tr class="tw-text-nowrap"
                                                x-data="{
                                                      get measure() {
                                                        if (register.asset_type_id) {
                                                           return list_asset_type.find((item) => +item.id === +register.asset_type_id).measure
                                                        }
                                                      }
                                                }"
                                            >
                                                <td>
                                                    @include('common.select_custom.extent.select_single', [
                                                        'selected' => 'register.asset_type_id',
                                                        'options' => 'list_asset_type',
                                                        'placeholder' => 'Chọn tài sản',
                                                    ])
                                                </td>
                                                <td class="align-middle" x-text="measure">
                                                </td>
                                                <td>
                                                    @include('common.select_custom.extent.select_single', [
                                                        'selected' => 'register.job_id',
                                                        'options' => 'list_job',
                                                        'placeholder' => 'Chọn chức danh',
                                                    ])
                                                </td>
                                                <td>
                                                    <input class="form-control w-auto" type="number" min="1"
                                                           x-model="register.quantity_registered"
                                                           @input="register.quantity_approved = register.quantity_registered">
                                                </td>
                                                <td>
                                                    @include('common.datepicker.datepicker', ['placeholder' => "Thời gian cần", 'model' => "register.receiving_time"])
                                                </td>
                                                <td>
                                                    <input class="form-control w-auto" x-model="register.description" type="text">
                                                </td>
                                                <td class="text-center align-middle">
                                                    <button class="border-0 bg-body" @click="deleteRow(index)">
                                                        <i class="fa-regular fa-trash-can" style="color: #cd1326;"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-sm btn-sc tw-w-fit" @click="addRow()">Thêm hàng</button>
                            </div>
                        </div>
                    </div>
                    <div class="card col-2">
                        @include('component.shopping_plan_company.history_comment')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

