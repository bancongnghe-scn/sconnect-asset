<table id="example2" class="table table-bordered dataTable dtr-inline"
       aria-describedby="example2_info">
    <thead class="position-sticky z-1" style="top: -1px">
    <tr>
        <th class="text-center"
            x-show="action === 'update' && [
                 STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_HR,
                 STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL,
                 STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL
            ].includes(+data.status)"
        >
            <input type="checkbox" @click="selectedAll">
        </th>
        <th class="text-center">Đơn vị</th>
        <th class="text-center"
            x-show="[
                STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_HR,
                STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL,
                STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL
            ].includes(+data.status)"
        >Trạng thái</th>
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
    <template x-for="(organization, index) in shoppingAssetWithAction" :key="index">
        <template x-for="(assetRegister, stt) in organization.asset_register.new" :key="index + '_' + stt">
            <tr x-data="{
                    get total() {
                        if (assetRegister.tax_money !== null && assetRegister.price === null) {
                            return formatCurrencyVND(+assetRegister.tax_money)
                        } else if (assetRegister.tax_money === null && assetRegister.price !== null) {
                            return formatCurrencyVND(+assetRegister.price)
                        } else if (assetRegister.tax_money === null && assetRegister.price === null) {
                            return null
                        } else {
                            return formatCurrencyVND((+assetRegister.price) + (+assetRegister.tax_money))
                        }
                    }
                }">
                <td class="text-center align-middle"
                    x-show="action === 'update' && [
                            STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_HR,
                            STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL,
                            STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL
                        ].includes(+data.status)"
                >
                    <input type="checkbox" x-model="selectedRow[assetRegister.id]" x-bind:checked="selectedRow[assetRegister.id]">
                </td>
                <td x-show="stt === 0" :rowspan="stt === 0 ? organization.asset_register.new.length : 1"
                    class="tw-font-bold align-middle">
                    <span x-text="organization.name"></span>
                </td>
                <td
                    x-show="[
                        STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_HR,
                        STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL,
                        STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL
                    ].includes(+data.status)"
                >
                    @include('component.status_shopping_asset', [
                        'status' => 'assetRegister.status', 'tooltip' => 'assetRegister.reason'
                    ])
                </td>
                <td x-text="assetRegister.asset_type_name ?? '-'"></td>
                <td x-text="assetRegister.job_name ?? '-'"></td>
                <td x-text="assetRegister.receiving_time ?? '-'" class="text-center"></td>
                <td x-text="assetRegister.quantity_registered ?? '-'" class="text-center"></td>
                <td>
                    <input class="form-control tw-min-w-20" type="number" min="1" x-model="assetRegister.quantity_approved" :disabled="action === 'view'">
                </td>
                <td>
                    <input class="form-control tw-w-fit" type="number" min="1" x-model="assetRegister.price" :disabled="action === 'view'">
                </td>
                <td>
                    <input class="form-control tw-w-fit" type="number" min="1" x-model="assetRegister.tax_money" :disabled="action === 'view'">
                </td>
                <td x-text="total ?? '-'" class="text-center"></td>
                <td>
                        <span x-data="{
                             model: assetRegister.supplier_id,
                             init() {this.$watch('assetRegister.supplier_id', (newValue) => {if (this.model !== newValue) {this.model = newValue}})}
                        }" @select-change="assetRegister.supplier_id = $event.detail">
                                    @include('common.select2.extent.select2', [
                                          'placeholder' => 'Chọn NCC',
                                          'values' => 'listSupplier',
                                          'disabled' => "action === 'view'"
                                    ])
                        </span>
                </td>
                <td>
                    <input class="form-control tw-w-fit" type="text" min="1" x-model="assetRegister.link" :disabled="action === 'view'">
                </td>
                <td x-text="assetRegister.description ?? '-'"></td>
                <td x-show="stt === 0" :rowspan="stt === 0 ? organization.asset_register.new.length : 1"
                    class="text-center align-middle">
                    {{-- button view --}}
                    <button @click="window.location.href = `/shopping-plan-organization/week/view/${organization.id}`"
                            class="border-0 bg-body">
                        <i class="fa-solid fa-eye" style="color: #63E6BE;"></i>
                    </button>
                </td>
            </tr>
        </template>
    </template>
    </tbody>
</table>
<style>
    th, td {
        white-space: nowrap;
    }
</style>
