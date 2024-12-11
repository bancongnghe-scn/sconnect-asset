<table id="example2" class="table table-bordered dataTable dtr-inline"
       aria-describedby="example2_info">
    <thead class="position-sticky z-1" style="top: -1px">
    <tr>
        <th class="text-center">
            <input type="checkbox" @click="selectedAll">
        </th>
        <th class="text-center">Đơn vị</th>
        <th class="text-center">Loại tài sản</th>
        <th class="text-center">Chức danh</th>
        <th class="text-center">Thời gian cần</th>
        <th class="text-center">SL</th>
        <th class="text-center">SL duyệt</th>
        <th class="text-center">Đơn giá</th>
        <th class="text-center">Thuế</th>
        <th class="text-center">Tổng</th>
        <th class="text-center">NCC</th>
        <th class="text-center">Ghi chú</th>
        <th class="text-center">Mô tả</th>
        <th class="text-center tw-w-28">Thao tác</th>
    </tr>
    </thead>
    <tbody>
    <template x-for="(organization, index) in register.organizations" :key="index">
        <template x-for="(assetRegister, stt) in organization.asset_register" :key="index + '_' + stt">
            <template x-if="assetRegister.action === SHOPPING_ASSET_ACTION_NEW">
                <tr>
                    <td class="text-center align-middle">
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
                    <td x-text="assetRegister.job_name ?? '-'"></td>
                    <td x-text="assetRegister.receiving_time ?? '-'" class="text-center"></td>
                    <td x-text="assetRegister.quantity_registered ?? '-'" class="text-center"></td>
                    <td>
                        <input class="form-control tw-min-w-20" type="number" min="1" x-model="assetRegister.quantity_approved">
                    </td>
                    <td>
                        <input class="form-control tw-w-fit" type="number" min="1" x-model="assetRegister.price">
                    </td>
                    <td>
                        <input class="form-control tw-w-fit" type="number" min="1" x-model="assetRegister.tax_money">
                    </td>
                    <td x-text="assetRegister.tax_money + assetRegister.price ?? '-'" class="text-center"></td>
                    <td>
                        <input class="form-control tw-w-fit" type="number" min="1" x-model="assetRegister.supplier_id">
                    </td>
                    <td>
                        <input class="form-control tw-w-fit" type="text" min="1" x-model="assetRegister.link">
                    </td>
                    <td x-text="assetRegister.description ?? '-'"></td>
                    <td x-show="stt === 0" :rowspan="stt === 0 ? organization.asset_register.length : 1" class="text-center align-middle">
                        {{-- button view --}}
                        <button @click="window.location.href = `/shopping-plan-organization/quarter/view/${organization.id}`" class="border-0 bg-body">
                            <i class="fa-solid fa-eye" style="color: #63E6BE;"></i>
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
    </template>
    </tbody>
</table>
<style>
    th, td {
        white-space: nowrap;
    }
</style>
