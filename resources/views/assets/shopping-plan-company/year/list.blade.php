@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm năm'
])

@section('content')
    @include('component.shopping_plan_company.year.content_list')
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_company/year/shoppingPlanCompanyYear.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
        'resources/js/assets/api/shopping_plan_company/year/apiShoppingPlanCompanyYear.js',
        'resources/js/app/api/apiUser.js',
    ])
@endsection
