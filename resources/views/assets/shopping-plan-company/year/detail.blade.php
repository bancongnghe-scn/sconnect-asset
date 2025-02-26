<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chi tiết kế hoạch mua sắm năm</h4>
                <div>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-warning">Quay lại</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="d-flex tw-gap-x-3 h-100">
                    <div class="flex-grow-1 overflow-auto custom-scroll">
                        <div class="mb-3">
                            <div class="d-flex tw-gap-x-4 mb-3">
                                <div class="active-link tw-w-fit">Thông tin chung</div>
                                @include('component.status.status_shopping_plan_company', ['status' => 'data.status'])
                            </div>
                            <div class="tw-grid tw-grid-cols-5 tw-gap-4">
                                <div>
                                    <label class="tw-font-bold">Năm<span
                                            class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                    @include('common.datepicker.datepicker_year', [
                                            'model' => 'data.time',
                                            'disabled' => true
                                    ])
                                </div>

                                <div>
                                    <label class="tw-font-bold">Thời gian đăng ký<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                    @include('common.datepicker.datepicker_range', [
                                           'placeholder' => 'Chọn thời gian đăng ký',
                                           'disabled' => true,
                                           'start' => 'data.start_time',
                                           'end' => 'data.end_time',
                                    ])
                                </div>

                                <div class="tw-col-span-3">
                                    <label class="form-label">Người quan sát</label>
                                    @include('common.user.select_multiple', [
                                            'placeholder' => 'Chọn người tham gia',
                                            'options' => 'listUser',
                                            'selected' => 'data.monitor_ids',
                                            'disabled' => true
                                    ])
                                </div>
                            </div>
                        </div>

                        <template x-if="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW">
                            <div class="mb-3">
                                <div class="active-link tw-w-fit">Thống kê</div>
                                <div class="mt-3 overflow-x-auto custom-scroll tw-max-w-full">
                                    <table id="example2" class="table table-bordered dataTable dtr-inline"
                                           aria-describedby="example2_info">
                                        <thead>
                                            <tr>
                                                <th colspan="12" class="text-center"
                                                    x-text="`Tổng tiền theo tháng toàn công ty(${window.formatCurrencyVND(register.total_price_company)})`"
                                                >
                                                </th>
                                            </tr>
                                            <tr>
                                                <template x-for="number in Array.from({ length: 12 }, (_, i) => i + 1)" :key="number">
                                                    <th x-text="`T` + number" class="text-center"></th>
                                                </template>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <template x-for="price in register.total_price_months">
                                                    <td x-text="window.formatCurrencyVND(price)"></td>
                                                </template>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </template>

                        <div class="mb-3">
                            <div class="mb-3 active-link tw-w-fit">Chi tiết</div>
                            <div>
                                <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                    @include('assets.shopping-plan-company.table_synthetic_organization_register')
                                </template>
                                <template x-if="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                    @include('assets.shopping-plan-company.year.table_synthetic_asset_organization_register')
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="col-3 border border-right-0 border-top-0 border-bottom-0"
                         x-data="{isActive: false}" x-effect="isActive = action === 'view'"
                    >
                        @include('assets.shopping-plan-company.history_comment')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



