<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Chi tiết</title>
    <link rel="icon" type="image/png" href="/images/fav-sc-icon.png"/>

    <script src="/js/const.js"></script>
    <script src='{{ asset('/js/jquery.js') }}'></script>
    <script src='{{ asset('/js/select2.full.js') }}'></script>
    @vite([
           'resources/css/app.css',
           'resources/sass/app.scss',
           'resources/css/custom.css',
           'resources/js/app.js',
    ])
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div x-data="{id: {{$id}}}" class="container">
    <div class="mb-3 active-link tw-w-fit">Thông tin chung</div>
    <div x-data="searchableDropdown()" class="position-relative mx-auto mt-5">
        <!-- Trigger Button -->
        <button
            class="form-select tw-w-fit"
            type="button"
            @click="toggleDropdown"
            aria-expanded="false"
        >
            <span x-text="selectedLabel || 'Chọn giá trị'"></span>
        </button>

        <!-- Dropdown Menu -->
        <div
            x-show="open"
            @click.away="closeDropdown"
            class="tw-w-fit"
            x-cloak
        >
            <!-- Input Tìm Kiếm -->
            <input
                type="text"
                x-model="search"
                class="form-control mb-2"
                placeholder="Tìm kiếm..."
                @keydown.stop
            />

            <!-- Danh sách các lựa chọn -->
            <ul>
                <template x-for="option in filteredOptions" :key="option.value">
                    <li>
                        <a
                            href="#"
                            @click.prevent="selectOption(option)"
                            :class="selected === option.value ? 'font-weight-bold text-primary' : ''"
                            x-text="option.label"
                        ></a>
                    </li>
                </template>
                <template x-if="filteredOptions.length === 0">
                    <li class="text-muted">Không tìm thấy kết quả</li>
                </template>
            </ul>
        </div>
    </div>
</div>
<script>
    function searchableDropdown() {
        return {
            open: false, // Trạng thái của dropdown
            search: '', // Từ khóa tìm kiếm
            selected: null, // Giá trị đã chọn
            options: [
                { value: 'option1', label: 'Lựa chọn 1' },
                { value: 'option2', label: 'Lựa chọn 2' },
                { value: 'option3', label: 'Lựa chọn 3' },
                { value: 'option4', label: 'Lựa chọn 4' },
            ],

            get filteredOptions() {
                return this.options.filter(option =>
                    option.label.toLowerCase().includes(this.search.toLowerCase())
                );
            },

            toggleDropdown() {
                this.open = !this.open;
            },

            closeDropdown() {
                this.open = false;
            },

            selectOption(option) {
                this.selected = option.value; // Gán giá trị đã chọn
                this.selectedLabel = option.label; // Gán nhãn giá trị đã chọn
                this.search = ''; // Reset ô tìm kiếm
                this.closeDropdown();
            },

            selectedLabel: null, // Hiển thị nhãn của lựa chọn đã chọn
        };
    }
</script>
</body>
</html>
