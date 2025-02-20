@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm quý'
])

@section('content')
    @include('assets.shopping_plan_organization.quarter.content_list')
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_organization/quarter/shoppingPlanOrganizationQuarter.js',
        'resources/js/assets/api/shopping_plan_organization/quarter/apiShoppingPlanOrganizationQuarter.js',
        'resources/js/app/api/apiJob.js',
        'resources/js/assets/api/apiAssetType.js',
        'resources/js/assets/api/allocation_rate/apiAllocationRate.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
        'resources/js/assets/api/shopping_plan_organization/apiShoppingPlanOrganization.js',
    ])
@endsection
