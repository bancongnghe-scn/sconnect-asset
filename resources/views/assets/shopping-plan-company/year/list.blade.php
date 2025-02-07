@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm năm'
])

@section('content')
    @include('assets.shopping-plan-company.year.content_list')
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_company/year/shoppingPlanCompanyYear.js',
        'resources/js/assets/shopping_plan_organization/year/shoppingPlanOrganizationYear.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
        'resources/js/assets/api/shopping_plan_company/year/apiShoppingPlanCompanyYear.js',
        'resources/js/assets/api/shopping_plan_organization/apiShoppingPlanOrganization.js',
        'resources/js/app/api/apiUser.js',
        'resources/js/assets/api/apiAssetType.js',
        'resources/js/app/api/apiJob.js',
    ])
@endsection
