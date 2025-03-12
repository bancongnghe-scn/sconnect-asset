@extends('layouts.app_v2', [
    'title' => 'Chi tiết kế hoạch mua sắm quý'
])

@section('x-data')
    x-data="shopping_company_quarter_detail({{$id}})"
@endsection

@section('btn-header')
    <a class="btn btn-warning" href="/shopping-plan-company/quarter/list">Quay lại</a>
@endsection

@section('content')
    <div class="d-flex tw-gap-x-3 h-100">
        <div class="flex-grow-1 overflow-auto custom-scroll">
            {{-- thong tin chung--}}
            <div class="mb-3">
                <div class="d-flex tw-gap-x-4 mb-3">
                    <div class="active-link tw-w-fit">Thông tin chung</div>
                    @include('component.status.status_shopping_plan_company', ['status' => 'data.status'])
                </div>
                <div class="tw-grid tw-grid-cols-7 tw-gap-4">
                    <div class="tw-col-span-2">
                        <label class="tw-font-bold">Tên kế hoạch</label>
                        <input type="text" x-model="data.name" class="form-control" disabled>
                    </div>

                    <div class="tw-col-span-2">
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
                            'placeholder' => 'Chọn người quan sát',
                            'selected' => 'data.monitor_ids',
                            'options' => 'listUser',
                            'disabled' => true
                        ])
                    </div>
                </div>
            </div>

            {{-- thong ke--}}
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
                                <template x-for="number in Array.from({ length: 3 }, (_, i) => i + 1)" :key="number">
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

            {{-- chi tiet--}}
            <div class="mb-3">
                <div class="mb-3 active-link tw-w-fit">Chi tiết</div>
                <div>
                    <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW">
                        @include('assets.shopping-plan-company.table_synthetic_organization_register')
                    </template>
                    <template x-if="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW" x-data="{action: 'view'}">
                        @include('assets.shopping-plan-company.quarter.table_synthetic_asset_organization_register')
                    </template>
                </div>
            </div>
        </div>

        <div class="col-3 border border-right-0 border-top-0 border-bottom-0" x-data="{ id: {{$id}} }">
            @include('component.history_comment.history_comment', ['type' => 'TYPE_COMMENT_SHOPPING_PLAN_COMPANY'])
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_company/quarter/shopping_company_quarter_detail.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
        'resources/js/app/api/apiUser.js'
    ])
@endsection
