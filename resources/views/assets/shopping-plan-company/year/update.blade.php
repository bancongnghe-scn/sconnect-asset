@extends('layouts.app',[
    'title' => 'Chi tiết kế hoạch mua sắm năm'
])

@section('content')
    <div class="mb-3 d-flex gap-2 justify-content-end">
        <button class="btn btn-primary">Gửi thông báo</button>
        <button class="btn btn-sc" @click="updatePlanYear">Lưu</button>
        <button class="btn btn-danger">Xóa</button>
        <button class="btn btn-warning">Quay lại</button>
    </div>
    <div class="d-flex justify-content-between" x-data="updateShoppingPlanCompanyYear">
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
                            <label class="tw-font-bold">Năm<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                            <input type="text" class="form-control yearPicker" x-model="data.time" autocomplete="off" disabled>
                        </div>

                        <div>
                            <label class="tw-font-bold">Thời gian đăng ký<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                            <input type="text" class="form-control dateRange" id="selectDateRegister" placeholder="Chọn thời gian đăng ký" autocomplete="off">
                        </div>

                        <div>
                            <label class="form-label">Người quan sát</label>
                            <select class="form-select select2" x-model="data.monitor_ids" id="selectUser" multiple="multiple" data-placeholder="Chọn người quan sát">
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
                        <div class="tw-max-h-dvh overflow-y-scroll">
                            <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
                                   aria-describedby="example2_info">
                                <thead class="position-sticky z-1" style="top: -1px">
                                    <tr>
                                        <th rowspan="1" colspan="1" class="tw-w-fit">STT</th>
                                        <th rowspan="1" colspan="1" class="text-center">Đơn vị</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(organization,index) in data.organizations">
                                        <tr>
                                            <td x-text="index+1"></td>
                                            <td x-text="organization" class="tw-font-bold"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </template>
{{--                    <template x-if="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_NEW">--}}
{{--                        222222222--}}
{{--                    </template>--}}
                </div>
            </div>
        </div>
        <div class="card tw-w-[20%]">
            <div class="card-body">22222222</div>
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_company/year/updateShoppingPlanCompanyYear.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
        'resources/js/app/api/apiUser.js',
    ])
@endsection
