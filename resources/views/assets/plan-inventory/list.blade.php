@extends('layouts.app',[
    'title' => ' Danh sách kiểm kê'
])

@section('content')
    <div x-data="plan_inventory">
        <div>
            <div class="card card-body">
                <div>
                    @include('assets.plan-inventory.filters')
                </div>

                <div class="mt-3">
                    @include('assets.plan-inventory.table')
                </div>
            </div>

            <div>
                @include('assets.plan-inventory.modal-insert')
            </div>
        </div>

        {{--modal--}}
        <div
            x-data="{
                modalId: 'idModalConfirmDelete',
                contentBody: 'Bạn có chắc chắn muốn xóa kế hoạch kiểm kê này không ?'
            }"
            @ok="deletePlanInventory"
        >
            @include('common.modal-confirm')
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/inventory/plan_inventory/plan_inventory.js',
        'resources/js/assets/api/apiPlanInventory.js',
        'resources/js/app/api/apiUser.js',
        'resources/js/app/api/apiOrganization.js',
        'resources/js/assets/api/apiAssetType.js',
    ])
@endsection
