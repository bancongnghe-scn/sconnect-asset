@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm quý'
])

@section('content')
    @include('component.shopping_plan_company.quarter.content_list')
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_company/quarter/shoppingPlanCompanyQuarter.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
        'resources/js/assets/api/shopping_plan_company/quarter/apiShoppingPlanCompanyQuarter.js',
        'resources/js/app/api/apiUser.js',
    ])
@endsection
