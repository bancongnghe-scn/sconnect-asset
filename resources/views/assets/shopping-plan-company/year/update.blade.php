@extends('layouts.app',[
    'title' => 'Chi tiết kế hoạch mua sắm năm'
])

@section('content')
    <div x-data="updateShoppingPlanCompanyYear">
        <div class="mb-3 d-flex gap-2 justify-content-end">
            @canany(['shopping_plan_company.sent_notifi_register', 'shopping_plan_company.sent_account_approval'])
                <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW">
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" @click="sentNotificationRegister()">Gửi thông báo</button>
                        <button class="btn btn-danger" @click="confirmRemove()">Xóa</button>
                    </div>
                </template>
                <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW || +data.status === STATUS_SHOPPING_PLAN_COMPANY_REGISTER">
                    <button class="btn btn-sc" @click="updatePlanYear()">Lưu</button>
                </template>
                <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_REGISTER && new Date() > new Date(window.formatDate(data.end_time))">
                    <button class="btn btn-primary" @click="sendAccountantApproval()">Gửi duyệt</button>
                </template>
            @endcanany
            <button class="btn btn-warning" @click="window.location.href = `/shopping-plan-company/year/list`">Quay lại</button>
        </div>
        <div class="d-flex justify-content-between">
            <div class="card tw-w-[78%]">
                <div class="card-body">
                    {{--Thong tin chung--}}
                    <div class="mb-3">
                        <div class="d-flex tw-gap-x-4 mb-3">
                            <div class="active-link tw-w-fit">Thông tin chung</div>
                            <span x-text="STATUS_SHOPPING_PLAN_COMPANY[data.status]" class="px-1 border rounded"
                                  :class="{
                                  'tw-text-sky-600 tw-bg-sky-100': +data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW,
                                  'tw-text-purple-600 tw-bg-purple-100': +data.status === STATUS_SHOPPING_PLAN_COMPANY_REGISTER,
                                  'tw-text-green-600 tw-bg-green-100': +data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL
                                                                        || +data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL,
                                  'tw-text-green-900 tw-bg-green-100'  : +data.status === STATUS_SHOPPING_PLAN_COMPANY_APPROVAL,
                                  'tw-text-red-600 tw-bg-red-100'  : +data.status === STATUS_SHOPPING_PLAN_COMPANY_CANCEL
                              }"></span>
                        </div>
                        <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                            <div>
                                <label class="tw-font-bold">Năm<span
                                        class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                <input type="text" class="form-control yearPicker" id="selectYear" x-model="data.time"
                                       autocomplete="off"
                                       :disabled="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW"
                                >
                            </div>

                            <div>
                                <label class="tw-font-bold">Thời gian đăng ký<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                <input type="text" class="form-control dateRange" id="selectDateRegister"
                                       placeholder="Chọn thời gian đăng ký" autocomplete="off"
                                       :disabled="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW && +data.status !== STATUS_SHOPPING_PLAN_COMPANY_REGISTER"
                                >
                            </div>

                            <div>
                                <label class="form-label">Người quan sát</label>
                                <select
                                    x-init="$nextTick(() => {
                                           $($el).on('change', (e) => {
                                               data.monitor_ids = $($el).val()
                                               console.log(data.monitor_ids)
                                           });
                                        })"
                                    class="form-select select2" id="selectUser" multiple="multiple" data-placeholder="Chọn người quan sát"
                                    :disabled="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW && +data.status !== STATUS_SHOPPING_PLAN_COMPANY_REGISTER">
                                    <template x-for="value in listUser" :key="value.id">
                                        <option :value="value.id" x-text="value.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- button phe duyet--}}
                    @canany(['shopping_plan_company.accounting_approval', 'shopping_plan_company.general_approval'])
                        <template x-if="
                            +data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL
                            || +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL
                        ">
                            <div class="d-flex tw-gap-x-2 justify-content-end">
                                <button class="btn bg-sc text-white">Duyệt</button>
                                <button class="btn bg-red">Từ chối</button>
                            </div>
                        </template>
                    @endcanany

                    {{--  thong ke--}}
                    <template x-if="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW">
                        <div class="mb-3">
                            <div class="active-link tw-w-fit">Thống kê</div>
                            <div class="mt-3">
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

                    {{--  chi tiet--}}
                    <div class="mb-3">
                        <div class="mb-3 active-link tw-w-fit">Chi tiết</div>
                        <div class="tw-max-h-dvh overflow-y-scroll custom-scroll">
                            <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                @include('component.shopping_plan_company.year.table_synthetic_organization_register')
                            </template>
                            <template x-if="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW">
                                @include('component.shopping_plan_company.year.table_synthetic_asset_organization_register')
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
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_company/year/updateShoppingPlanCompanyYear.js',
        'resources/js/assets/history_comment/comment_shopping_plan_company.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
        'resources/js/assets/api/shopping_plan_company/year/apiShoppingPlanCompanyYear.js',
        'resources/js/app/api/apiUser.js',
        'resources/js/assets/api/apiComment.js',
        'resources/js/assets/api/shopping_plan_organization/apiShoppingPlanOrganization.js',
    ])
@endsection
