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
    <style>
        .dropdown-menu {
            border-radius: 0.375rem;
            max-height: 200px;
            overflow-y: auto;
        }

        .form-control {
            cursor: pointer;
        }

    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div x-data="{id: {{$id}}}" class="container">
    <div class="mb-3 active-link tw-w-fit">Thông tin chung</div>
    <div x-data="select2Style" class="position-relative w-100">
        <!-- Input hiển thị các mục đã chọn -->
        <div
            class="form-control d-flex align-items-center flex-wrap gap-2"
            @click="toggleDropdown"
            tabindex="0"
            @blur="closeDropdown"
        >
            <template x-for="(item, index) in selectedItems" :key="index">
            <span class="badge bg-primary d-flex align-items-center gap-1">
                <span x-text="item"></span>
                <button type="button" class="btn-close ms-1" aria-label="Close"
                        @click.stop="removeItem(index)"></button>
            </span>
            </template>
            <span x-show="selectedItems.length === 0" class="text-muted">
            Chọn giá trị...
        </span>
        </div>

        <!-- Dropdown -->
        <ul
            x-show="isOpen"
            class="dropdown-menu show w-100 mt-1 shadow"
            style="max-height: 200px; overflow-y: auto;"
            @mousedown.prevent
        >
            <!-- Input tìm kiếm -->
            <li>
                <input
                    type="text"
                    x-model="search"
                    class="form-control border-0"
                    placeholder="Tìm kiếm..."
                    @input="filterItems"
                    @click.stop
                />
            </li>
            <!-- Danh sách lựa chọn -->
            <template x-for="(item, index) in filteredItems" :key="index">
                <li
                    class="dropdown-item"
                    :class="{'bg-primary text-white': selectedItems.includes(item)}"
                    @click="toggleItem(item)"
                >
                    <span x-text="item"></span>
                </li>
            </template>
            <li x-show="filteredItems.length === 0" class="dropdown-item text-muted">
                Không tìm thấy kết quả.
            </li>
        </ul>

        <input type="hidden" x-model="selectedItems"/>
    </div>
</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('select2Style', () => ({
            search: '',
            isOpen: false,
            items: ['Option 1', 'Option 2', 'Option 3', 'Option 4', 'Option 5'],
            selectedItems: [],
            filteredItems: [],
            init() {
                this.filteredItems = this.items;
            },
            toggleDropdown() {
                this.isOpen = !this.isOpen;
            },
            closeDropdown(event) {
                // Kiểm tra nếu focus không nằm trong dropdown thì đóng
                if (!event.relatedTarget || !event.relatedTarget.closest('.dropdown-menu')) {
                    this.isOpen = false;
                }
            },
            filterItems() {
                this.filteredItems = this.items.filter(item =>
                    item.toLowerCase().includes(this.search.toLowerCase()) &&
                    !this.selectedItems.includes(item)
                );
            },
            toggleItem(item) {
                if (this.selectedItems.includes(item)) {
                    this.selectedItems = this.selectedItems.filter(i => i !== item);
                } else {
                    this.selectedItems.push(item);
                }
                this.filterItems();
            },
            removeItem(index) {
                const removedItem = this.selectedItems.splice(index, 1)[0];
                this.filteredItems.push(removedItem);
            }
        }));
    });

</script>
</body>
</html>
