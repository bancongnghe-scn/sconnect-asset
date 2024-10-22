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
                                <template x-for="(data,index) in dataTable">
                                    <tr>
                                        <td class="text-center align-middle">
                                            <input type="checkbox" x-model="selectedRow[data.id]">
                                        </td>
                                        <td x-text="from + index"></td>
                                        <template x-for="(columnName, key) in columns">
                                            <td>
                                                <template x-if="key !== 'register_time' && key !== 'status'">
                                                    <span x-text="data[key]"></span>
                                                </template>
                                                <template x-if="key === 'register_time'">
                                                    <span :class="!data.status_register ? 'tw-text-red-500': ''" x-text="data.start_time + ' - ' + data.end_time"></span>
                                                </template>
                                                <template x-if="key === 'status'">
                                                    <div class="d-flex justify-content-center">
                                                        <span x-text="listStatus[data[key]]" class="p-1 border rounded"
                                                              :class="{
                                                                 'tw-text-sky-600 tw-bg-sky-100': +data[key] === 1,
                                                                 'tw-text-purple-600 tw-bg-purple-100': +data[key] === 2,
                                                                 'tw-text-green-600 tw-bg-green-100': +data[key] === 3,
                                                                 'tw-text-green-600 tw-bg-green-100': +data[key] === 4,
                                                                 'tw-text-red-600 tw-bg-red-100'  : +data[key] === 5
                                                                 }"
                                                        ></span>
                                                    </div>
                                                </template>
                                            </td>
                                        </template>
                                        <td class="text-center align-middle">
                                            <button class="border-0 bg-body" x-show="showAction.view ?? true" @click="$dispatch('view', { id: data.id })">
                                                <i class="fa-solid fa-eye" style="color: #63E6BE;"></i>
                                            </button>
                                            <button class="border-0 bg-body" x-show="showAction.edit ?? true" @click="$dispatch('edit', { id: data.id })">
                                                <i class="fa-solid fa-pen" style="color: #1ec258;"></i>
                                            </button>
                                            <button class="border-0 bg-body" x-show="showAction.remove ?? true" @click="$dispatch('remove', { id: data.id })">
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
                this.dataTable.forEach((item) => this.selectedRow[item.id] = this.checkedAll)
            }
        }
    }
</script>
