<table id="example2" class="table table-bordered dataTable dtr-inline"
       aria-describedby="example2_info">
    <thead>
    <tr>
        <th class="text-center">Đơn vị</th>
        <th class="text-center">Loại tài sản</th>
        <th class="text-center">Chức danh</th>
        <th class="text-center">Thời gian cần</th>
        <th class="text-center" x-text="`SL(${register.total_register})`"></th>
        <th class="text-center">Mô tả</th>
        <th class="text-center" x-show="+data.status === STATUS_SHOPPING_PLAN_COMPANY_HR_HANDLE">Xử lý</th>
        <th class="text-center tw-w-28">Thao tác</th>
    </tr>
    </thead>
    <tbody>
    <template x-for="(organization, index) in register.organizations" :key="index">
        <template x-for="(assetRegister, stt) in organization.asset_register" :key="index + '_' + stt">
            <tr>
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
                <td x-text="assetRegister.description ?? '-'"></td>
                <td x-show="+data.status === STATUS_SHOPPING_PLAN_COMPANY_HR_HANDLE">
                    <template x-if="typeof assetRegister.action !== 'undefined'">
                        <select class="form-select" x-model="assetRegister.action">
                            <option value="1">Mua mới</option>
                            <option value="2">Luân chuyển</option>
                        </select>
                    </template>
                </td>
                <td x-show="stt === 0" :rowspan="stt === 0 ? organization.asset_register.length : 1" class="text-center align-middle">
                    {{-- button view --}}
                    <button @click="window.location.href = `/shopping-plan-organization/week/view/${organization.id}`" class="border-0 bg-body">
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
    </tbody>
</table>

