<div x-data="shoppingPlanOrganizationYear">
    <div>
        @include('assets.shopping_plan_organization.year.filters')
    </div>

    <div
        @change-page.window="changePage($event.detail.page)"
        @change-limit.window="changeLimit"
    >
        @include('assets.shopping_plan_organization.year.table')
    </div>
</div>
