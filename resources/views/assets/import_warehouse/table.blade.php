<div class="row">
    <div class="col-12">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 table-responsive custom-scroll">
                    <table id="example2" class="table table-bordered dataTable dtr-inline"
                           aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 4rem;">STT</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 8rem;">Mã</th>
                            <th rowspan="1" colspan="1" class="text-center">Tên phiếu</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 8rem;">Thời gian nhập</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 16rem;">Người nhập</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 9rem;">Trạng thái</th>
                            <th rowspan="1" colspan="1" style="width: 5rem;">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(value,index) in dataTable" x-data="{line: 1}">
                            <tr>
                                <td x-text="from + index" class="text-center align-middle"></td>
                                <td class="text-center align-middle">
                                    <a class="tw-no-underline" x-text="value.code"
                                       :href="`/import-warehouse/detail/${value.id}`"
                                    ></a>
                                </td>
                                <td x-text="value.name" class="align-middle"></td>
                                <td x-text="formatDateVN(value.created_at)" class="text-center align-middle"></td>
                                <td class="text-center align-middle">
                                    @include('common.user.user_info', ['user' => 'value.created_by'])
                                </td>
                                <td class="text-center align-middle">
                                    @include('component.status.status_import_warehouse', ['status' => 'value.status'])
                                </td>
                                <td class="align-middle">
                                    <button
                                        x-show="+value.status === STATUS_IMPORT_WAREHOUSE_NOT_COMPLETE"
                                        class="border-0 bg-white"
                                        @click="handleShowModalUI('update', value.id)">
                                        <i class="bi bi-pencil-square color-sc"></i>
                                    </button>
                                    <button
                                        x-show="+value.status === STATUS_IMPORT_WAREHOUSE_NOT_COMPLETE"
                                        class="border-0 bg-white" @click="confirmRemove(value.id)">
                                        <i class="bi bi-trash text-red"></i>
                                    </button>
                                    <a :href="`/api/import-warehouse/export?ids[]=${value.id}`" download>
                                        <button x-show="+value.status === STATUS_IMPORT_WAREHOUSE_COMPLETE"
                                                class="border-0 bg-white">
                                            <i class="fa-solid fa-print"></i>
                                        </button>
                                    </a>
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

