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
                            <th rowspan="1" colspan="1" class="col-2 text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(data,index) in dataTable" x-data="{line: 1}">
                            <tr>
                                <td class="text-center align-middle">
                                    <input type="checkbox" x-model="selectedRow[data.id]" x-bind:checked="selectedRow[data.id]">
                                </td>
                                <td x-text="from + index"></td>
                                <template x-for="(columnName, key) in columns">
                                    <td>
                                        <template x-if="key !== 'contract_value' && key !== 'validity' && key !== 'status'">
                                            <span x-text="data[key]"></span>
                                        </template>
                                        <template x-if="key === 'contract_value' && data[key] !== null">
                                            <span x-text="data[key].toLocaleString('vi-VN') + ' đ'"></span>
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
                                            @include('common.table-status')
                                        </template>
                                    </td>
                                </template>
                                <td class="text-center align-middle">
                                    <button class="border-0 bg-body" x-show="showAction.view ?? true" @click="$dispatch('view', { id: data.id })">
                                        <i class="bi bi-eye" style="color: #63E6BE;"></i>
                                    </button>
                                    <button class="border-0 bg-body" x-show="showAction.edit ?? true" @click="$dispatch('edit', { id: data.id })">
                                        <i class="fa-regular fa-pen-to-square color-sc"></i>
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
