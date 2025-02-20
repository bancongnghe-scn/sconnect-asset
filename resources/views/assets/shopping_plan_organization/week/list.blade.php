@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm tuần'
])

@section('content')
    @include('assets.shopping_plan_organization.week.content_list')
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_organization/week/shoppingPlanOrganizationWeek.js',
        'resources/js/assets/api/shopping_plan_organization/week/apiShoppingPlanOrganizationWeek.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
        'resources/js/assets/api/shopping_plan_organization/apiShoppingPlanOrganization.js',
        'resources/js/assets/api/apiAssetType.js',
        'resources/js/app/api/apiJob.js',
    ])
@endsection
