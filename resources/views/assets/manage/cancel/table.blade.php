<div class="col-12">
    <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
        <div class="row">
            <div class="col-sm-12">
                <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
                        aria-describedby="example2_info">
                    <thead>
                    <tr :class="'position-sticky tw-top-0'">
                        <template x-for="(columnName, key) in columns">
                            <th rowspan="1" colspan="1" x-text="columnName" :class="key === 'status' ? 'text-center' : ''"></th>
                        </template>
                    </tr>
                    </thead>
                    <tbody>
                    <template x-for="(data,index) in dataTable" x-data="{line: 1}">
                        <tr>
                            <template x-for="(columnName, key) in columns">
                                <td :class="'align-content-center'">
                                    <template x-if="key !== 'validity' && key !== 'status' && key !== 'created_at' && key !== 'user_name' ">
                                        <span x-text="data[key]"></span>
                                    </template>
                                    <template x-if="key === 'validity'">
                                        <div class="text-white d-flex justify-content-center">
                                            <span class="tw-px-4 tw-py-1 tw-rounded-full"
                                                    :class="data[key] ? 'tw-bg-[#54B435]' : 'tw-bg-slate-300'"
                                                    x-text="data[key] ? 'On' : 'Off'">
                                            </span>
                                        </div>
                                    </template>
                                    <template x-if="key === 'status'">
                                        @include('common.table-status-asset')
                                    </template>
                                    <template x-if="key === 'created_at'">
                                        <span x-text="formatDate(data.date)"></span>
                                    </template>
                                    <template x-if="key === 'user_name'">
                                        <div class="d-flex">
                                            <img x-show="data.user_name" x-bind:src="data.avatar 
                                                    ? (data.avatar.includes('/uploads/') 
                                                        ? 'https://office.sconnect.com.vn' + data.avatar 
                                                        : data.avatar) 
                                                    : 'https://office.sconnect.com.vn/images/avatar-default.png'" 
                                                    alt="" 
                                                    style="width: 55px; height: 55px; object-fit: cover; border-radius: 100px;">
                                            <div style="display: flex; flex-direction: column; align-items: flex-start; justify-content: center; margin-left: 10px;">                                   
                                                <span x-text="data.user_name ?? ''" style="font-weight: 600; font-size: 16px;"></span>
                                                <span x-text="data.code ? 'Mã nhân sự:' + data.code : ''" style="color: #706f6f;"></span>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                            </template>
                            
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('common.pagination')
</div>

<script>
    function table() {
        return {
            checkedAll: false,

            selectedAll() {
                this.checkedAll = !this.checkedAll
                this.dataTable.forEach((item) => this.selectedRow[item.id] = this.checkedAll)
            }
        }
    }
</script>
