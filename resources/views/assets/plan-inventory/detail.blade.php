@extends('layouts.app_v2', [
    'title' => 'Chi tiết kế hoạch kiểm kê'
])

@section('x-data')
    x-data="plan_inventory_detail({{$id}})"
@endsection

@section('title_other')
    <div class="tw-h-fit">
        @include('component.status.status_plan_inventory', [
          'status' => 'data.status'
        ])
    </div>
@endsection

@section('btn-header')
    <a class="btn btn-warning" href="/plan-inventory/list">Quay lại</a>
@endsection

@section('content')
    <div class="d-flex tw-gap-x-3 h-100">
        <div class="flex-grow-1 overflow-auto custom-scroll">
            {{--thong ke--}}
            <template x-if="data.status !== STATUS_INVENTORY_NEW">
                @include('assets.plan-inventory.statistic_plan_inventory')
            </template>

            {{-- thong tin chung--}}
            <div class="mb-3">
                @include('assets.plan-inventory.plan_inventory_info', ['disabled' => true])
            </div>

            <template x-if="data.status === STATUS_INVENTORY_NEW">
                <div>
                    <div class="mb-3 active-link tw-w-fit" x-text="`Danh sách tài sản kiểm kê (${data?.assets?.length ?? 0})`"></div>
                    @include('assets.plan-inventory.list_asset_new')
                </div>
            </template>

            <template x-if="data.status !== STATUS_INVENTORY_NEW">
                <div>
                    <div class="mb-3 active-link tw-w-fit" x-text="`Tài sản kiểm kê (${data?.assets?.length ?? 0})`"></div>
                    @include('assets.plan-inventory.list_asset_inventory', ['disabled' => true])
                </div>
            </template>
        </div>

        <div class="col-3 border border-right-0 border-top-0 border-bottom-0" x-data="{ id: {{$id}} }">
            @include('component.history_comment.history_comment', ['type' => 'TYPE_COMMENT_PLAN_MAINTAIN'])
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/inventory/plan_inventory/plan_inventory_detail.js',
        'resources/js/assets/api/apiPlanInventory.js',
        'resources/js/app/api/apiUser.js',
        'resources/js/app/api/apiOrganization.js',
        'resources/js/assets/api/apiAssetType.js',
    ])
@endsection
