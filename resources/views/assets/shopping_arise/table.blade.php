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
                                <th rowspan="1" colspan="1" class="text-center" style="width: 18rem;">Nội dung</th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: 13rem;">Người đề xuất</th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: 17rem;">Đơn vị</th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: auto;">Ngày đề xuất</th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: 8rem;">Trạng thái</th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: 5rem;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(value, index) in dataTable" :key="index">
                                <tr>
                                    <td class="text-center align-middle">
                                        <input type="checkbox" x-model="selectedRow[value.id]" x-bind:checked="selectedRow[value.id]"
                                               :disabled="value.status !== STATUS_SHOPPING_ARISE_NEW">
                                    </td>
                                    <td class="align-middle">
                                        <a x-text="value.name" class="tw-cursor-pointer tw-no-underline" :href="`/plan-inventory/detail/${value.id}`"></a>
                                    </td>
                                    <td class="align-middle">
                                        @include('common.user.user_info', ['user' => 'value.user'])
                                    </td>
                                    <td class="align-middle" x-text="value.organization_name">
                                    <td class="align-middle" x-text="formatDateVN(value.created_at)">
                                    <td class="align-middle">
                                        @include('component.status.status_shopping_arise', [
                                            'status' => 'value.status'
                                        ])
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="tw-cursor-pointer" @click="window.location.href = `/shopping-arise/detail/${value.id}`">
                                           <i class="bi bi-eye text-info"></i>
                                        </span>
                                        <span class="tw-cursor-pointer mr-1" @click="window.location.href = `/shopping-arise/update/${value.id}`">
                                            <i class="bi bi-pencil-square color-sc"></i>
                                        </span>
                                        <span class="tw-cursor-pointer" @click="confirmRemove('single', value.id)">
                                            <i class="bi bi-trash3 text-red"></i>
                                        </span>
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
