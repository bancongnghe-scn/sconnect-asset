<div x-data="shoppingPlanOrganizationYear">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('assets.shopping_plan_organization.year.filters')

                    <div
                        @change-page.window="changePage($event.detail.page)"
                        @change-limit.window="changeLimit"
                    >
                        @include('assets.shopping_plan_organization.year.table')
                    </div>

                    @include('assets.shopping_plan_organization.year.detail')
                    @include('assets.shopping_plan_organization.year.register')
                </div>
            </div>
        </div>
    </div>
</div>
