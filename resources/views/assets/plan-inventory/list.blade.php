@extends('layouts.app',[
    'title' => ' Danh sách kiểm kê'
])

@section('content')
    <div x-data="plan_inventory">
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
