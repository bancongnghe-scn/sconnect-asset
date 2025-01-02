<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chi tiết kế hoạch</h4>
                <div>
                    <template x-if="data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL">
                        @can('shopping_plan_company_week.complete')
                            <button class="btn btn-sc" @click="completeShoppingPlan()">Hoàn thành</button>
                        @endcan
                    </template>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-warning">Quay lại</button>
                </div>
            </div>
            <div class="modal-body">
                <div>
                    <div class="d-flex tw-gap-x-4 h-100">
                        <div class="card col-10 mh-100 overflow-y-auto custom-scroll">
                            <div class="card-body">
                                {{--Thong tin chung--}}
                                <div class="mb-3">
                                    <div class="d-flex tw-gap-x-4 mb-3">
                                        <div class="active-link tw-w-fit">Thông tin chung</div>
                                        <div x-show="data.status !== null">
                                            @include('component.shopping_plan_company.status_shopping_plan_company', ['status' => 'data.status'])
                                        </div>
                                    </div>

                                    <div class="tw-grid tw-grid-cols-3 tw-gap-4 mt-3">
                                        <div>
                                            <label class="tw-font-bold">Tên</label>
                                            <input class="form-control" type="text" x-model="data.name" disabled>
                                        </div>

                                        <div>
                                            <label class="tw-font-bold">Thời gian đăng ký<span
                                                    class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                            @include('common.datepicker.datepicker_range', [
                                                   'placeholder' => 'Chọn thời gian đăng ký',
                                                   'disabled' => true,
                                                   'start' => 'data.start_time',
                                                   'end' => 'data.end_time',
                                            ])
                                        </div>

                                        <div>
                                            <label class="form-label">Người quan sát</label>
                                            @include('common.select_custom.extent.select_multiple', [
                                                    'selected' => 'data.monitor_ids',
                                                    'options' => 'listUser',
                                                    'placeholder' => 'Chọn người quan sát',
                                                    'disabled' => true,
                                            ])
                                        </div>
                                    </div>
                                </div>

                                {{--  chi tiet--}}
                                <template x-if="[
                                    STATUS_SHOPPING_PLAN_COMPANY_NEW,
                                    STATUS_SHOPPING_PLAN_COMPANY_REGISTER,
                                    STATUS_SHOPPING_PLAN_COMPANY_HR_HANDLE
                                ].includes(+data.status)">
                                    <div class="mb-3">
                                        <div class="mb-3 active-link tw-w-fit">Chi tiết</div>
                                        <div class="tw-max-h-dvh overflow-scroll custom-scroll">
                                            <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                                @include('component.shopping_plan_company.table_synthetic_organization_register')
                                            </template>
                                            <template x-if="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                                @include('component.shopping_plan_company.week.table_synthetic_asset_organization_register')
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                {{-- tổng hợp--}}
                                <template x-if="![
                                    STATUS_SHOPPING_PLAN_COMPANY_NEW,
                                    STATUS_SHOPPING_PLAN_COMPANY_REGISTER,
                                    STATUS_SHOPPING_PLAN_COMPANY_HR_HANDLE
                                ].includes(+data.status)">
                                    <div class="mb-3">
                                        <div class="d-flex tw-gap-x-4 mb-3">
                                            <a class="tw-no-underline hover:tw-text-green-500"
                                               :class="activeLink.new ? 'active-link' : 'inactive-link'"
                                               @click="handleShowActive('new')"
                                            >
                                                Tài sản mua sắm
                                            </a>
                                            <a class="tw-no-underline hover:tw-text-green-500"
                                               :class="activeLink.rotation ? 'active-link' : 'inactive-link'"
                                               @click="handleShowActive('rotation')"
                                            >
                                                Tài sản luân chuyển
                                            </a>
                                        </div>
                                        <div class="tw-max-h-dvh overflow-y-scroll custom-scroll">
                                            <div x-show="activeLink.new">
                                                @include('component.shopping_plan_company.week.table_synthetic_action_new')
                                            </div>
                                            <div x-show="activeLink.rotation">
                                                @include('component.shopping_plan_company.week.table_synthetic_action_rotation')
                                            </div>
                                        </div>
                                    </div>
                                </template>
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
</div>

