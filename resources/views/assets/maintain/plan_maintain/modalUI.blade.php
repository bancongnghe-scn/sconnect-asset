<div class="modal fade" id="modalUIPlanMaintain" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="title + ' kế hoạch bảo dưỡng'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <div class="tw-grid tw-grid-cols-5 gap-3 align-items-end">
                            <div class="tw-col-span-2">
                                <label class="tw-font-bold">Tên kế hoạch<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                <input class="form-control" type="text" x-model="data.name" placeholder="Nhập tên kế hoạch">
                            </div>
                            <div>
                                <label class="tw-font-bold">Thời gian<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                @include('common.datepicker.datepicker_range', [
                                    'placeholder' => 'Chọn khoảng thời gian',
                                    'start' => 'data.start_time',
                                    'end' => 'data.end_time'
                                ])
                            </div>
                            <div class="tw-col-span-2">
                                <label class="tw-font-bold">Đơn vị bảo dưỡng<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                <div x-init="$watch('data.organization_ids', (newValue, oldValue) => getAssetMaintainForOrganization(newValue, oldValue))">
                                    @include('common.select_custom.extent.select_multiple', [
                                        'placeholder' => 'Chọn đơn vị bảo dưỡng',
                                        'options' => 'listOrganization',
                                        'selected' => 'data.organization_ids'
                                    ])
                                </div>
                            </div>
                            <div class="tw-col-span-2">
                                <label class="tw-font-bold">Đơn vị thực hiện bảo dưỡng<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                @include('common.select_custom.extent.select_multiple', [
                                    'placeholder' => 'Chọn đơn vị thực hiện bảo dưỡng',
                                    'options' => 'listSupplier',
                                    'selected' => 'data.supplier_ids'
                                ])
                            </div>
                            <div>
                                <label class="tw-font-bold">Chi phí bảo dưỡng</label>
                                <input class="form-control" type="text" x-model="data.maintain_costs" placeholder="Nhập chi phí bảo dưỡng">
                            </div>
                            <div class="tw-col-span-2">
                                <label class="tw-font-bold">Người tham gia</label>
                                @include('common.select_custom.extent.select_multiple', [
                                    'placeholder' => 'Chọn người tham gia',
                                    'options' => 'listSupplier',
                                    'selected' => 'data.user_ids'
                                ])
                            </div>
                            <div class="tw-col-span-3">
                                <label class="tw-font-bold">Mô tả</label>
                                <input class="form-control" type="text" x-model="data.note" placeholder="Nhập mô tả">
                            </div>
                            <div>
                                <input type="checkbox" class="" id="exampleCheck1" x-model="data.sent_notification">
                                <label class="form-check-label" for="exampleCheck1">Gửi thông báo cho đơn vị</label>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="#" class="tw-no-underline hover:tw-text-green-500 active-link"
                               x-text="`Danh sách tài sản bảo dưỡng (${data.assets_maintain?.length})`"></a>

                            <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4 mt-3">
                                <div class="row">
                                    <div class="col-sm-12 table-responsive custom-scroll">
                                        <table id="example2" class="table table-bordered dataTable dtr-inline" aria-describedby="example2_info">
                                            <thead>
                                                <tr>
                                                    <th rowspan="1" colspan="1" class="text-center" style="width: 7rem;">
                                                        Mã tài sản
                                                    </th>
                                                    <th rowspan="1" colspan="1" class="text-center" style="width: 23rem;">
                                                        Tên tài sản
                                                    </th>
                                                    <th rowspan="1" colspan="1" class="text-center" style="width: 12rem;">
                                                        Ngày BD gần nhất
                                                    </th>
                                                    <th rowspan="1" colspan="1" class="text-center" style="width: 7rem;">
                                                        Số serial
                                                    </th>
                                                    <th rowspan="1" colspan="1" class="text-center" style="width: 21rem;">
                                                        Loại tài sản
                                                    </th>
                                                    <th rowspan="1" colspan="1" class="text-center">
                                                        Đơn vị
                                                    </th>
                                                    <th rowspan="1" colspan="1" class="text-center" style="width: 16rem;">
                                                        Nhân viên sử dụng
                                                    </th>
                                                    <th rowspan="1" colspan="1" class="text-center" style="width: 9rem;">
                                                        Trạng thái
                                                    </th>
                                                    <th rowspan="1" colspan="1" class="text-center" style="width: 3rem;">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="(value, key) in data.assets_maintain">
                                                    <tr>
                                                        <td class="text-center align-middle" x-text="value.code"></td>
                                                        <td class="align-middle" x-text="value.name"></td>
                                                        <td class="text-center align-middle" x-text="formatDateVN(value.recent_maintenance_date)"></td>
                                                        <td class="text-center align-middle" x-text="value.serial_number"></td>
                                                        <td class="align-middle" x-text="value.asset_type_name"></td>
                                                        <td class="align-middle">
                                                            <div>
                                                                <template x-for="(organization_name, key) in value.organization.hierarchy ?? []" :key="key">
                                                                    <div x-text="organization_name" :class="key === 0 ? 'tw-font-bold' : ''"></div>
                                                                </template>
                                                            </div>
                                                        </td>
                                                        <td class="text-center align-middle" x-data="{data: value, key: 'user'}">
                                                            @include('common.user_info')
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            @include('component.status.status_plan_maintain_asset', [
                                                                'status' => 'value.status'
                                                            ])
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <span class="tw-cursor-pointer"
                                                                  @click="data.assets_maintain.splice(key,1)">
                                                                <i class="bi bi-trash text-red"></i>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 border border-right-0 border-top-0 border-bottom-0" x-show="action !== 'create'">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>

