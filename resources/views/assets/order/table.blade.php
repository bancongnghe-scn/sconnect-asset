<div class="row" x-data="table">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
                                   aria-describedby="example2_info">
                                <thead>
                                <tr>
                                    <th class="text-center">
                                        <input type="checkbox" @click="selectedAll">
                                    </th>
                                    <th rowspan="1" colspan="1">STT</th>
                                    <template x-for="(columnName, key) in columns">
                                        <th rowspan="1" colspan="1" x-text="columnName"></th>
                                    </template>
                                    <th rowspan="1" colspan="1" class="col-2 text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="(data,index) in dataTable" :key="index">
                                    <tr x-data="{
                                        isStatusActive: [ORDER_STATUS_NEW, ORDER_STATUS_TRANSIT].includes(+data.status)
                                    }">
                                        <td class="text-center align-middle" >
                                            <input x-show="isStatusActive" type="checkbox" x-model="selectedRow[data.id]" x-bind:checked="selectedRow[data.id]">
                                            <input x-show="!isStatusActive" type="checkbox" disabled>
                                        </td>
                                        <td x-text="from + index"></td>
                                        <template x-for="(columnName, key) in columns">
                                            <td>
                                                <template x-if="key === 'created_at'">
                                                    <span x-text="formatDateVN(data.created_at)"></span>
                                                </template>
                                                <template x-if="key === 'delivery_date'">
                                                    <span x-text="formatDateVN(data.delivery_date)"></span>
                                                </template>
                                                <template x-if="key === 'purchasing_manager'">
                                                    @include('common.user_info')
                                                </template>
                                                <template x-if="key === 'status'">
                                                    @include('component.status_order', ['status' => 'data.status'])
                                                </template>
                                                <template x-if="key === 'supplier_name' || key === 'name' || key === 'code'">
                                                    <span x-text="data[key]"></span>
                                                </template>
                                            </td>
                                        </template>
                                        <td class="text-center align-middle">
                                            <button class="border-0 bg-body" @click="$dispatch('view', { id: data.id })">
                                                <i class="fa-solid fa-eye" style="color: #63E6BE;"></i>
                                            </button>
                                            <button class="border-0 bg-body" x-show="isStatusActive" @click="$dispatch('edit', { id: data.id })">
                                                <i class="fa-solid fa-pen" style="color: #1ec258;"></i>
                                            </button>
                                            <button class="border-0 bg-body" x-show="isStatusActive" @click="$dispatch('remove', { id: data.id })">
                                                <i class="fa-regular fa-trash-can" style="color: #cd1326;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('common.pagination')
    </div>
</div>

<script>
    function table() {
        return {
            checkedAll: false,

            selectedAll() {
                this.checkedAll = !this.checkedAll
                this.dataTable.forEach((item) => {
                    if(+item.status === ORDER_STATUS_NEW || +item.status === ORDER_STATUS_TRANSIT) {
                        this.selectedRow[item.id] = this.checkedAll
                    }
                })
            }
        }
    }
</script>
