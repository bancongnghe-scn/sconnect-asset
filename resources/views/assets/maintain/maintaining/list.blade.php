<div x-data="maintaining">
    <div>
        @include('assets.maintain.maintaining.filters')
    </div>

    <div class="mt-3"
         @change-page.window="changePage($event.detail.page)"
         @change-limit.window="changeLimit"
    >
        @include('assets.maintain.maintaining.table')
    </div>
</div>
@vite([
    'resources/js/assets/maintain/maintaining.js'
])
