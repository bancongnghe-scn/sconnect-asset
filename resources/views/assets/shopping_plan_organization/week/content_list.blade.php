<div x-data="shoppingPlanOrganizationWeek">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="tw-mt-8">
                        @include('assets.shopping_plan_organization.week.filter')
                    </div>

                    <div>
                        @include('assets.shopping_plan_organization.week.table')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--modal--}}
    <div>
        @include('assets.shopping_plan_organization.week.register')
    </div>
</div>
