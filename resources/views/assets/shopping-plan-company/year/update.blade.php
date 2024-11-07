@extends('layouts.app',[
    'title' => 'Chi tiết kế hoạch mua sắm năm'
])

@section('content')
    <div x-data="updateShoppingPlanCompanyYear">
        <div class="mb-3 d-flex gap-2 justify-content-end">
            <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW">
                <button class="btn btn-primary" @click="sentNotificationRegister()">Gửi thông báo</button>
                <button class="btn btn-danger">Xóa</button>
            </template>
            <button class="btn btn-sc" @click="updatePlanYear()">Lưu</button>
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
                                  'tw-text-sky-600 tw-bg-sky-100': +data.status === 1,
                                  'tw-text-purple-600 tw-bg-purple-100': +data.status === 2,
                                  'tw-text-green-600 tw-bg-green-100': +data.status === 3 || +data.status === 4,
                                  'tw-text-green-900 tw-bg-green-100'  : +data.status === 5,
                                  'tw-text-red-600 tw-bg-red-100'  : +data.status === 6
                              }"></span>
                        </div>
                        <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                            <div>
                                <label class="tw-font-bold">Năm<span
                                        class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                <input type="text" class="form-control yearPicker" id="selectYear" x-model="data.time"
                                       autocomplete="off"
                                       :disabled="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW">
                            </div>

                            <div>
                                <label class="tw-font-bold">Thời gian đăng ký<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                                <input type="text" class="form-control dateRange" id="selectDateRegister"
                                       placeholder="Chọn thời gian đăng ký" autocomplete="off">
                            </div>

                            <div>
                                <label class="form-label">Người quan sát</label>
                                <select class="form-select select2" id="selectUser" multiple="multiple" data-placeholder="Chọn người quan sát">
                                    <template x-for="value in listUser" :key="value.id">
                                        <option :value="value.id" x-text="value.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="mb-3 active-link tw-w-fit">Chi tiết</div>
                        <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW">
                            @include('component.shopping_plan_company.year.table_synthetic_organization_register')
                        </template>
                        <template x-if="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW">
                            @include('component.shopping_plan_company.year.table_synthetic_asset_organization_register')
                        </template>
                    </div>
                </div>
            </div>
            <div class="card tw-w-[20%]">
                <div class="container d-flex tw-gap-x-4 mt-3">
                    <a href="#" class="tw-no-underline hover:tw-text-green-500"
                       :class="activeLink.history ? 'active-link' : 'inactive-link'"
                       @click="handleShowActive('history')"
                    >
                        Lịch sử
                    </a>
                    <a href="#" class="tw-no-underline hover:tw-text-green-500"
                       :class="activeLink.comment ? 'active-link' : 'inactive-link'"
                       @click="handleShowActive('comment')"
                    >
                        Bình luận
                    </a>
                </div>
                <div class="mt-3" style="border-top: 1px solid">
                    <div class="container mt-3">
                        <template x-for="log in logs">
                            <div class="card">
                                <p class="mb-0" x-text="log.created_at"></p>
                                <div>
                                    <p class="tw-inline tw-font-bold" x-text="log.created_by + ': '"></p>
                                    <span x-text="log.desc"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_company/year/updateShoppingPlanCompanyYear.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
        'resources/js/assets/api/shopping_plan_company/year/apiShoppingPlanCompanyYear.js',
        'resources/js/app/api/apiUser.js',
        'resources/js/assets/api/apiShoppingPlanLog.js',
    ])
@endsection
