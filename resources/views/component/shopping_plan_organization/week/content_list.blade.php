<div x-data="shoppingPlanOrganizationWeek">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="tw-mt-8">
                        @include('component.shopping_plan_company.week.filter')
                    </div>

                    <div
                        @change-page.window="changePage($event.detail.page)"
                        @change-limit.window="changeLimit"
                    >
                        @include('assets.shopping_plan_organization.week.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
