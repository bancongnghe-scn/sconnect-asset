<div x-data="shoppingPlanOrganizationQuarter">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="tw-mt-8">
                        @include('assets.shopping_plan_organization.quarter.filter')
                    </div>

                    <div
                        @change-page.window="changePage($event.detail.page)"
                        @change-limit.window="changeLimit"
                    >
                        @include('assets.shopping_plan_organization.quarter.table')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('assets.shopping_plan_organization.quarter.detail')
    @include('assets.shopping_plan_organization.quarter.register')
</div>

