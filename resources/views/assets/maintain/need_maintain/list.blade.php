<div x-data="need_maintain">
    <div>
        @include('assets.maintain.need_maintain.filters')
    </div>

    <div class="mt-3"
        @change-page.window="changePage($event.detail.page)"
        @change-limit.window="changeLimit"
    >
        @include('assets.maintain.need_maintain.table')
    </div>

    @include('assets.maintain.need_maintain.modalCalendar')
</div>
@vite([
    'resources/js/assets/maintain/need_maintain.js'
])
