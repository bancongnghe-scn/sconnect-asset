@extends('layouts.app_v2', [
    'title' => 'Chi tiết kế hoạch bảo dưỡng'
])

@section('x-data')
    x-data="plan_maintain_detail({{$id}})"
@endsection

@section('btn-header')
    <a class="btn btn-warning" href="/maintain/list">Quay lại</a>
@endsection

@section('title_other')
    <div class="tw-h-fit">
        @include('component.status.status_plan_maintain', [
          'status' => 'data.status'
        ])
    </div>
@endsection

@section('content')
    <div class="d-flex tw-gap-x-3">
        <div class="flex-grow-1">
            <div class="tw-grid tw-grid-cols-5 gap-3 align-items-end">
                <div class="tw-col-span-2">
                    <label class="tw-font-bold">Tên kế hoạch<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                    <input class="form-control" type="text" x-model="data.name" placeholder="Nhập tên kế hoạch" disabled>
                </div>
                <div>
                    <label class="tw-font-bold">Thời gian<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                    @include('common.datepicker.datepicker_range', [
                        'placeholder' => 'Chọn khoảng thời gian',
                        'start' => 'data.start_time',
                        'end' => 'data.end_time',
                        'disabled' => true
                    ])
                </div>
                <div class="tw-col-span-2">
                    <label class="tw-font-bold">Đơn vị bảo dưỡng<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                    @include('common.select_custom.extent.select_multiple', [
                        'placeholder' => 'Chọn đơn vị bảo dưỡng',
                        'options' => 'listOrganization',
                        'selected' => 'data.organization_ids',
                        'disabled' => true
                    ])
                </div>
                <div class="tw-col-span-2">
                    <label class="tw-font-bold">Đơn vị thực hiện bảo dưỡng<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                    @include('common.select_custom.extent.select_multiple', [
                        'placeholder' => 'Chọn đơn vị thực hiện bảo dưỡng',
                        'options' => 'listSupplier',
                        'selected' => 'data.supplier_ids',
                        'disabled' => true
                    ])
                </div>
                <div>
                    <label class="tw-font-bold">Chi phí bảo dưỡng</label>
                    <input class="form-control" type="text" x-model="data.maintain_costs" placeholder="Nhập chi phí bảo dưỡng" disabled>
                </div>
                <div class="tw-col-span-2">
                    <label class="tw-font-bold">Người tham gia</label>
                    @include('common.user.select_multiple', [
                        'placeholder' => 'Chọn người tham gia',
                        'options' => 'listUser',
                        'selected' => 'data.user_ids',
                        'disabled' => true
                    ])
                </div>
                <div class="tw-col-span-3">
                    <label class="tw-font-bold">Mô tả</label>
                    <input class="form-control" type="text" x-model="data.note" placeholder="Nhập mô tả" disabled>
                </div>
                <div>
                    <input type="checkbox" class="" id="exampleCheck1" x-model="data.sent_notification" disabled>
                    <label class="form-check-label" for="exampleCheck1">Gửi thông báo cho đơn vị</label>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between">
                    <a href="#" class="tw-no-underline hover:tw-text-green-500 active-link"
                       x-text="`Danh sách tài sản bảo dưỡng (${data.assets_maintain?.length})`">
                    </a>
                </div>

                <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4 mt-3">
                    <div class="row">
                        <div class="table-responsive custom-scroll">
                            <table id="example2" class="table table-bordered dataTable dtr-inline" aria-describedby="example2_info">
                                <thead>
                                <tr>
                                    <th rowspan="1" colspan="1" class="text-center">Mã tài sản</th>
                                    <th rowspan="1" colspan="1" class="text-center">Tên tài sản</th>
                                    <th rowspan="1" colspan="1" class="text-center">Ngày BD gần nhất</th>
                                    <th rowspan="1" colspan="1" class="text-center">Số serial</th>
                                    <th rowspan="1" colspan="1" class="text-center">Loại tài sản</th>
                                    <th rowspan="1" colspan="1" class="text-center">Đơn vị</th>
                                    <th rowspan="1" colspan="1" class="text-center">Nhân viên sử dụng</th>
                                    <th rowspan="1" colspan="1" class="text-center">Trạng thái</th>
                                    <th rowspan="1" colspan="1" class="text-center" style="width: 20%">Ghi chú</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="(value, key) in data.assets_maintain">
                                    <tr>
                                        <td class="text-center align-middle" x-text="value.code"></td>
                                        <td class="align-middle" x-text="value.name"></td>
                                        <td class="text-center align-middle" x-text="formatDateVN(value.recent_maintenance_date)"></td>
                                        <td class="text-center align-middle" x-text="value.seri_number"></td>
                                        <td class="align-middle" x-text="value.asset_type_name"></td>
                                        <td class="align-middle">
                                            <div>
                                                <template x-for="(organization_name, key) in value.organization.hierarchy ?? []" :key="key">
                                                    <div x-text="organization_name" :class="key === 0 ? 'tw-font-bold' : ''"></div>
                                                </template>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            @include('common.user.user_info', ['user' => 'value.user'])
                                        </td>
                                        <td class="text-center align-middle">
                                            @include('component.status.status_plan_maintain_asset', [
                                                'status' => 'value.status'
                                            ])
                                        </td>
                                        <td class="align-middle">
                                            <input type="text" class="border-0 w-100 tw-outline-0 border-bottom" x-model="value.note"
                                                   placeholder="Nhập ghi chú" disabled>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3 border border-right-0 border-top-0 border-bottom-0" x-data="{ id: {{$id}} }">
            @include('component.history_comment.history_comment', ['type' => 'TYPE_COMMENT_PLAN_MAINTAIN'])
        </div>
    </div>
@endsection

@section('js')
    @vite([
       'resources/js/assets/maintain/plan-maintain/plan_maintain_detail.js',
       'resources/js/assets/api/maintain/apiMaintain.js',
       'resources/js/app/api/apiOrganization.js',
       'resources/js/assets/api/apiSupplier.js',
       'resources/js/app/api/apiUser.js'
    ])
@endsection
