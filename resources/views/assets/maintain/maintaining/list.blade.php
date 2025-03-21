<div x-data="maintaining">
    <div>
        @include('assets.maintain.maintaining.filters')
    </div>

    <div class="mt-3">
        @include('assets.maintain.maintaining.table')
    </div>
</div>
@vite([
    'resources/js/assets/maintain/maintaining.js'
])
