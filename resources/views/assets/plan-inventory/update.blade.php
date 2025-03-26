@extends('layouts.app_v2', [
    'title' => 'Cập nhật kế hoạch kiểm kê'
])

@section('x-data')
    x-data="plan_inventory_update({{$id}})"
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
            <template x-if="data.status && data.status !== STATUS_INVENTORY_NEW">
                @include('assets.plan-inventory.statistic_plan_inventory')
            </template>

            {{-- thong tin chung--}}
            <div class="mb-3">
                @include('assets.plan-inventory.plan_inventory_info', ['disabled' => false])
            </div>

            <div>
                <div class="mb-3 active-link tw-w-fit" x-text="`Tài sản kiểm kê (${data?.assets?.length ?? 0})`"></div>
                <template x-if="data.status === STATUS_INVENTORY_NEW">
                    @include('assets.plan-inventory.list_asset_new')
                </template>
                <template x-if="data.status && data.status !== STATUS_INVENTORY_NEW">
                    @include('assets.plan-inventory.list_asset_inventory', ['disabled' => false])
                </template>
            </div>
        </div>

        <div class="col-3 border border-right-0 border-top-0 border-bottom-0" x-data="{ id: {{$id}} }">
            @include('component.history_comment.history_comment', ['type' => 'TYPE_COMMENT_PLAN_MAINTAIN'])
        </div>
    </div>

    {{--modal--}}
    <div
        x-data="{
            modalId: 'modalConfirmRemove',
            contentBody: 'Bạn có chắc chắn muốn hủy kế hoạch kiểm kê này không ?'
        }"
        @ok="deletePlanInventory"
    >
        @include('common.modal-confirm')
    </div>

    @include('assets.plan-inventory.modal_confirm_complete')
@endsection

@section('footer')
    <template x-if="data.status !== STATUS_INVENTORIED">
        <button class="btn btn-sc" @click="updatePlanInventory">Lưu</button>
    </template>
    <template x-if="data.status === STATUS_INVENTORY_NEW">
        <button class="btn btn-sc" @click="startPlanInventory">Bắt đầu kiểm kê</button>
    </template>
    <template x-if="data.status && data.status === STATUS_TAKING_INVENTORY">
        <button class="btn btn-outline-success" @click="$('#modalConfirmComplete').modal('show')">Hoàn thành kiểm kê</button>
    </template>
    <template x-if="data.status === STATUS_INVENTORY_NEW">
        <button class="btn btn-outline-success" @click="findPlanInventory">Hủy</button>
    </template>
    <template x-if="data.status === STATUS_INVENTORY_NEW">
        <button class="btn btn-outline-success" @click="$('#modalConfirmRemove').modal('show')">Hủy lịch kiểm kê</button>
    </template>
@endsection

@section('js')
    @vite([
        'resources/js/assets/inventory/plan_inventory/plan_inventory_update.js',
        'resources/js/assets/api/apiPlanInventory.js',
        'resources/js/app/api/apiUser.js',
        'resources/js/app/api/apiOrganization.js',
        'resources/js/assets/api/apiAssetType.js',
    ])
@endsection
