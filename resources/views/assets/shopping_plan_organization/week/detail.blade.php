<div class="modal fade" id="{{$id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chi tiết kế hoạch</h4>
                <div>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-warning">Quay lại</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="d-flex tw-gap-x-4 h-100">
                    <div class="flex-grow-1 overflow-auto custom-scroll">
                        <div class="mb-3">
                            <div class="d-flex tw-gap-x-4 mb-3">
                                <div class="active-link tw-w-fit">Thông tin chung</div>
                                @include('component.status.status_shopping_plan_organization', ['status' => 'data.status'])
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
                            <table id="example2" class="table table-bordered dataTable dtr-inline" aria-describedby="example2_info">
                                <thead>
                                <tr class="tw-text-nowrap">
                                    <th rowspan="1" colspan="1">Loại tài sản</th>
                                    <th rowspan="1" colspan="1" class="tw-w-24">Đơn vị tính</th>
                                    <th rowspan="1" colspan="1">Chức danh</th>
                                    <th rowspan="1" colspan="1" class="tw-w-24">SL</th>
                                    <th rowspan="1" colspan="1" class="tw-w-48">Thời gian cần</th>
                                    <th rowspan="1" colspan="1">Mô tả</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="(register, index) in registers" :key="`asset_${register.id || register.id_fake}`">
                                    <tr
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
                                                'disabled' => true
                                            ])
                                        </td>
                                        <td class="align-middle" x-text="measure">
                                        </td>
                                        <td>
                                            @include('common.select_custom.extent.select_single', [
                                                'selected' => 'register.job_id',
                                                'options' => 'list_job',
                                                'placeholder' => 'Chọn chức danh',
                                                'disabled' => true
                                            ])
                                        </td>
                                        <td>
                                            <input class="form-control" type="number" min="1" x-model="register.quantity_registered" disabled>
                                        </td>
                                        <td>
                                            @include('common.datepicker.datepicker',[
                                                'placeholder' => "Thời gian cần", 'model' => "register.receiving_time", 'disabled' => true
                                            ])
                                        </td>
                                        <td>
                                            <input class="form-control" x-model="register.description" type="text" disabled>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-2 border border-right-0 border-top-0 border-bottom-0">
                        @include('assets.shopping-plan-company.history_comment')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

