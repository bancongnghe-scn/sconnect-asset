@extends('layouts.app',[
    'title' => 'Chi tiết kế hoạch mua sắm tuần'
])

@section('content')
    <div x-data="updateShoppingPlanCompanyWeek">
        {{-- danh sách button --}}
        <div class="mb-3 d-flex gap-2 justify-content-end">
            <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW">
                <div class="d-flex gap-2">
                    @can('shopping_plan_company.sent_notifi_register')
                        <button class="btn btn-primary" @click="sentNotificationRegister()">Gửi thông báo</button>
                    @endcan
                    @can('shopping_plan_company.crud')
                        <button class="btn btn-danger" @click="confirmRemove()">Xóa</button>
                    @endcan
                </div>
            </template>
            <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW || +data.status === STATUS_SHOPPING_PLAN_COMPANY_REGISTER">
                @can('shopping_plan_company.crud')
                    <button class="btn btn-sc" @click="updatePlanWeek()">Lưu</button>
                @endcan
            </template>
            <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_REGISTER && new Date() > new Date(window.formatDate(data.end_time))">
                @can('shopping_plan_company.handle_shopping')
                    <button class="btn btn-primary" @click="handleShopping()">Xử lý</button>
                @endcan
            </template>
            <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_HR_HANDLE && new Date() > new Date(window.formatDate(data.end_time))">
                @can('shopping_plan_company.synthetic_shopping')
                    <button class="btn btn-primary" @click="syntheticShopping()">Tổng hợp</button>
                @endcan
            </template>
            <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL
                    && new Date() > new Date(window.formatDate(data.end_time))"
            >
                @can('shopping_plan_company.sent_manager_approval')
                    <button class="btn btn-primary" @click="sendManagerApproval()">Gửi duyệt</button>
                @endcan
            </template>
            @can('shopping_plan_company.general_approval')
                <template
                        x-if="[STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL, STATUS_SHOPPING_PLAN_COMPANY_CANCEL].includes(+data.status)">
                    <button class="btn btn-sc"
                            @click="generalApprovalShoppingPlanCompany(GENERAL_TYPE_APPROVAL_COMPANY)">Duyệt
                    </button>
                </template>

                <template
                        x-if="[STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL, STATUS_SHOPPING_PLAN_COMPANY_APPROVAL].includes(+data.status)">
                    <button class="btn bg-red"
                            @click="showModalNoteDisapprovalShoppingCompany()">Từ chối
                    </button>
                </template>
            @endcan
            <button class="btn btn-warning" @click="window.location.href = `/shopping-plan-company/week/list`">Quay lại</button>
        </div>

        {{-- content --}}
        <div class="d-flex justify-content-between">
            <div class="card tw-w-[78%]">
                <div class="card-body">
                    {{--Thong tin chung--}}
                    <div class="mb-3">
                        <div class="d-flex tw-gap-x-4 mb-3">
                            <div class="active-link tw-w-fit">Thông tin chung</div>
                            @include('component.shopping_plan_company.status_shopping_plan_company', ['status' => 'data.status'])
                        </div>
                        <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                            <div>
                                <label class="tw-font-bold">Kế hoạch quý<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                <span x-data="{
                                    model: data.plan_quarter_id,
                                    init() {this.$watch('data.plan_quarter_id', (newValue) => {if (this.model !== newValue) {this.model = newValue}})}
                                }">
                                    @include('common.select2.extent.select2', [
                                          'placeholder' => 'Chọn quý',
                                          'values' => 'listPlanCompanyQuarter',
                                          'disabled' => '+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW',
                                          'id' => 'selectPlanQuarter'
                                    ])
                                </span>
                            </div>

                            <div>
                                <label class="tw-font-bold">Tháng<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                <span x-data="{
                                    model: data.month,
                                    init() {this.$watch('data.month', (newValue) => {if (this.model !== newValue) {this.model = newValue}})}
                                }">
                                    @include('common.select2.simple.select2_single', [
                                          'placeholder' => 'Chọn tháng',
                                          'values' => 'LIST_MONTHS',
                                          'disabled' => '+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW',
                                          'id' => 'selectMonth'
                                    ])
                                </span>
                            </div>

                            <div>
                                <label class="tw-font-bold">Tuần<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                <span x-data="{
                                    model: data.time,
                                    init() {this.$watch('data.time', (newValue) => {if (this.model !== newValue) {this.model = newValue}})}
                                }">
                                    @include('common.select2.simple.select2_single', [
                                          'placeholder' => 'Chọn tuần',
                                          'values' => 'LIST_WEEK',
                                          'disabled' => '+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW',
                                          'id' => 'selectWeek'
                                    ])
                                </span>
                            </div>

                            <div>
                                <label class="tw-font-bold">Thời gian đăng ký<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                @include('common.datepicker.datepicker_range', [
                                       'placeholder' => 'Chọn thời gian đăng ký',
                                       'disabled' => '+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW && +data.status !== STATUS_SHOPPING_PLAN_COMPANY_REGISTER',
                                       'start' => 'data.start_time',
                                       'end' => 'data.end_time',
                                ])
                            </div>

                            <div>
                                <label class="form-label">Người quan sát</label>
                                <div x-data="{
                                        model: data.monitor_ids,
                                        init() {this.$watch('data.monitor_ids', (newValue) => {if (this.model !== newValue) {this.model = newValue}})}
                                    }"
                                     @select-change="data.monitor_ids = $event.detail"
                                >
                                    @include('common.select2.extent.select2_multiple', [
                                        'placeholder' => 'Chọn người quan sát',
                                        'disabled' => '!([STATUS_SHOPPING_PLAN_COMPANY_NEW, STATUS_SHOPPING_PLAN_COMPANY_REGISTER].includes(+data.status))',
                                        'values' => 'listUser',
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- button phe duyet--}}
                    <div>
                        <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL">
                            @can('shopping_plan_company.accounting_approval')
                                <div class="d-flex tw-gap-x-2 justify-content-end">
                                    <button class="btn bg-sc text-white"
                                            @click="accountApprovalMultipleShoppingPlanOrganization(ORGANIZATION_TYPE_APPROVAL)"
                                            :disabled="window.checkDisableSelectRow"
                                    >
                                        Duyệt
                                    </button>
                                    <button class="btn bg-red"
                                            @click="showModalNoteDisapprovalMultiple()"
                                            :disabled="window.checkDisableSelectRow"
                                    >
                                        Từ chối
                                    </button>
                                </div>
                            @endcan
                        </template>
                    </div>

                    {{--  chi tiet--}}
                    <div class="mb-3">
                        <div class="mb-3 active-link tw-w-fit">Chi tiết</div>
                        <div class="tw-max-h-dvh overflow-y-scroll custom-scroll">
                            <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                @include('component.shopping_plan_company.table_synthetic_organization_register')
                            </template>
                            <template x-if="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                @include('component.shopping_plan_company.week.table_synthetic_asset_organization_register')
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card tw-w-[20%] tw-h-[80dvh]" x-data="comment_shopping_plan">
                @include('component.shopping_plan_company.history_comment')
            </div>
        </div>
        <div
                x-data="{
                        modalId: idModalConfirmDelete,
                        contentBody: 'Bạn có chắc chắn muốn xóa kế hoạch mua sắm này không ?'
                    }"
                @ok="remove"
        >
            @include('common.modal-confirm')
        </div>

        <div @ok="accountApprovalShoppingPlanOrganization(id_organization, ORGANIZATION_TYPE_DISAPPROVAL)">
            @include('common.modal-note', ['id' => 'modalNoteDisapproval', 'model' => 'note_disapproval'])
        </div>

        <div @ok="accountApprovalMultipleShoppingPlanOrganization(ORGANIZATION_TYPE_DISAPPROVAL)">
            @include('common.modal-note', ['id' => 'modalNoteDisapprovalMultiple', 'model' => 'note_disapproval'])
        </div>

        <div @ok="generalApprovalShoppingPlanCompany(GENERAL_TYPE_DISAPPROVAL_COMPANY)">
            @include('common.modal-note', ['id' => 'modalNoteDisapprovalPlanCompany', 'model' => 'note_disapproval'])
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_company/week/updateShoppingPlanCompanyWeek.js',
        'resources/js/assets/history_comment/comment_shopping_plan_company.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
        'resources/js/assets/api/shopping_plan_company/week/apiShoppingPlanCompanyWeek.js',
        'resources/js/app/api/apiUser.js',
        'resources/js/assets/api/shopping_plan_organization/apiShoppingPlanOrganization.js'
    ])
@endsection