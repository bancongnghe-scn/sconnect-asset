<div x-data="{
            init() {
                this.$watch(`{{$selected}}`, (newValue, oldValue) => {
                    this.selected = newValue
                })

                this.options = {{$options}}
            },
            open: false,
            selected: null,
            search: '',
            options: [],
            get filteredOptions() {
                if (!this.search) {
                    return this.options;
                }
                let data = {}; // Khởi tạo data là object
                Object.entries(this.options).forEach(([key, value]) => {
                    if (value.toLowerCase().includes(this.search.toLowerCase())) {
                        data[key] = value; // Gán key-value vào object data
                    }
                });
                return data;
            },

            selectOption(value) {
                this.{{$selected}} = value;
                this.open = false;
                this.search = '';
            },
}"
     class="dropdown"
     @if(isset($id)) id="{{$id}}" @endif
>
    <!-- Nút chọn -->
    <button
        @click="open = !open"
        class="form-select tw-w-full"
        type="button"
        x-text="selected ? options[selected] : '{{ $placeholder ?? 'Chọn ...' }}'"
        style="text-align: start"
        @if(isset($disabled)) :disabled="{{$disabled}}" @endif
    >
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

        <!-- Danh sách các tùy chọn -->
        <ul
            class="list-unstyled mb-0 overflow-y-auto"
        >
            <template x-for="(option, key) in filteredOptions" :key="key">
                <li>
                    <a
                        href="#"
                        class="dropdown-item"
                        :class="{ 'selected': selected === key }"
                        @click.prevent="selectOption(key)"
                        x-text="option"
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
