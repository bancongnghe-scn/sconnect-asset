<div x-data="plan_maintain">
    <div>
        @include('assets.maintain.plan_maintain.filters')
    </div>

    <div class="mt-3"
         @change-page.window="changePage($event.detail.page)"
         @change-limit.window="changeLimit"
    >
        @include('assets.maintain.plan_maintain.table')
    </div>

    @include('assets.maintain.plan_maintain.modalUI')
</div>
@vite([
    'resources/js/assets/maintain/plan_maintain.js',
    'resources/js/app/api/apiOrganization.js',
    'resources/js/assets/api/apiSupplier.js',
    'resources/js/app/api/apiUser.js'
])
