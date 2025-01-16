<table id="example2" class="table table-bordered dataTable dtr-inline"
       aria-describedby="example2_info">
    <thead class="position-sticky z-1" style="top: -1px">
    <tr>
        <th rowspan="2" class="text-center" x-show="+data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL">
            <input type="checkbox" @click="selectedAll">
        </th>
        <th rowspan="2" class="text-center">Đơn vị</th>
        <th rowspan="2" class="text-center">Loại tài sản</th>
        <th colspan="3" class="text-center">Số lượng đăng ký theo tháng</th>
        <th rowspan="2" class="text-center">Tổng Số lượng</th>
        <th rowspan="2" class="text-center">Tổng Thành tiền</th>
        <th rowspan="2" class="text-center tw-w-28">Thao tác</th>
    </tr>
    <tr>
        <template x-for="number in Array.from({ length: 3 }, (_, i) => i + 1)" :key="number">
            <th x-text="`T` + number" class="text-center"></th>
        </template>
    </tr>
    </thead>
    <tbody>
    <template x-for="(organization, index) in register.organizations" :key="index">
        <template x-for="(assetRegister, stt) in organization.asset_register" :key="index + '_' + stt">
            <tr>
                <td :rowspan="stt === 0 ? organization.asset_register.length : 1"
                    class="text-center align-middle"
                    x-show="stt === 0 && +data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL"
                >
                    <input type="checkbox" x-model="selectedRow[organization.id]" x-bind:checked="selectedRow[organization.id]">
                </td>
                <td x-show="stt === 0" :rowspan="stt === 0 ? organization.asset_register.length : 1" class="tw-font-bold">
                    <span x-text="organization.name"></span>
                    @include('component.shopping_plan_organization.status_shopping_plan_organization', [
                        'status' => 'organization.status',
                        'tooltip' => 'organization.note'
                    ])
                </td>
                <td x-text="assetRegister.asset_type_name ?? '-'"></td>
                <template x-for="number in Array.from({ length: 3 }, (_, i) => i + 1)" :key="index + '_' + stt + '_' + number">
                    <td x-text="assetRegister.register?.[number - 1] ?? '-'" class="text-center"></td>
                </template>
                <td x-text="assetRegister.total_register ?? '-'" class="text-center"></td>
                <td x-text="window.formatCurrencyVND(organization.total_price)" x-show="stt === 0" :rowspan="stt === 0 ? organization.asset_register.length : 1" class="text-center"></td>
                <td x-show="stt === 0" :rowspan="stt === 0 ? organization.asset_register.length : 1" class="text-center">
                    {{-- button view --}}
                    <button @click="window.location.href = `/shopping-plan-organization/quarter/view/${organization.id}`" class="border-0 bg-body">
                        <i class="bi bi-eye" style="color: #63E6BE;"></i>
                    </button>

                    {{-- button duyet --}}
                    <template x-if="+data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL && action === 'update'">
                        @can('shopping_plan_company.accounting_approval')
                            <span>
                                    <template x-if="
                                        [
                                            STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
                                            STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED,
                                            STATUS_SHOPPING_PLAN_ORGANIZATION_CANCEL
                                        ].includes(+organization.status)"
                                    >
                                        <button class="border-0 bg-body"
                                                @click="accountApprovalShoppingPlanOrganization(organization.id, ORGANIZATION_TYPE_APPROVAL)"
                                        >
                                            <i class="fa-solid fa-thumbs-up" style="color: #125fe2;"></i>
                                        </button>
                                    </template>
                                    <template x-if="
                                        [
                                            STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
                                            STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED,
                                            STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL
                                        ].includes(+organization.status)"
                                    >
                                        <button class="border-0 bg-body"
                                                @click="showModalNoteDisapproval(organization.id)"
                                        >
                                            <i class="fa-solid fa-thumbs-down" style="color: #727479;"></i>
                                        </button>
                                    </template>
                                </span>
                        @endcan
                    </template>
                </td>
            </tr>
        </template>
    </template>
    </tbody>
</table>
