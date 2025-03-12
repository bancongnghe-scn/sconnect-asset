@extends('layouts.app_v2', [
    'title' => 'Chi tiết kế hoạch mua sắm tuần'
])

@section('x-data')
    x-data="shopping_organization_week_detail({{$id}})"
@endsection

@section('btn-header')
    <a class="btn btn-warning" href="/shopping-plan-company/week/list">Quay lại</a>
@endsection

@section('content')
    <div class="d-flex tw-gap-x-4 h-100">
        <div class="flex-grow-1 overflow-auto custom-scroll">
            {{--thong tin chung--}}
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

            {{--chi tiet--}}
            <div class="mb-3">
                <div class="mb-3 active-link tw-w-fit">Chi tiết</div>
                <table id="example2" class="table table-bordered dataTable dtr-inline" aria-describedby="example2_info">
                    <thead>
                    <tr class="tw-text-nowrap">
                        <th rowspan="1" colspan="1">Loại tài sản</th>
                        <th rowspan="1" colspan="1" class="tw-w-24">Đơn vị tính</th>
                        <th rowspan="1" colspan="1">Chức danh</th>
                        <th rowspan="1" colspan="1" class="tw-w-24">SL</th>
                        <th rowspan="1" colspan="1" class="tw-w-48">Thời gian cần</th>
                        <th rowspan="1" colspan="1">Mô tả</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template x-for="(register, index) in registers" :key="`asset_${register.id || register.id_fake}`">
                        <tr
                            x-data="{
                                get measure() {
                                    if (register.asset_type_id) {
                                        return list_asset_type.find((item) => +item.id === +register.asset_type_id).measure
                                    }
                                }
                            }"
                        >
                            <td>
                                @include('common.select_custom.extent.select_single', [
                                    'selected' => 'register.asset_type_id',
                                    'options' => 'list_asset_type',
                                    'placeholder' => 'Chọn tài sản',
                                    'disabled' => true
                                ])
                            </td>
                            <td class="align-middle" x-text="measure">
                            </td>
                            <td>
                                @include('common.select_custom.extent.select_single', [
                                    'selected' => 'register.job_id',
                                    'options' => 'list_job',
                                    'placeholder' => 'Chọn chức danh',
                                    'disabled' => true
                                ])
                            </td>
                            <td>
                                <input class="form-control" type="number" min="1" x-model="register.quantity_registered" disabled>
                            </td>
                            <td>
                                @include('common.datepicker.datepicker',[
                                    'placeholder' => "Thời gian cần",
                                    'model' => "register.receiving_time",
                                    'disabled' => true
                                ])
                            </td>
                            <td>
                                <input class="form-control" x-model="register.description" type="text" disabled>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-3 border border-right-0 border-top-0 border-bottom-0" x-data="{ id: {{$id}} }">
            @include('component.history_comment.history_comment', ['type' => 'TYPE_COMMENT_SHOPPING_PLAN_ORGANIZATION'])
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_organization/week/shopping_organization_week_detail.js',
        'resources/js/assets/api/shopping_plan_organization/apiShoppingPlanOrganization.js',
        'resources/js/assets/api/apiAssetType.js',
        'resources/js/app/api/apiJob.js',
    ])
@endsection
