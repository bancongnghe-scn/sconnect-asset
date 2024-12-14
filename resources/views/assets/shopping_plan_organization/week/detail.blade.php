@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm tuần'
])

@section('content')
    <div x-data="register_shopping_plan_organization_week">
        <div class="mb-3 d-flex gap-2 justify-content-end">
            <button class="btn btn-warning" @click="window.location.href = `/shopping-plan-company/week/list`">Quay lại</button>
        </div>
        <div class="d-flex">
            <div class="card flex-grow-1 mr-3">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex tw-gap-x-4 mb-3">
                            <div class="active-link tw-w-fit">Thông tin chung</div>
                            @include('component.shopping_plan_organization.status_shopping_plan_organization', ['status' => 'data.status'])
                        </div>
                        <div class="tw-grid tw-grid-cols-3 tw-gap-4">
                            <div>
                                <label class="tw-font-bold">Tên</label>
                                <div class="form-control" style="background-color: #E5E7EB" x-text="data.name"></div>
                            </div>

                            <div>
                                <label class="tw-font-bold">Đơn vị</label>
                                <div class="form-control" style="background-color: #E5E7EB" x-text="data.organization_name"></div>
                            </div>

                            <div>
                                <label class="tw-font-bold">Thời gian đăng ký</label>
                                <template x-if="data.start_time !== null">
                                    @include('common.datepicker.datepicker_range', [
                                         'placeholder' => 'Chọn thời gian đăng ký',
                                         'disabled' => true,
                                         'start' => 'data.start_time',
                                         'end' => 'data.end_time',
                                    ])
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="mb-3 active-link tw-w-fit">Chi tiết</div>
                        <template x-if="list_asset_type.length > 0 && list_job.length > 0">
                            <div class="card card-body mt-3">
                                <div class="tw-max-w-full overflow-x-scroll custom-scroll">
                                    <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                                        <thead>
                                        <tr class="tw-text-nowrap">
                                            <th rowspan="1" colspan="1">Loại tài sản</th>
                                            <th rowspan="1" colspan="1">Đơn vị tính</th>
                                            <th rowspan="1" colspan="1">Chức danh</th>
                                            <th rowspan="1" colspan="1">SL</th>
                                            <th rowspan="1" colspan="1">Thời gian cần</th>
                                            <th rowspan="1" colspan="1">Mô tả</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <template x-for="(register, index) in registers" :key="`asset_${register.id || register.id_fake}`">
                                            <tr class="tw-text-nowrap"
                                                x-data="{
                                                  get measure() {
                                                    if (register.asset_type_id) {
                                                       return list_asset_type.find((item) => +item.id === +register.asset_type_id).measure
                                                    }
                                                  }
                                                }"
                                            >
                                                <td>
                                                <span
                                                    x-data="{model: register.asset_type_id}"
                                                    @select-change="register.asset_type_id = $event.detail"
                                                >
                                                    @include('common.select2.extent.select2', [
                                                       'placeholder' => 'Chọn tài sản',
                                                       'values' => 'list_asset_type',
                                                       'disabled' => true
                                                    ])
                                                </span>
                                                </td>
                                                <td class="align-middle" x-text="measure">
                                                </td>
                                                <td>
                                                <span
                                                    x-data="{model: register.job_id}"
                                                    @select-change="register.job_id = $event.detail"
                                                >
                                                      @include('common.select2.extent.select2', [
                                                         'placeholder' => 'Chọn chức danh',
                                                         'values' => 'list_job',
                                                         'disabled' => true
                                                      ])
                                                </span>
                                                </td>
                                                <td>
                                                    <input class="form-control w-auto" type="number" min="1"
                                                           x-model="register.quantity_registered" disabled>
                                                </td>
                                                <td>
                                                    @include('common.datepicker.datepicker',[
                                                        'placeholder' => "Thời gian cần", 'model' => "register.receiving_time", 'disabled' => true
                                                    ])
                                                </td>
                                                <td>
                                                    <input class="form-control w-auto" x-model="register.description" type="text" disabled>
                                                </td>
                                            </tr>
                                        </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </template>
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
       'resources/js/assets/shopping_plan_organization/week/register_shopping_plan_organization_week.js',
       'resources/js/assets/history_comment/history_comment_shopping_plan_organization.js',
       'resources/js/assets/api/shopping_plan_organization/apiShoppingPlanOrganization.js',
       'resources/js/assets/api/apiAssetType.js',
       'resources/js/app/api/apiJob.js',
       'resources/js/assets/api/shopping_plan_organization/week/apiShoppingPlanOrganizationWeek.js',
    ])
@endsection
