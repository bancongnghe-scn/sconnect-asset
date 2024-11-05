@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm năm'
])

@section('content')
    <div x-data="shoppingPlanOrganizationYear">
        <div>
            @include('assets.shopping-plan-company.year.filter')
        </div>

        <div
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changeLimit"
        >
            @include('assets.shopping_plan_organization.year.table')
        </div>
    </div>

@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_organization/year/shoppingPlanOrganizationYear.js',
        'resources/js/assets/api/shopping_plan_organization/year/apiShoppingPlanOrganizationYear.js',
    ])
@endsection
