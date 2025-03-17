@extends('layouts.app_v2', [
    'title' => 'Kế hoạch kiểm kê quý IV'
])

@section('x-data')
    x-data="plan_inventory_detail({{$id}})"
@endsection

@section('content')
    <div class="d-flex tw-gap-x-3 h-100">
        <div class="flex-grow-1 overflow-auto custom-scroll">
            {{-- thong tin chung--}}
            <div class="mb-3">
                @include('assets.plan-inventory.plan_inventory_info', ['disabled' => true])
            </div>

            <div>
                <div class="mb-3 active-link tw-w-fit" x-text="`Danh sách tài sản kiểm kê (${data.assets.length})`"></div>
                @include('assets.plan-inventory.plan_inventory_asset')
            </div>
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
