<div x-data="shoppingPlanOrganizationQuarter">
    <div>
        @include('component.shopping_plan_company.quarter.filter')
    </div>

    <div
        @change-page.window="changePage($event.detail.page)"
        @change-limit.window="changeLimit"
    >
        @include('assets.shopping_plan_organization.quarter.table')
    </div>
</div>
