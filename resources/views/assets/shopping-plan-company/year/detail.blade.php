@extends('layouts.app',[
    'title' => 'Chi tiết kế hoạch mua sắm năm'
])

@section('content')
    <div x-data="updateShoppingPlanCompanyYear">
        <div class="mb-3 d-flex gap-2 justify-content-end">
            <button class="btn btn-warning" @click="window.location.href = `/shopping-plan-company/year/list`">Quay lại</button>
        </div>
        <div class="d-flex justify-content-between">
            <div class="card tw-w-[78%]">
                <div class="card-body">
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
                                @include('common.datepicker.datepicker_year', [
                                        'model' => 'data.time',
                                        'disabled' => true
                                ])
                            </div>

                            <div>
                                <label class="tw-font-bold">Thời gian đăng ký<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                <template x-if="data.start_time !== null">
                                    @include('common.datepicker.datepicker_range', [
                                       'placeholder' => 'Chọn thời gian đăng ký',
                                       'disabled' => true,
                                       'start' => 'data.start_time',
                                       'end' => 'data.end_time',
                                    ])
                                </template>
                            </div>

                            <template x-if="listUser.length > 0">
                                <div>
                                    <label class="form-label">Người quan sát</label>
                                    <div x-data="{
                                        values: listUser,
                                        model: data.monitor_ids,
                                    }"
                                         @select-change="data.monitor_ids = $event.detail"
                                    >
                                        @include('common.select2.extent.select2_multiple', [
                                            'placeholder' => 'Chọn người quan sát',
                                            'disabled' => true
                                        ])
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <template x-if="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW">
                        <div class="mb-3">
                            <div class="active-link tw-w-fit">Thống kê</div>
                            <div class="mt-3 overflow-x-auto custom-scroll-x tw-max-w-full">
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
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_company/year/updateShoppingPlanCompanyYear.js',
        'resources/js/assets/history_comment/comment_shopping_plan_company.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
        'resources/js/assets/api/shopping_plan_company/year/apiShoppingPlanCompanyYear.js',
        'resources/js/app/api/apiUser.js',
    ])
@endsection
