<div x-data="shoppingPlanOrganizationYear">
    <div>
        @include('component.shopping_plan_company.year.filter')
    </div>

    <div
        @change-page.window="changePage($event.detail.page)"
        @change-limit.window="changeLimit"
    >
        @include('assets.shopping_plan_organization.year.table')
    </div>
</div>
