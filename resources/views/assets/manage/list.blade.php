@section('css')
@vite([
    'resources/css/assets/manage/list.css',
]);
@endsection

@extends('layouts.app',[
    'title' => 'Mất-Hủy-Thanh lý'
])

@section('content')
    <div x-data="{tab: 'assets-lost'}" class="mt-3 manage_assets">
        <div class="manage_assets_tab">
            <div>
                <button :class="{'active': tab === 'assets-lost'}" @click="tab='assets-lost' ">
                    Tài sản mất
                </button>
            </div>
            <div>
                <button :class="{'active': tab === 'assets-cancel'}" @click="tab='assets-cancel' ">
                    Tài sản hủy
                </button>
            </div>
            <div>
                <button :class="{'active': tab === 'propose-liquidation'}" @click="tab='propose-liquidation' ">
                    Đề nghị thanh lý
                </button>
            </div>
            <div>
                <button :class="{'active': tab === 'assets-liquidation'}" @click="tab='assets-liquidation' ">
                    Kế hoạch thanh lý
                </button>
            </div>
        </div>


        <div x-show="tab === 'assets-lost' ">
            @include('assets.manage.lost.detail')
        </div>
        <div x-show="tab === 'assets-cancel' ">
            @include('assets.manage.cancel.detail')
        </div>
        <div x-show="tab === 'propose-liquidation' ">
            @include('assets.manage.liquidation.detail')
        </div>
        <div x-show="tab === 'assets-liquidation' ">
            @include('assets.manage.plan-liquidation.detail')
        </div>

    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/manage/lost/assetLost.js',
        'resources/js/assets/manage/lost/api/apiAssetLost.js',
        'resources/js/assets/manage/cancel/assetCancel.js',
        'resources/js/assets/manage/cancel/api/apiAssetCancel.js',
        'resources/js/assets/manage/liquidation/assetLiquidation.js',
        'resources/js/assets/manage/liquidation/api/apiAssetLiquidation.js',
        'resources/js/assets/manage/plan-liquidation/assetPlanLiquidation.js',
        'resources/js/assets/manage/plan-liquidation/api/apiAssetPlanLiquidation.js',
    ])
@endsection