<div class="col-12" x-data="tableLost">
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
                        <template x-for="(columnName, key) in columns">
                            <th rowspan="1" colspan="1" 
                                :class="key === 'status' ? 'text-center' : ''" 
                                x-text="columnName">
                            </th>
                        </template>
                        <th rowspan="1" colspan="1" class="text-center">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template x-for="(data,index) in dataTable" x-data="{line: 1}">
                        <tr>
                            <td class="text-center align-middle">
                                <input type="checkbox" x-model="selectedRow[data.id]" x-bind:checked="selectedRow[data.id]" @change="count()">
                            </td>
                            <template x-for="(columnName, key) in columns">
                                <td>
                                    <template x-if="key !== 'validity' && key !== 'status' && key !== 'date' ">
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
                                    <template x-if="key === 'date'">
                                        <span x-text="formatDate(data.date)"></span>
                                    </template>
                                </td>
                            </template>
                            <td class="text-center align-middle">
                                <button class="border-0 bg-body" x-show="showAction.edit ?? true" @click="$dispatch('edit', { id: data.id })">
                                    <i class="fa-solid fa-arrow-rotate-left tw-text-green-500">&#xF117;</i>
                                </button>
                                <button class="border-0 bg-body" x-show="showAction.cancel ?? true" @click="$dispatch('cancel', { id: data.id })">
                                    <i class="fa-solid fa-xmark tw-text-red-600"></i>
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
    function tableLost() {
        return {
            checkedAll: false,

            selectedAll() {
                
                this.checkedAll = !this.checkedAll
                this.dataTable.forEach(
                    (item) => {
                        this.selectedRow[item.id] = this.checkedAll
                    }
                )
            }
        }
    }
</script>
