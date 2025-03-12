@extends('layouts.app_v2', [
    'title' => 'Chi tiết kế hoạch mua sắm quý'
])

@section('x-data')
    x-data="shopping_organization_quarter_detail({{$id}})"
@endsection

@section('btn-header')
    <template x-if="+data.status_company === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL">
        <template x-for="(config, key) in configButtonsDetail" :key="key">
            <template x-if="config.condition()">
                <template x-for="(button, index) in config.buttons" :key="key + index">
                    <template x-if="permission.includes(button.permission)">
                        <button :class="button.class"  @click="button.action()">
                            <span x-text="button.text"></span>
                        </button>
                    </template>
                </template>
            </template>
        </template>
    </template>
    <a class="btn btn-warning" href="/shopping-plan-company/quarter/list">Quay lại</a>
@endsection

@section('content')
    <div class="d-flex tw-gap-x-3 h-100">
        <div class="flex-grow-1 overflow-auto custom-scroll">
            {{--Thông tin chung--}}
            <div class="mb-3">
                <div class="d-flex tw-gap-x-4 mb-3">
                    <div class="active-link tw-w-fit">Thông tin chung</div>
                    @include('component.status.status_shopping_plan_organization', ['status' => 'data.status'])
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
                        @include('common.datepicker.datepicker_range', [
                                 'placeholder' => 'Chọn thời gian đăng ký',
                                 'disabled' => true,
                                 'start' => 'data.start_time',
                                 'end' => 'data.end_time',
                        ])
                    </div>
                </div>
            </div>

            {{-- chi tiet--}}
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <div class="mb-3 active-link tw-w-fit">Chi tiết</div>
                    <div x-data="{expand: true, decrease: false}">
                        <button class="btn btn-sm btn-secondary" @click="expand = !expand; decrease = !decrease; expand ? handleShowTable('decrease') : handleShowTable('expand')">
                            <i class="bi bi-arrows-angle-expand" x-show="expand"></i>
                            <i class="bi bi-arrows-angle-contract" x-show="decrease"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <template x-for="(register, index) in registers" :key="index">
                        <div class="p-4 tw-bg-[#E4F0E6] mb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 d-flex align-items-center tw-gap-x-6 mr-5">
                                    <span class="form-control" style="flex: 1;" x-text="`Tháng ${register.month}`"></span>

                                    <div class="d-flex align-items-center" style="flex: 1;">
                                        <span class="me-2 flex-shrink-0 tw-font-bold">Tổng số lượng</span>
                                        <span class="form-control text-center" x-text="`${register.approval.total} / ${register.register.total}`"></span>
                                    </div>

                                    <div class="d-flex align-items-center" style="flex: 1;">
                                        <span class="me-2 flex-shrink-0 tw-font-bold">Tổng giá trị</span>
                                        <span class="form-control text-center"
                                              x-text="`${window.formatCurrencyVND(register.approval.price)} / ${window.formatCurrencyVND(register.register.price)}`"
                                        ></span>
                                    </div>
                                </div>

                                <button class="btn" @click="handleShowTable(index)">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </button>
                            </div>

                            <div class="card card-body mt-3" x-show="table_index.includes(index)">
                                <table id="example2" class="table table-bordered dataTable dtr-inline" aria-describedby="example2_info">
                                    <thead>
                                    <tr>
                                        <th rowspan="1" colspan="1">Loại tài sản</th>
                                        <th rowspan="1" colspan="1" class="tw-w-20">Đơn vị</th>
                                        <th rowspan="1" colspan="1" >Chức danh</th>
                                        <th rowspan="1" colspan="1" class="tw-w-28">Đơn giá</th>
                                        <th rowspan="1" colspan="1" class="tw-w-24">Số lượng</th>
                                        <th rowspan="1" colspan="1" class="tw-w-24">Duyệt</th>
                                        <th rowspan="1" colspan="1" >Tổng</th>
                                        <th rowspan="1" colspan="1" >Mô tả</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <template x-for="(asset, key) in register.assets" :key="`asset_${asset.id || asset.id_fake}`">
                                        <tr>
                                            <td>
                                                @include('common.select_custom.extent.select_single', [
                                                   'placeholder' => 'Chọn tài sản',
                                                   'selected' => 'asset.asset_type_id',
                                                   'options' => 'list_asset_type',
                                                   'disabled' => true
                                                ])
                                            </td>
                                            <td class="align-middle" x-text="asset.asset_type_id ? list_asset_type.find((item) => +item.id === +asset.asset_type_id)?.measure : ''"></td>
                                            <td>
                                                @include('common.select_custom.extent.select_single', [
                                                    'placeholder' => 'Chọn chức danh',
                                                    'selected' => 'asset.job_id',
                                                    'options' => 'list_job',
                                                    'disabled' => true
                                                ])
                                            </td>
                                            <td class="align-middle" x-text="window.formatCurrencyVND(asset.price)"></td>
                                            <td>
                                                <input class="form-control" type="number" x-model="asset.quantity_registered" disabled>
                                            </td>
                                            <td>
                                                <input
                                                    class="form-control" type="number" x-model="asset.quantity_approved"
                                                    @input="calculateApproval(index)"
                                                >
                                            </td>
                                            <td class="align-middle" x-text="window.formatCurrencyVND(asset.quantity_registered * asset.price)"></td>
                                            <td>
                                                <input class="form-control" x-model="asset.description" type="text" disabled>
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

        <div class="col-3 border border-right-0 border-top-0 border-bottom-0" x-data="{ id: {{$id}} }">
            @include('component.history_comment.history_comment', ['type' => 'TYPE_COMMENT_SHOPPING_PLAN_ORGANIZATION'])
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_organization/quarter/shopping_organization_quarter_detail.js',
        'resources/js/assets/api/shopping_plan_organization/apiShoppingPlanOrganization.js',
        'resources/js/assets/api/apiAssetType.js',
        'resources/js/app/api/apiJob.js',
    ])
@endsection
