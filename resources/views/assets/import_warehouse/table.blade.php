<div class="row">
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
                                        <td x-text="from + index"></td>
                                        <template x-for="(columnName, key) in columns">
                                            <td>
                                                <template x-if="key === 'status'">
                                                    @include('component.status_import_warehouse', ['status' => 'value.status'])
                                                </template>
                                                <template x-if="key === 'created_at'">
                                                    <span x-text="formatDateVN(value.created_at)"></span>
                                                </template>
                                                <template x-if="key !== 'status' && key !== 'created_at'">
                                                    <span x-text="value[key]"></span>
                                                </template>
                                            </td>
                                        </template>
                                        <td class="text-center align-middle">
                                            <button
                                                x-show="+value.status === STATUS_IMPORT_WAREHOUSE_NOT_COMPLETE"
                                                class="border-0 bg-body" @click="handleShowModalUI('update', value.id)">
                                                <i class="fa-solid fa-pen" style="color: #1ec258;"></i>
                                            </button>
                                            <button
                                                x-show="+value.status === STATUS_IMPORT_WAREHOUSE_NOT_COMPLETE"
                                                class="border-0 bg-body" @click="confirmRemove(value.id)">
                                                <i class="fa-regular fa-trash-can" style="color: #cd1326;"></i>
                                            </button>
                                            <button x-show="+value.status === STATUS_IMPORT_WAREHOUSE_COMPLETE"
                                                    class="border-0 bg-body"
                                                    @click="printImportWarehouse(value.id)"
                                            >
                                                <i class="fa-solid fa-print"></i>
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

