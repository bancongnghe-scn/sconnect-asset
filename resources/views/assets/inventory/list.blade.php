@section('css')
@vite([
    'resources/css/assets/manage/list.css',
])
@endsection

@extends('layouts.app',[
    'title' => 'Sửa chữa tài sản'
])

@section('content')
<div class="card">
    <div class="card-body">
        <div x-data="{tab: 'assets-damaged'}" class="mt-3">
            <div class="manage_assets_tab">
                <div>
                    <button :class="{'active': tab === 'assets-damaged'}" @click="tab='assets-damaged' ">
                        Tài sản hỏng
                    </button>
                </div>
                <div>
                    <button :class="{'active': tab === 'assets-repair'}" @click="tab='assets-repair' ">
                        Đang sửa chữa
                    </button>
                </div>
            </div>
    
    
            <div x-show="tab === 'assets-damaged' ">
                @include('assets.inventory.damaged.detail')
            </div>
            <div x-show="tab === 'assets-repair' ">
                @include('assets.inventory.repair.detail')
            </div>
    
        </div>
    </div>
</div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/inventory/damaged/assetDamaged.js',
        'resources/js/assets/inventory/damaged/api/apiAssetDamaged.js',
        'resources/js/assets/inventory/repair/assetRepair.js',
        'resources/js/assets/inventory/repair/api/apiAssetRepair.js',
    ])
@endsection