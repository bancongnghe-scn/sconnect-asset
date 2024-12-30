<div x-data="{
            init() {
                this.options = {{$options}}
                this.selected = {{$selected}}
                this.$watch(`{{$selected}}`, (newValue, oldValue) => {
                    this.selected = newValue
                })

                this.$watch(`{{$options}}`, (newValue, oldValue) => {
                    this.options = newValue
                    this.selected = null
                })
            },
            open: false,
            selected: null,
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

            selectOption(value) {
                this.{{$selected}} = value;
                this.open = false;
                this.search = '';
            },
}" class="dropdown"
     @if(isset($id)) id="{{$id}}" @endif
     @if(isset($disabled)) :disabled="{{$disabled}}" @endif
>
    <!-- Nút chọn -->
    <button
        @click="open = true"
        class="form-select tw-w-full"
        type="button"
        x-text="selected ? options.find((item) => +item.id === +selected)?.name : '{{ $placeholder ?? 'Chọn ...' }}'"
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
            class="list-unstyled mb-0 overflow-y-auto tw-max-h-64 custom-scroll"
        >
            <template x-for="option in filteredOptions" :key="option.id">
                <li>
                    <a
                        href="#"
                        class="dropdown-item"
                        :class="{ 'selected': selected === option.id }"
                        @click.prevent="selectOption(option.id)"
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
