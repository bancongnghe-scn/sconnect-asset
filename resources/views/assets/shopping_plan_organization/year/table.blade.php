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
                                                        <span x-text="listStatus[data[key]]" class="p-1 border rounded"
                                                              :class="{
                                                                 'tw-text-purple-600 tw-bg-purple-100': +data[key] === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER,
                                                                 'tw-text-green-600 tw-bg-green-100': +data[key] === STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED
                                                                  || +data[key] === STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
                                                                 'tw-text-green-900 tw-bg-green-100'  : +data[key] === STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED
                                                                  || +data[key] === STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL
                                                                  || +data[key] === STATUS_SHOPPING_PLAN_ORGANIZATION_APPROVAL,
                                                                 'tw-text-red-600 tw-bg-red-100'  : +data[key] === STATUS_SHOPPING_PLAN_ORGANIZATION_CANCEL
                                                                 }"
                                                        ></span>
                                                    </div>
                                                </template>
                                                <template x-if="key === 'user'">
                                                    @include('common.user_info')
                                                </template>

                                            </td>
                                        </template>
                                        <td class="text-center align-middle">
                                            <button class="border-0 bg-body" @click="window.location.href = `/shopping-plan-organization/year/view/${data.id}`">
                                                <i class="fa-solid fa-eye" style="color: #63E6BE;"></i>
                                            </button>
                                            <template x-if="new Date() >= new Date(window.formatDate(data.start_time))
                                                      && new Date() <= new Date(window.formatDate(data.end_time))">
                                                <span>
                                                    <template x-if="+data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER">
                                                        <button class="border-0 bg-body"
                                                                @click="window.location.href = `/shopping-plan-organization/year/register/${data.id}`"
                                                        >
                                                            <i class="fa-solid fa-pen-to-square" style="color: #74C0FC;"></i>
                                                        </button>
                                                    </template>
                                                    <template x-if="+data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED">
                                                        <button class="border-0 bg-body"
                                                                @click="window.location.href = `/shopping-plan-organization/year/register/${data.id}`"
                                                        >
                                                            <i class="fa-solid fa-pen" style="color: #1ec258;"></i>
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
