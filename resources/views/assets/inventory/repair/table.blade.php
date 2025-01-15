<div class="col-12" x-data="tableRepair">
    <div id="" class="dataTables_wrapper dt-bootstrap4">
        <div class="row">
            <div class="col-sm-12">
                <table id="" class="table table-bordered table-hover dataTable dtr-inline"
                        aria-describedby="example2_info">
                    <thead>
                    <tr>
                        <th class="text-center">
                            <input type="checkbox" id="selectedAll" @click="selectedAll" @change="count()">
                        </th>
                        <th rowspan="1" colspan="1">STT</th>
                        <template x-for="(columnName, key) in columns">
                            <th rowspan="1" colspan="1" x-text="columnName"></th>
                        </template>
                        <th rowspan="1" colspan="1" class="text-center">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template x-for="(data,index) in dataTable" x-data="{line: 1}">
                        <tr>
                            <td class="text-center align-middle">
                                <input type="checkbox" 
                                    x-model="selectedRow[data.id]" 
                                    x-bind:checked="selectedRow[data.id]" 
                                    @change="count()"
                                    x-show="data.status_repair !== 'Hoàn thành sửa chữa'">
                                <span x-show="data.status_repair === 'Hoàn thành sửa chữa'"></span>

                            </td>

                            <td x-text="from + index"></td>
                            <template x-for="(columnName, key) in columns">
                                <td>
                                    <template x-if="key !== 'validity' && key !== 'status_repair' && key !== 'date' && key !== 'date_repair'">
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
                                    <template x-if="key === 'status_repair'">
                                        @include('common.table-status-asset')
                                    </template>
                                    <template x-if="key === 'date'">
                                        <span x-text="data.date ? formatDate(data.date) : ''"></span>
                                    </template>
                                    <template x-if="key === 'date_repair'">
                                        <span x-text="data.date_repair ? formatDate(data.date_repair) : ''"></span>
                                    </template>
                                </td>
                            </template>
                            <td class="text-center align-middle" x-show="data.status_repair === 'Đang sửa chữa'">
                                <button class="border-0 bg-body" @click="clickRepaired(data.id)">
                                    <i class="fa-solid fa-check tw-text-green-600">&#xF117;</i>
                                </button>
                            </td>
                            <td class="text-center align-middle" x-show="data.status_repair === 'Hoàn thành sửa chữa'">
                                <button class="border-0 bg-body" @click="clickShowRepaired(data.id)">
                                    <i class="fa-solid fa-eye">&#xF117;</i>
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

<script>
    function tableRepair() {
        return {
            checkedAll: false,

            selectedAll() {
                
                this.checkedAll = !this.checkedAll
                this.dataTable.forEach((item) => {
                    if (item.status_repair !== 'Hoàn thành sửa chữa') {
                        this.selectedRow[item.id] = this.checkedAll
                    }
                })
            }
        }
    }
</script>
