@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm tuần'
])

@section('content')
    @include('component.shopping_plan_company.week.content_list')
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_company/week/shoppingPlanCompanyWeek.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
        'resources/js/assets/api/shopping_plan_company/week/apiShoppingPlanCompanyWeek.js',
        'resources/js/app/api/apiUser.js',
        'resources/js/assets/history_comment/comment_shopping_plan_company.js',
        'resources/js/assets/api/shopping_plan_organization/apiShoppingPlanOrganization.js',
        'resources/js/assets/api/apiSupplier.js',
        'resources/js/assets/api/apiShoppingAsset.js'
    ])
@endsection
