@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm năm'
])

@section('content')
    @include('common.shopping_plan_company.year.list_shopping_plan_organization')
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_organization/year/shoppingPlanOrganizationYear.js',
        'resources/js/assets/api/shopping_plan_organization/year/apiShoppingPlanOrganizationYear.js',
    ])
@endsection
