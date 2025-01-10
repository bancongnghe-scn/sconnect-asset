<div class="row">
    <div class="col-12">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 table-responsive custom-scroll">
                    <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
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
                            <template x-for="(value,index) in dataTable">
                                <tr>
                                    <td x-text="from + index"></td>
                                    <template x-for="(columnName, key) in columns">
                                        <td>
                                            <template x-if="key !== 'register_time' && key !== 'status' && key !== 'user'">
                                                <span x-text="value[key]"></span>
                                            </template>
                                            <template x-if="key === 'register_time'">
                                                <span :class="!value.status_register ? 'tw-text-red-500': ''" x-text="value.start_time + ' - ' + value.end_time"></span>
                                            </template>
                                            <template x-if="key === 'status'">
                                                <div class="d-flex justify-content-center">
                                                    @include('component.shopping_plan_organization.status_shopping_plan_organization', ['status' => 'value.status'])
                                                </div>
                                            </template>
                                            <template x-if="key === 'user'">
                                                <span x-data="{data: value}">
                                                    @include('common.user_info')
                                                </span>
                                            </template>
                                        </td>
                                    </template>
                                    <td class="text-center align-middle">
                                        <button class="border-0 bg-body" @click="handleShowModal(value.id, 'view')">
                                            <i class="bi bi-eye" style="color: #63E6BE;"></i>
                                        </button>
                                        <template x-if="
                                                          (new Date() >= new Date(window.formatDate(value.start_time))
                                                          && new Date() <= new Date(window.formatDate(value.end_time))) &&
                                                           ( +value.status === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER
                                                            || +value.status === STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED)
                                                            "
                                        >
                                            <button class="border-0 bg-body"
                                                    @click="handleShowModal(value.id, 'register')"
                                            >
                                                <i class="fa-regular fa-pen-to-square color-sc"></i>
                                            </button>
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
