<div x-data="{
            init() {
                this.options = {{$options}}
                this.selected = {{$selected}}
                this.open = {{$open}}
                this.$watch(`{{$selected}}`, (newValue, oldValue) => {
                    this.selected = newValue
                })

                this.$watch(`{{$options}}`, (newValue, oldValue) => {
                    this.options = newValue
                })
                this.$watch(`{{$open}}`, (newValue, oldValue) => {
                    this.open = true
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
                    option.name.toLowerCase().includes(this.search.toLowerCase()) || option.code.toLowerCase().includes(this.search.toLowerCase())
                );
            },

            selectOption(value) {
                this.{{$selected}} = value;
                this.open = false;
                this.search = '';
            },
}" class="dropdown">
    <div
        x-show="open"
        @click.away="open = false"
        style="
             box-shadow: 0 6px 16px 0 rgba(83, 92, 105, .15);
             border-radius: 30px;
             background-color: #fcfcfd;
             width: 380px;
             padding: 10px;"
    >
        <input x-model="search" type="text" class="form-control tw-rounded-full" placeholder="Tìm kiếm ...">
        <hr>
        <ul class="list-unstyled mb-0 overflow-y-auto tw-max-h-64 custom-scroll">
            <template x-for="option in filteredOptions" :key="option.id">
                <li>
                    <a
                        href="#"
                        class="dropdown-item"
                        :class="{ 'selected': selected === option.id }"
                        @click.prevent="selectOption(option.id)"
                    >
                        <div class="d-flex align-items-center">
                            <img x-bind:src="option && option.avatar ?
                                (option.avatar.includes('/uploads/') ? 'https://office.sconnect.com.vn' + option.avatar : option.avatar)
                                : 'https://office.sconnect.com.vn/images/avatar-default.png'"
                                 style="width: 35px; height: 35px; object-fit: cover; border-radius: 100px;"
                            >
                            <div class="d-flex flex-column align-items-start justify-content-center"
                                 style="margin-left: 10px">
                                <span x-text="option ? (option.code + '-' + option.name) : ''" class="text-sm"></span>
                                <span x-text="option ? option.job_title : ''" style="color: #706f6f;"></span>
                            </div>
                        </div>
                    </a>
                </li>
            </template>
        </ul>
    </div>
</div>
