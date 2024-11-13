@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm năm 2024'
])

@section('content')
    <div x-data="register_shopping_plan_organization_year">
        <div class="mb-3 d-flex gap-2 justify-content-end">
            <template x-if="+data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER
                || +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED ">
                <button class="btn btn-primary" @click="saveRegister">Đăng ký</button>
            </template>
            <button class="btn btn-warning" @click="window.location.href = `/shopping-plan-company/year/list`">Quay lại</button>
        </div>
        <div class="d-flex justify-content-between">
            <div class="card tw-w-[78%]">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex tw-gap-x-4 mb-3">
                            <div class="active-link tw-w-fit">Thông tin chung</div>
                            <span x-text="STATUS_SHOPPING_PLAN_ORGANIZATION[data.status]" class="p-1 border rounded"
                                  :class="{
                                             'tw-text-sky-600 tw-bg-sky-100': +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER,
                                             'tw-text-green-600 tw-bg-green-100': +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED
                                             || +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
                                             'tw-text-green-900 tw-bg-green-100'  : +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED
                                             || +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL
                                             || +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_APPROVAL,
                                             'tw-text-red-600 tw-bg-red-100'  : +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_CANCEL
                                  }"
                            ></span>
                        </div>
                        <div class="tw-grid tw-grid-cols-3 tw-gap-4">
                            <div>
                                <label class="tw-font-bold">Tên</label>
                                <input type="text" class="form-control" x-model="data.name" disabled>
                            </div>

                            <div>
                                <label class="tw-font-bold">Đơn vị</label>
                                <input type="text" class="form-control" x-model="data.organization_name" disabled>
                            </div>

                            <div>
                                <label class="tw-font-bold">Thời gian đăng ký</label>
                                <input type="text" class="form-control" x-model="data.time_register" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="mb-3 active-link tw-w-fit">Chi tiết</div>
                        <div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card tw-w-[20%] tw-h-[80dvh]" x-data="history_comment_shopping_plan_organization">
                @include('component.shopping_plan_company.history_comment')
            </div>
        </div>
    </div>
@endsection

@section('js')
    @vite([
       'resources/js/assets/shopping_plan_organization/year/register_shopping_plan_organization_year.js',
       'resources/js/assets/history_comment/history_comment_shopping_plan_organization.js',
       'resources/js/assets/api/shopping_plan_organization/apiShoppingPlanOrganization.js',
    ])
@endsection
