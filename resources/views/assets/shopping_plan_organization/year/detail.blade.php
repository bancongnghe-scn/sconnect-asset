<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chi tiết kế hoạch mua sắm năm</h4>
                <div class="mb-3 d-flex gap-2 justify-content-end">
                    <template x-if="+data.status_company === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL">
                        <template x-for="(config, key) in configButtons" :key="key">
                            <template x-if="config.condition()">
                                <template x-for="(button, index) in config.buttons" :key="key + index">
                                    <template x-if="!button.permission || permission.includes(button.permission)">
                                        <button :class="button.class"  @click="button.action()">
                                            <span x-text="button.text"></span>
                                        </button>
                                    </template>
                                </template>
                            </template>
                        </template>
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
                                    <template x-if="data.start_time !== null">
                                        @include('common.datepicker.datepicker_range', [
                                             'placeholder' => 'Chọn thời gian đăng ký',
                                             'disabled' => true,
                                             'start' => 'data.start_time',
                                             'end' => 'data.end_time',
                                        ])
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="mb-3 active-link tw-w-fit">Chi tiết</div>
                            <template x-if="list_asset_type.length > 0 && list_job.length > 0">
                                <div>
                                    <template x-for="(register, index) in registers" :key="index">
                                        <div class="p-4 tw-bg-[#E4F0E6] mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 d-flex align-items-center tw-gap-x-6 mr-5">
                                                    <span class="form-control" style="flex: 1;" x-text="`Tháng ${register.month}`"></span>

                                                    <div class="d-flex align-items-center" style="flex: 1;">
                                                        <span class="me-2 flex-shrink-0 tw-font-bold">Tổng số lượng</span>
                                                        <span class="form-control text-center" x-text="`${register.approval.total} / ${register.register.total}`"></span>
                                                    </div>

                                                    <div class="d-flex align-items-center" style="flex: 1;">
                                                        <span class="me-2 flex-shrink-0 tw-font-bold">Tổng giá trị</span>
                                                        <span class="form-control text-center"
                                                              x-text="`${window.formatCurrencyVND(register.approval.price)} / ${window.formatCurrencyVND(register.register.price)}`"
                                                        ></span>
                                                    </div>
                                                </div>

                                                <button class="btn" @click="handleShowTable(index)">
                                                    <i class="fa-solid fa-chevron-down"></i>
                                                </button>
                                            </div>

                                            <div class="card card-body mt-3" x-show="table_index.includes(index)">
                                                <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                                                    <thead>
                                                    <tr>
                                                        <th rowspan="1" colspan="1">Loại tài sản</th>
                                                        <th rowspan="1" colspan="1" class="tw-w-20">Đơn vị</th>
                                                        <th rowspan="1" colspan="1" >Chức danh</th>
                                                        <th rowspan="1" colspan="1" class="tw-w-28">Đơn giá</th>
                                                        <th rowspan="1" colspan="1" class="tw-w-24">Số lượng</th>
                                                        <th rowspan="1" colspan="1" class="tw-w-24">Duyệt</th>
                                                        <th rowspan="1" colspan="1" >Tổng</th>
                                                        <th rowspan="1" colspan="1" >Mô tả</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <template x-for="(asset, key) in register.assets" :key="`asset_${asset.id || asset.id_fake}`">
                                                        <tr>
                                                            <td>
                                                            <span x-data="{values: list_asset_type, model: asset.asset_type_id}">
                                                                @include('common.select2.extent.select2', [
                                                                    'placeholder' => 'Chọn loại tài sản',
                                                                    'disabled' => true
                                                                ])
                                                            </span>
                                                            </td>
                                                            <td class="align-middle" x-text="LIST_MEASURE[asset.asset_type_id]"></td>
                                                            <td>
                                                            <span x-data="{values: list_job, model: asset.job_id}">
                                                                @include('common.select2.extent.select2', [
                                                                    'placeholder' => 'Chọn chức danh',
                                                                    'disabled' => true
                                                                ])
                                                            </span>
                                                            </td>
                                                            <td class="align-middle" x-text="window.formatCurrencyVND(asset.price)"></td>
                                                            <td>
                                                                <input class="form-control" type="number" x-model="asset.quantity_registered" disabled>
                                                            </td>
                                                            <td>
                                                                <input
                                                                    class="form-control" type="number" x-model="asset.quantity_approved"
                                                                    @input="calculateApproval(index)"
                                                                    @cannot('shopping_plan_company.accounting_approval')
                                                                        disabled
                                                                    @endcannot
                                                                >
                                                            </td>
                                                            <td class="align-middle" x-text="window.formatCurrencyVND(asset.quantity_registered * asset.price)"></td>
                                                            <td>
                                                                <input class="form-control" x-model="asset.description" type="text" disabled>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="card col-2" x-data="history_comment_shopping_plan_organization">
                        @include('component.shopping_plan_company.history_comment')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

