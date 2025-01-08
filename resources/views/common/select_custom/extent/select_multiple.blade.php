<div x-data="{
            init() {
                this.options = {{$options}};
                this.selected = {{$selected}};
                this.$watch(`{{$selected}}`, (newValue) => {
                    this.selected = newValue;
                });
                this.$watch('selected', (newValue) => {
                    this.{{$selected}} = newValue;
                });
                this.$watch(`{{$options}}`, (newValue) => {
                    this.options = newValue;
                });
            },
            open: false,
            selected: [],
            search: '',
            options: [],
            get filteredOptions() {
                if (!this.search) {
                    return this.options;
                }
                return this.options.filter(option =>
                    option.name.toLowerCase().includes(this.search.toLowerCase())
                );
            },
            toggleOption(value) {
                if (this.selected.includes(value)) {
                    this.selected = this.selected.filter(item => item !== value);
                } else {
                    this.selected.push(value);
                }
            },
            isSelected(value) {
                return this.selected.includes(value);
            },
            clearOption(value) {
                this.selected = this.selected.filter(item => item !== value);
            },
            toggleSelectAll() {
                if (this.selected.length === this.options.length) {
                    this.selected = [];
                } else {
                    this.selected = this.options.map(option => option.id);
                }
            },
            isAllSelected() {
                return this.selected.length === this.options.length;
            }
}" class="dropdown"
     @if(isset($id)) id="{{$id}}" @endif
>
    <!-- Nút chọn -->
    <button
        @click="open = !open"
        class="form-select tw-w-full tw-text-gray-500 flex flex-wrap items-center"
        type="button"
        style="text-align: start"
        @if(isset($disabled)) :disabled="{{$disabled}}" @endif
    >
        <template x-if="selected.length">
            <div class="d-flex flex-wrap gap-1">
                <template x-for="id in selected" :key="id">
                    <span class="badge bg-primary d-flex align-items-center tw-w-fit">
                        <span x-text="options.find(option => option.id === id)?.name"></span>
                        <button
                            @click.stop="clearOption(id)"
                            class="btn btn-sm text-white ms-2 p-0 d-flex align-items-center"
                        >x</button>
                    </span>
                </template>
            </div>
        </template>
        <span x-show="!selected.length">{{ $placeholder ?? 'Chọn ...' }}</span>
    </button>

    <!-- Dropdown -->
    <div
        x-show="open"
        @click.away="open = false"
        class="dropdown-container tw-w-full"
        style="border: 1px solid #ccc; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);"
    >
        <!-- Input tìm kiếm -->
        <input
            type="text"
            placeholder="Tìm kiếm..."
            x-model="search"
            class="px-2 py-2 tw-w-full"
            style="border: none; border-bottom: 1px solid #ddd; outline: none;"
        />

        <!-- Select All -->
        <div class="dropdown-item">
            <span class="d-flex">
                <input type="checkbox" @change="toggleSelectAll()" :checked="isAllSelected()" class="mr-2">
                <span>Chọn tất cả</span>
            </span>
        </div>

        <!-- Danh sách các tùy chọn -->
        <ul
            class="list-unstyled mb-0 overflow-y-auto tw-max-h-64 custom-scroll"
        >
            <template x-for="option in filteredOptions" :key="option.id">
                <li>
                    <a
                        href="#"
                        class="dropdown-item"
                        :class="{ 'selected': selected.includes(option.id) }"
                        @click.prevent="toggleOption(option.id)"
                        x-text="option.name"
                    ></a>
                </li>
            </template>
        </ul>
    </div>
</div>

<style>
    /* Dropdown container */
    .dropdown-container {
        background: #fff;
        position: absolute;
        z-index: 10;
    }

    /* Các mục */
    .dropdown-item {
        padding: 10px 15px;
        color: #000;
        font-size: 14px;
        cursor: pointer;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    /* Đang được chọn */
    .dropdown-item.selected {
        background-color: #e9ecef;
        color: #0056b3;
    }
</style>
