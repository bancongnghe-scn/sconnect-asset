<div x-data="shoppingPlanOrganizationWeek">
    <div>
        @include('component.shopping_plan_company.week.filter')
    </div>

    <div
        @change-page.window="changePage($event.detail.page)"
        @change-limit.window="changeLimit"
    >
        @include('assets.shopping_plan_organization.week.table')
    </div>
</div>
