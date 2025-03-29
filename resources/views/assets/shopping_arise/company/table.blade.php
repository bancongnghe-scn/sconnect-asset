<div class="row">
    <div class="col-12">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="table-responsive custom-scroll">
                    <table id="example2" class="table table-bordered dataTable dtr-inline"
                           aria-describedby="example2_info">
                        <thead>
                            <tr>
                                <th rowspan="1" colspan="1" class="text-center">Nội dung</th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: 19rem;">Người đề xuất</th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: 14rem;">Đơn vị</th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: 10rem;">Ngày đề xuất</th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: 10rem;">Trạng thái</th>
                                <th rowspan="1" colspan="1" class="text-center" style="width: 7rem;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(value, index) in dataTable" :key="index">
                                <tr>
                                    <td class="align-middle">
                                        <a x-text="value.name" class="tw-cursor-pointer tw-no-underline" :href="`/shopping-arise/organization/detail/${value.id}`"></a>
                                    </td>
                                    <td class="align-middle">
                                        @include('common.user.user_info', ['user' => 'value.user'])
                                    </td>
                                    <td class="align-middle" x-text="value.organization_name">
                                    <td class="align-middle text-center" x-text="formatDateVN(value.created_at)">
                                    <td class="align-middle">
                                        @include('component.status.status_shopping_arise', [
                                            'status' => 'value.status'
                                        ])
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="tw-cursor-pointer" @click="window.location.href = `/shopping-arise/company/detail/${value.id}`">
                                               <i class="bi bi-eye text-info"></i>
                                        </span>
                                        <template x-if="+value.status === STATUS_SHOPPING_ARISE_NEW">
                                            <span class="tw-cursor-pointer mr-1" @click="window.location.href = `/shopping-arise/company/update/${value.id}`">
                                                <i class="bi bi-pencil-square color-sc"></i>
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
        <div
            @change-page.windows.stop="changePage($event.detail.page)"
            @change-limit.window.stop="changeLimit($event.detail.limit)">
            @include('common.pagination')
        </div>
    </div>
</div>
