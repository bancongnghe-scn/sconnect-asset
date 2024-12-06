@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm tuần'
])

@section('content')
    @include('component.shopping_plan_organization.quarter.content_list')
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_organization/quarter/shoppingPlanOrganizationQuarter.js',
        'resources/js/assets/api/shopping_plan_organization/quarter/apiShoppingPlanOrganizationQuarter.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
    ])
@endsection
