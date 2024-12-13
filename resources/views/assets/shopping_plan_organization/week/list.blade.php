@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm tuần'
])

@section('content')
    @include('component.shopping_plan_organization.week.content_list')
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_organization/week/shoppingPlanOrganizationWeek.js',
        'resources/js/assets/api/shopping_plan_organization/week/apiShoppingPlanOrganizationWeek.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
    ])
@endsection
