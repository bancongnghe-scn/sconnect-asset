@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm quý'
])

@section('content')
    <div x-data="register_shopping_plan_organization_quarter">
        <div class="mb-3 d-flex gap-2 justify-content-end">
            <template x-if="
                (+data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER || +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED)
                && ( new Date() > new Date(data.start_time) &&  new Date() < new Date(data.end_time))">
                <button class="btn btn-primary" @click="sentRegister">Đăng ký</button>
            </template>
            <button class="btn btn-warning" @click="window.location.href = `/shopping-plan-company/quarter/list`">Quay lại</button>
        </div>
        <div class="d-flex">
            <div class="card flex-grow-1 mr-3">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex tw-gap-x-4 mb-3">
                            <div class="active-link tw-w-fit">Thông tin chung</div>
                            <span x-show="data.status" x-text="STATUS_SHOPPING_PLAN_ORGANIZATION[data.status]" class="p-1 border rounded"
                                  :class="{
                                            'tw-text-purple-600 tw-bg-purple-100': +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER,
                                            'tw-text-green-600 tw-bg-green-100': +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED
                                            || +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
                                            'tw-text-green-900 tw-bg-green-100'  : +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED
                                            || +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL
                                            || +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_APPROVAL,
                                            'tw-text-red-600 tw-bg-red-100'  : +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_CANCEL || +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNT_CANCEL
                                  }"
                            ></span>
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
                            <div>
                                <template x-for="(register, index) in registers" :key="index">
                                    <div class="p-4 tw-bg-[#E4F0E6] mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 d-flex align-items-center tw-gap-x-6 mr-5">
                                                <span class="form-control" style="flex: 1;" x-text="`Tháng ${register.month}`"></span>

                                                <div class="d-flex align-items-center" style="flex: 1;">
                                                    <span class="me-2 flex-shrink-0 tw-font-bold">Tổng số lượng</span>
                                                    <span class="form-control text-center" x-text="`${register.register.total}`"></span>
                                                </div>

                                                <div class="d-flex align-items-center" style="flex: 1;">
                                                    <span class="me-2 flex-shrink-0 tw-font-bold">Tổng giá trị</span>
                                                    <span class="form-control text-center"
                                                          x-text="`${window.formatCurrencyVND(register.register.price)}`"
                                                    ></span>
                                                </div>
                                            </div>

                                            <button class="btn" @click="handleShowTable(index)">
                                                <i class="fa-solid fa-chevron-down"></i>
                                            </button>
                                        </div>

                                        <div class="card card-body mt-3" x-show="table_index.includes(index)">
                                            <div class="tw-max-w-full overflow-x-scroll custom-scroll">
                                                <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                                                    <thead>
                                                    <tr class="tw-text-nowrap">
                                                        <th rowspan="1" colspan="1">Loại tài sản</th>
                                                        <th rowspan="1" colspan="1">Đơn vị tính</th>
                                                        <th rowspan="1" colspan="1">Chức danh</th>
                                                        <th rowspan="1" colspan="1">Đơn giá</th>
                                                        <th rowspan="1" colspan="1">Số lượng</th>
                                                        <th rowspan="1" colspan="1">Tổng</th>
                                                        <th rowspan="1" colspan="1">Mô tả</th>
                                                        <th rowspan="1" colspan="1"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <template x-for="(asset, key) in register.assets" :key="`asset_${asset.id || asset.id_fake}`">
                                                        <tr class="tw-text-nowrap"
                                                            x-data="{
                                                            get measure() {
                                                                if (asset.asset_type_id) {
                                                                    return list_asset_type.find((item) => +item.id === +asset.asset_type_id).measure
                                                                }
                                                            }
                                                        }"
                                                            x-init="$watch('asset.price', value => calculatePrice(index))"
                                                        >
                                                            <td>
                                                            <span x-data="{values: list_asset_type, model: asset.asset_type_id}"
                                                                  @select-change="
                                                                      asset.asset_type_id = $event.detail
                                                                      asset.price = getPrice(asset.asset_type_id, asset.job_id)
                                                                  "
                                                            >
                                                                @include('common.select2.extent.select2', [
                                                                   'placeholder' => 'Chọn tài sản',
                                                                ])
                                                            </span>
                                                            </td>
                                                            <td class="align-middle"
                                                                x-text="measure"
                                                            >
                                                            </td>
                                                            <td>
                                                            <span x-data="{values: list_job, model: asset.job_id}"
                                                                  @select-change="
                                                                      asset.job_id = $event.detail
                                                                      asset.price = getPrice(asset.asset_type_id, asset.job_id)
                                                                  "
                                                            >
                                                                @include('common.select2.extent.select2', ['placeholder' => 'Chọn chức danh'])
                                                            </span>
                                                            </td>
                                                            <td class="align-middle" x-text="window.formatCurrencyVND(asset.price)"></td>
                                                            <td>
                                                                <input class="form-control w-auto" type="number" min="1"
                                                                       x-model="asset.quantity_registered"
                                                                       @change="validateQuantityRegistered(asset.quantity_registered)"
                                                                       @input="
                                                                       asset.quantity_approved = asset.quantity_registered
                                                                       calculateRegister(index)
                                                                   "
                                                                >
                                                            </td>
                                                            <td class="align-middle" x-text="window.formatCurrencyVND(asset.quantity_registered * asset.price)"></td>
                                                            <td>
                                                                <input class="form-control w-auto" x-model="asset.description" type="text">
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <button class="border-0 bg-body" @click="deleteRow(index, key)">
                                                                    <i class="fa-solid fa-trash" style="color: #cd1326;"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <button type="button" class="btn btn-sc tw-w-fit mt-3" @click="addRow(index)">Thêm hàng</button>
                                        </div>
                                    </div>
                                </template>
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
       'resources/js/assets/shopping_plan_organization/quarter/register_shopping_plan_organization_quarter.js',
       'resources/js/assets/history_comment/history_comment_shopping_plan_organization.js',
       'resources/js/assets/api/shopping_plan_organization/apiShoppingPlanOrganization.js',
       'resources/js/assets/api/apiAssetType.js',
       'resources/js/app/api/apiJob.js',
       'resources/js/assets/api/shopping_plan_organization/quarter/apiShoppingPlanOrganizationQuarter.js',
    ])
@endsection