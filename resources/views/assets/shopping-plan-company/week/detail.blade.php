@extends('layouts.app_v2', [
    'title' => 'Chi tiết kế hoạch mua sắm tuần'
])

@section('x-data')
    x-data="shopping_company_week_detail({{$id}})"
@endsection

@section('btn-header')
    <template x-if="data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL">
        @can('shopping_plan_company_week.complete')
            <button class="btn btn-sc" @click="completeShoppingPlan()">Hoàn thành</button>
        @endcan
    </template>
    <a class="btn btn-warning" href="/shopping-plan-company/week/list">Quay lại</a>
@endsection

@section('content')
    <div class="d-flex tw-gap-x-4 h-100">
        <div class="flex-grow-1 overflow-auto custom-scroll" x-data="{action: 'view'}">
            {{--Thong tin chung--}}
            <div class="mb-3">
                <div class="d-flex tw-gap-x-4 mb-3">
                    <div class="active-link tw-w-fit">Thông tin chung</div>
                    <div x-show="data.status !== null">
                        @include('component.status.status_shopping_plan_company', ['status' => 'data.status'])
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
                        @include('common.user.select_multiple', [
                                'selected' => 'data.monitor_ids',
                                'options' => 'listUser',
                                'placeholder' => 'Chọn người quan sát',
                                'disabled' => true,
                        ])
                    </div>
                </div>
            </div>

            {{--  chi tiet--}}
            <template x-if="data.status !== null && statusShowDetail.includes(+data.status)">
                <div class="mb-3">
                    <div class="mb-3 active-link tw-w-fit">Chi tiết</div>
                    <div>
                        <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW">
                            @include('assets.shopping-plan-company.table_synthetic_organization_register')
                        </template>
                        <template x-if="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW">
                            @include('assets.shopping-plan-company.week.table_synthetic_asset_organization_register')
                        </template>
                    </div>
                </div>
            </template>

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
                            @include('assets.shopping-plan-company.week.table_synthetic_action_new')
                        </div>
                        <div x-show="activeLink.rotation">
                            @include('assets.shopping-plan-company.week.table_synthetic_action_rotation')
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <div class="col-3 border border-right-0 border-top-0 border-bottom-0" x-data="{ id: {{$id}} }">
            @include('component.history_comment.history_comment', ['type' => 'TYPE_COMMENT_SHOPPING_PLAN_COMPANY'])
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_company/week/shopping_company_week_detail.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
        'resources/js/assets/api/apiSupplier.js',
        'resources/js/app/api/apiUser.js',
    ])
@endsection
