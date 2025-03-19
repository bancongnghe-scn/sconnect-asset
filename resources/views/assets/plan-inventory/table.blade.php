<div class="row">
    <div class="col-12">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="table-responsive custom-scroll">
                    <table id="example2" class="table table-bordered dataTable dtr-inline"
                           aria-describedby="example2_info">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <input type="checkbox" @click="selectedAll">
                                </th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: 18rem;">Tên kế hoạch kiểm kê</th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: 13rem;">Thời gian</th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: 17rem;">Đơn vị</th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: auto;">Loại tài sản</th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: 8rem;">Trạng thái</th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: 20rem;">Tiến độ</th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: 5rem;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(value, index) in dataTable" :key="index">
                                <tr>
                                    <td class="text-center align-middle">
                                        <input type="checkbox" x-model="selectedRow[value.id]" x-bind:checked="selectedRow[value.id]"
                                               :disabled="value.status !== STATUS_INVENTORY_NEW">
                                    </td>
                                    <td class="align-middle">
                                        <a x-text="value.name" class="tw-cursor-pointer tw-no-underline" :href="`/plan-inventory/detail/${value.id}`"></a>
                                    </td>
                                    <td class="text-center align-middle" x-text="formatDateVN(value.start_time) + ' - ' + formatDateVN(value.start_time)">
                                    <td class="align-middle" x-text="value.organizations.join(', ')">
                                    <td class="align-middle" x-text="value.asset_types.join(', ')">
                                    <td class="align-middle">
                                        @include('component.status.status_plan_inventory', [
                                            'status' => 'value.status'
                                        ])
                                    </td>
                                    <td class="align-middle">
                                        <div class="progress rounded">
                                            <div class="progress-bar" role="progressbar"
                                                 :style="{ width: value.process + '%' }"
                                                 :aria-valuenow="value.process"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100"
                                                 x-text="value.process + '%'"
                                            >
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <template x-if="value.status === STATUS_INVENTORIED">
                                            <span class="tw-cursor-pointer" @click="window.location.href = `/plan-inventory/detail/${value.id}`">
                                                <i class="bi bi-eye text-info"></i>
                                            </span>
                                        </template>
                                        <template x-if="[STATUS_INVENTORY_NEW, STATUS_TAKING_INVENTORY].includes(+value.status)">
                                            <span>
                                                <span class="tw-cursor-pointer mr-1"
                                                      @click="window.location.href = `/plan-inventory/update/${value.id}`">
                                                     <i class="bi bi-pencil-square color-sc"></i>
                                                </span>
                                                <span class="tw-cursor-pointer"
                                                      @click="confirmRemovePlanInventory(false, value.id)">
                                                      <i class="bi bi-trash3 text-red"></i>
                                                </span>
                                            </span>
                                        </template>
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

@include('assets.asset.common.commonSvg')


