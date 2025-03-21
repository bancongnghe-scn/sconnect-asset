<div class="row" x-data="table">
    <div class="col-12">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 table-responsive custom-scroll">
                    <table id="example2" class="table table-bordered dataTable dtr-inline"
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
                        <template x-for="(value,index) in dataTable" x-data="{line: 1}">
                            <tr>
                                <td class="text-center align-middle">
                                    <input type="checkbox" x-model="selectedRow[value.id]" x-bind:checked="selectedRow[value.id]">
                                </td>
                                <td x-text="from + index"></td>
                                <template x-for="(columnName, key) in columns">
                                    <td>
                                        <template x-if="key !== 'validity' && key !== 'status'">
                                            <span x-text="value[key]"></span>
                                        </template>
                                        <template x-if="key === 'validity'">
                                            <div class="text-white d-flex justify-content-center">
                                                        <span class="tw-px-4 tw-py-1 tw-rounded-full"
                                                              :class="value[key] ? 'tw-bg-[#54B435]' : 'tw-bg-slate-300'"
                                                              x-text="value[key] ? 'On' : 'Off'">
                                                        </span>
                                            </div>
                                        </template>
                                        <template x-if="key === 'status'">
                                            @include('component.status.status_contract_appendix', ['status' => 'value.status'])
                                        </template>
                                    </td>
                                </template>
                                <td class="text-center align-middle">
                                    <button class="border-0 bg-white" x-show="showAction.view ?? true" @click="$dispatch('view', { id: value.id })">
                                        <i class="bi bi-eye text-info"></i>
                                    </button>
                                    <button class="border-0 bg-white" x-show="showAction.edit ?? true" @click="$dispatch('edit', { id: value.id })">
                                        <i class="bi bi-pencil-square color-sc"></i>
                                    </button>
                                    <button class="border-0 bg-white" x-show="showAction.remove ?? true" @click="$dispatch('remove', { id: value.id })">
                                        <i class="bi bi-trash text-red"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div
            @change-page.windows.stop="changePage($event.detail.page)"
            @change-limit.window.stop="changeLimit($event.detail.limit)">
            @include('common.pagination')
        </div>
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
