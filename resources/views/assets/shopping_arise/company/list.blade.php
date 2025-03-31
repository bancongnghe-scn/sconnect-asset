@extends('layouts.app',[
    'title' => 'Đề xuất mua sắm phát sinh'
])

@section('content')
    <div x-data="shopping_arise_company_list">
        <div class="card card-body">
            <div>
                @include('assets.shopping_arise.company.filters')
            </div>

            <div class="mt-3">
                @include('assets.shopping_arise.company.table')
            </div>
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_arise/company/shopping_arise_list.js',
        'resources/js/assets/api/apiShoppingArise.js',
        'resources/js/assets/api/apiAssetType.js',
        'resources/js/app/api/apiJob.js'
    ])
@endsection
