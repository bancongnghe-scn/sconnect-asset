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
                                <template x-for="(data,index) in dataTable">
                                    <tr>
                                        <td x-text="from + index"></td>
                                        <template x-for="(columnName, key) in columns">
                                            <td>
                                                <template x-if="key !== 'register_time' && key !== 'status' && key !== 'user'">
                                                    <span x-text="data[key]"></span>
                                                </template>
                                                <template x-if="key === 'register_time'">
                                                    <span :class="!data.status_register ? 'tw-text-red-500': ''" x-text="data.start_time + ' - ' + data.end_time"></span>
                                                </template>
                                                <template x-if="key === 'status'">
                                                    <div class="d-flex justify-content-center">
                                                        @include('component.shopping_plan_organization.status_shopping_plan_organization', ['status' => 'data.status'])
                                                    </div>
                                                </template>
                                                <template x-if="key === 'user'">
                                                    @include('common.user_info')
                                                </template>

                                            </td>
                                        </template>
                                        <td class="text-center align-middle">
                                            <button class="border-0 bg-body" @click="window.location.href = `/shopping-plan-organization/week/view/${data.id}`">
                                                <i class="fa-solid fa-eye" style="color: #63E6BE;"></i>
                                            </button>
                                            <template x-if="new Date() >= new Date(window.formatDate(data.start_time))
                                                      && new Date() <= new Date(window.formatDate(data.end_time))">
                                                <span>
                                                    <template x-if="+data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER">
                                                        <button class="border-0 bg-body"
                                                                @click="window.location.href = `/shopping-plan-organization/week/register/${data.id}`"
                                                        >
                                                            <i class="fa-solid fa-pen-to-square" style="color: #74C0FC;"></i>
                                                        </button>
                                                    </template>
                                                    <template x-if="+data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED">
                                                        <button class="border-0 bg-body"
                                                                @click="window.location.href = `/shopping-plan-organization/week/register/${data.id}`"
                                                        >
                                                            <i class="fa-regular fa-pen-to-square color-sc"></i>
                                                        </button>
                                                    </template>
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
            </div>
        </div>
        @include('common.pagination')
    </div>
</div>
