<div class="row">
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
                            <th rowspan="1" colspan="1" class="tw-w-80 text-center">Đơn vị</th>
                            <th rowspan="1" colspan="1" class="tw-w-80 text-center">Chức danh</th>
                            <th rowspan="1" colspan="1" class="text-center">Loại tài sản</th>
                            <th rowspan="1" colspan="1" class="text-center">Hạng</th>
                            <th rowspan="1" colspan="1" class="text-center">Giá</th>
                            <th rowspan="1" colspan="1" class="tw-w-96 text-center">Ghi chú</th>
                            <th rowspan="1" colspan="1" class="text-center tw-w-20">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                            <template x-for="(value, index) in dataTable" :key="index">
                                <template x-for="(config, key) in value.configs" :key="key">
                                    <tr x-data="{ get rowspan(){ return value.configs.length } }">
                                        <td x-show="key === 0" :rowspan="key === 0 ? rowspan : 1" class="text-center align-middle">
                                            <input type="checkbox" x-model="selectedRow[value.organization_id + '_' + value.position_id]">
                                        </td>
                                        <td x-show="key === 0" :rowspan="key === 0 ? rowspan : 1" x-text="value.organization_name"
                                            class="text-center align-middle"></td>
                                        <td x-show="key === 0" :rowspan="key === 0 ? rowspan : 1" x-text="value.position_name"
                                            class="text-center align-middle"></td>
                                        <td class="text-center">
                                            <span class="border rounded p-1 tw-text-xs tw-shadow-md"
                                                  x-text="config?.asset_type_name || ''"
                                            ></span>
                                        </td>
                                        <td class="text-center">
                                            <span x-text="LEVEL_ALLOCATION_RATE[config.level]"
                                                  class="border rounded p-1 tw-text-xs tw-shadow-md">
                                            </span>
                                        </td>
                                        <td x-text="formatCurrencyVND(+config.price)" class="text-center"></td>
                                        <td x-text="config?.description || ''"></td>
                                        <td x-show="key === 0" :rowspan="key === 0 ? rowspan : 1" class="text-center align-middle">
                                            <button class="border-0 bg-white" @click="handleShowModal('update', value.organization_id, value.position_id)">
                                                <i class="bi bi-pencil-square color-sc"></i>
                                            </button>
                                            <button class="border-0 bg-white" @click="handleShowModalConfirmRemove(value.organization_id, value.position_id, false)">
                                                <i class="bi bi-trash text-red"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @include('common.pagination')
    </div>
</div>


