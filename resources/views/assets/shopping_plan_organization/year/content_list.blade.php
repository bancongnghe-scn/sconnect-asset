<div x-data="shoppingPlanOrganizationYear">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('assets.shopping_plan_organization.year.filters')

                    <div>
                        @include('assets.shopping_plan_organization.year.table')
                    </div>

                    @include('assets.shopping_plan_organization.year.register')
                </div>
            </div>
        </div>
    </div>
</div>
