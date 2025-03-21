<div x-data="need_maintain">
    <div>
        @include('assets.maintain.need_maintain.filters')
    </div>

    <div class="mt-3">
        @include('assets.maintain.need_maintain.table')
    </div>

    @include('assets.maintain.need_maintain.modalCalendar')
</div>
@vite([
    'resources/js/assets/maintain/need_maintain.js'
])
