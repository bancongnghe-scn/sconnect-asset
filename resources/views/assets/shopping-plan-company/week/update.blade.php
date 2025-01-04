<div class="modal fade" id="modalUpdate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" x-data="{permission: {{Auth::user()->getAllPermissions()->pluck('name')}}}" x-effect="console.log(permission)">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cập nhật kế hoạch</h4>
                <div class="d-flex tw-gap-x-2">
                    <template x-for="(config, key) in configButtons" :key="key">
                        <template x-if="config.condition()">
                            <template x-for="(button, index) in config.buttons" :key="key + index">
                                <template x-if="!button.permission || permission.includes(button.permission)">
                                    <button :class="button.class" @click="button.action()">
                                        <span x-text="button.text"></span>
                                    </button>
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
                        {{--Thong tin chung--}}
                        <div class="mb-3">
                            <div class="d-flex tw-gap-x-4 mb-3">
                                <div class="active-link tw-w-fit">Thông tin chung</div>
                                <div x-show="data.status !== null">
                                    @include('component.shopping_plan_company.status_shopping_plan_company', ['status' => 'data.status'])
                                </div>
                            </div>

                            <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                <div class="tw-grid tw-grid-cols-3 tw-gap-4">
                                    <div>
                                        <label class="tw-font-bold">Kế hoạch quý<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                        @include('common.select_custom.extent.select_single', [
                                            'selected' => 'data.plan_quarter_id',
                                            'options' => 'listPlanCompanyQuarter',
                                            'placeholder' => 'Chọn kế hoạch quý',
                                            'disabled' => '+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW',
                                            'id' => 'selectPlanQuarter'
                                        ])
                                    </div>

                                    <div>
                                        <label class="tw-font-bold">Tháng<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                        @include('common.select_custom.simple.select_single', [
                                            'selected' => 'data.month',
                                            'options' => 'LIST_MONTHS',
                                            'placeholder' => 'Chọn tháng',
                                            'disabled' => '+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW',
                                            'id' => 'selectMonth'
                                        ])
                                    </div>

                                    <div>
                                        <label class="tw-font-bold">Tuần<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                        @include('common.select_custom.simple.select_single', [
                                            'selected' => 'data.time',
                                            'options' => 'LIST_WEEK',
                                            'placeholder' => 'Chọn tuần',
                                            'disabled' => '+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW',
                                            'id' => 'selectWeek'
                                        ])
                                    </div>
                                </div>
                            </template>

                            <div class="tw-grid tw-grid-cols-3 tw-gap-4 mt-3">
                                <template x-if="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                    <div>
                                        <label class="tw-font-bold">Tên</label>
                                        <input class="form-control" type="text" x-model="data.name" disabled>
                                    </div>
                                </template>

                                <div>
                                    <label class="tw-font-bold">Thời gian đăng ký<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                    @include('common.datepicker.datepicker_range', [
                                           'placeholder' => 'Chọn thời gian đăng ký',
                                           'disabled' => '!([STATUS_SHOPPING_PLAN_COMPANY_NEW, STATUS_SHOPPING_PLAN_COMPANY_REGISTER].includes(+data.status))',
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
                                            'disabled' => '!([STATUS_SHOPPING_PLAN_COMPANY_NEW, STATUS_SHOPPING_PLAN_COMPANY_REGISTER].includes(+data.status))',
                                    ])
                                </div>
                            </div>
                        </div>

                        {{--  chi tiet--}}
                        <template x-if="data.status !== null && statusShowDetail.includes(+data.status)">
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

                        {{-- button phe duyet--}}
                        <div>
                            <template x-for="(config, key) in configButtonsApproval" :key="key">
                                <template x-if="config.condition()">
                                    <template x-if="!config.permission || permission.includes(config.permission)">
                                        <div class="d-flex tw-gap-x-2 justify-content-end">
                                            <template x-for="(button, index) in config.buttons" :key="key + index">
                                                <button :class="button.class"
                                                        x-text="button.text"
                                                        @click="button.action()" :disabled="button.disabled()">
                                                </button>
                                            </template>
                                        </div>
                                    </template>
                                </template>
                            </template>
                        </div>

                        {{-- tổng hợp--}}
                        <template x-if="data.status !== null && !statusShowDetail.includes(+data.status)">
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
                                <div>
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
                    <div class="card col-2">
                        @include('component.shopping_plan_company.history_comment')
                    </div>
                </div>

                {{-- modal--}}
                <div>
                    <div
                        x-data="{
                                modalId: idModalConfirmDelete,
                                contentBody: 'Bạn có chắc chắn muốn xóa kế hoạch mua sắm này không ?'
                            }"
                        @ok="remove"
                    >
                        @include('common.modal-confirm')
                    </div>

                    <div @ok="approvalShoppingAsset(statusDisapproval)">
                        @include('common.modal-note', ['id' => 'modalNoteDisapproval', 'model' => 'note_disapproval'])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

