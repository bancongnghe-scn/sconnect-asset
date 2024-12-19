@vite([
       'resources/css/app.css',
       'resources/sass/app.scss',
       'resources/css/custom.css',
       'resources/js/app.js',
])
<div class="mb-3 active-link tw-w-fit">Thông tin chung</div>

<div x-data="asset-info">

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('asset-info', () => ({
            init() {

            }
        }))
    })
</script>
