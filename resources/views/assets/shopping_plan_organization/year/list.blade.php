@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm năm'
])

@section('content')
    @include('component.shopping_plan_organization.year.list_shopping_plan_organization')
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_plan_organization/year/shoppingPlanOrganizationYear.js',
        'resources/js/assets/api/shopping_plan_organization/year/apiShoppingPlanOrganizationYear.js',
    ])
@endsection
