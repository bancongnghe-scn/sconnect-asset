<div class="row" x-data="table">
    <div class="col-12">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 table-responsive custom-scroll">
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
                            <th rowspan="1" colspan="1" class="col-2 text-center tw-w-28">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(value,index) in dataTable" :key="index">
                            <tr x-data="{
                                        isStatusActive: [ORDER_STATUS_NEW, ORDER_STATUS_TRANSIT].includes(+value.status)
                                    }" x-effect="isStatusActive = [ORDER_STATUS_NEW, ORDER_STATUS_TRANSIT].includes(+value.status)">
                                <td class="text-center align-middle" >
                                    <input x-show="isStatusActive" type="checkbox" x-model="selectedRow[value.id]" x-bind:checked="selectedRow[value.id]">
                                    <input x-show="!isStatusActive" type="checkbox" disabled>
                                </td>
                                <td x-text="from + index"></td>
                                <template x-for="(columnName, key) in columns">
                                    <td>
                                        <template x-if="key === 'created_at'">
                                            <span x-text="formatDateVN(value.created_at)"></span>
                                        </template>
                                        <template x-if="key === 'delivery_date'">
                                            <span x-text="formatDateVN(value.delivery_date)"></span>
                                        </template>
                                        <template x-if="key === 'purchasing_manager'" x-data="{data: value}">
                                            @include('common.user_info')
                                        </template>
                                        <template x-if="key === 'status'">
                                            @include('component.status_order', ['status' => 'value.status'])
                                        </template>
                                        <template x-if="key === 'name'">
                                            <a x-text="value[key]" class="tw-cursor-pointer"
                                               @click="handleShowModalUI('view', value.id)"
                                            ></a>
                                        </template>
                                        <template x-if="key === 'supplier_name' || key === 'code'">
                                            <span x-text="value[key]"></span>
                                        </template>
                                    </td>
                                </template>
                                <td class="text-center align-middle">
                                    <button class="border-0 bg-body" x-show="isStatusActive" @click="$dispatch('edit', { id: value.id })">
                                        <i class="fa-regular fa-pen-to-square color-sc"></i>
                                    </button>
                                    <button class="border-0 bg-body" x-show="isStatusActive" @click="$dispatch('remove', { id: value.id })">
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
