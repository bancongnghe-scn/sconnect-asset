<div x-data="shoppingPlanOrganizationQuarter">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="tw-mt-8">
                        @include('assets.shopping_plan_organization.quarter.filter')
                    </div>

                    <div>
                        @include('assets.shopping_plan_organization.quarter.table')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('assets.shopping_plan_organization.quarter.register')
</div>

