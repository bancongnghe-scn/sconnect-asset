<div class="col-12" x-data="tablePlanLiquidation">
    <div id="" class="dataTables_wrapper dt-bootstrap4">
        <div class="row">
            <div class="col-sm-12">
                <table id="" class="table table-bordered table-hover dataTable dtr-inline"
                        aria-describedby="example2_info">
                    <thead>
                    <tr :class="'position-sticky tw-top-0'">
                        <th class="text-center">
                            <input type="checkbox" id="selectedAllLiquidation" @click="selectedAllLiquidation">
                        </th>
                        <template x-for="(columnName, key) in columns">
                            <th rowspan="1" colspan="1" x-text="columnName" :class="key === 'status' ? 'text-center' : ''"></th>
                        </template>
                        <th rowspan="1" colspan="1" class="text-center">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template x-for="(data,index) in dataTable">
                        <tr>
                            <td class="text-center align-middle">
                                <input type="checkbox" x-model="selectedRow[data.id]" x-bind:checked="selectedRow[data.id]">
                            </td>
                            <template x-for="(columnName, key) in columns" x-data="{line: 1}">
                                <td>
                                    <template x-if="key !== 'validity' && key !== 'status' && key !== 'date'">
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
                                        <div class="d-flex justify-content-center">
                                            <span x-text="data[key]" class="rounded tw-p-1" style="border: 1px solid;font-size:11px;"
                                                :class="{
                                                    'tw-text-slate-500 tw-bg-slate-100':  data[key] === 'Mới tạo',
                                                    'tw-text-yellow-500 tw-bg-yellow-100':  data[key] === 'Chờ duyệt',
                                                    'tw-text-green-500 tw-bg-green-100':    data[key] === 'Đã duyệt',
                                                    'tw-text-red-500 tw-bg-red-100':    data[key] === 'Từ chối',
                                                }"
                                            ></span>
                                        </div>
                                    </template>
                                    <template x-if="key === 'date'">
                                        <span x-text="formatDate(data.date)"></span>
                                    </template>
                                </td>
                            </template>
                            <td class="text-center align-middle" x-show="data.status === 'Mới tạo'">
                                <button class="border-0 bg-body" x-show="showAction.edit ?? true" @click="$dispatch('edit', { id: data.id })">
                                    <i class="fa-solid fa-pencil">&#xF117;</i>
                                </button>
                                <button class="border-0 bg-body" @click="removeOnePlan(data.id)"
                                >
                                    <i class="fa-solid fa-trash" style="color: #db4554;"></i>
                                </button>
                            </td>
                            <td class="text-center align-middle" x-show="data.status !== 'Mới tạo'">
                                <button class="border-0 bg-body" x-show="showAction.get ?? true" @click="$dispatch('get', { id: data.id })">
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
    function tablePlanLiquidation() {
        return {
            checkedAll: false,

            selectedAllLiquidation() {
                
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
