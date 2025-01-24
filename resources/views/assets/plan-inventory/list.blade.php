@extends('layouts.app',[
    'title' => ' Danh sách kiểm kê'
])

@section('content')
    <div class="card card-body" x-data="plan_inventory">
        <div>
            @include('assets.plan-inventory.filters')
        </div>

        <div class="mt-3">
            @include('assets.plan-inventory.table')
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/inventory/plan_inventory/plan_inventory.js',
        'resources/js/assets/api/apiPlanInventory.js'
    ])
@endsection
