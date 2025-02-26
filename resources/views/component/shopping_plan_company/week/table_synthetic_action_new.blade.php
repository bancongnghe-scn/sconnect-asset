<table id="example2" class="table table-bordered dataTable dtr-inline" aria-describedby="example2_info">
    <thead>
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
        <th class="text-center" style="min-width: 12rem">Đơn vị</th>
        <th class="text-center"
            style="min-width: 9rem"
            x-show="[
                STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_HR,
                STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL,
                STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL
            ].includes(+data.status)"
        >Trạng thái</th>
        <th class="text-center" style="min-width: 12rem">Loại tài sản</th>
        <th class="text-center" style="min-width: 11rem">Chức danh</th>
        <th class="text-center">Thời gian cần</th>
        <th class="text-center">SL</th>
        <th class="text-center">SL duyệt</th>
        <th class="text-center">Đơn giá</th>
        <th class="text-center">Thuế</th>
        <th class="text-center" style="min-width: 9rem">Tổng</th>
        <th class="text-center" style="min-width: 14rem">NCC</th>
        <th class="text-center">Ghi chú</th>
        <th class="text-center" style="min-width: 10rem">Mô tả</th>
        <th class="text-center" style="width: 4rem">Thao tác</th>
    </tr>
    </thead>
    <tbody>
    <template x-for="(organization, index) in shoppingAssetWithAction" :key="index">
        <template x-for="(assetRegister, stt) in organization.asset_register.new" :key="index + '_' + stt">
            <tr x-data="{
                isDisabled: action === 'view' || ![
                    SHOPPING_ASSET_STATUS_HR_MANAGER_DISAPPROVAL,
                    SHOPPING_ASSET_STATUS_ACCOUNTANT_DISAPPROVAL,
                    SHOPPING_ASSET_STATUS_GENERAL_DISAPPROVAL,
                ].includes(+assetRegister.status),

                get total() {
                    const tax = +assetRegister.tax_money || 0;
                    const price = +assetRegister.price || 0;

                    return (tax || price)
                        ? formatCurrencyVND(tax + price)
                        : null;
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
                <td class="align-middle"
                    x-show="[
                        STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_HR,
                        STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL,
                        STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL
                    ].includes(+data.status)"
                >
                    @include('component.status.status_shopping_asset', [
                        'status' => 'assetRegister.status', 'tooltip' => 'assetRegister.reason'
                    ])
                </td>
                <td class="align-middle" x-text="assetRegister.asset_type_name ?? '-'"></td>
                <td class="align-middle" x-text="assetRegister.job_name ?? '-'"></td>
                <td class="text-center align-middle" x-text="assetRegister.receiving_time ?? '-'"></td>
                <td class="align-middle text-center" x-text="assetRegister.quantity_registered ?? '-'"></td>
                <td class="align-middle">
                    <input class="form-control tw-min-w-20" type="number" min="1" x-model="assetRegister.quantity_approved"
                           :disabled="isDisabled"
                    >
                </td>
                <td class="tw-min-w-36 align-middle">
                    @include('common.input.input_price', [
                        'model' => 'assetRegister.price',
                        'disabled' => 'isDisabled'
                    ])
                </td>
                <td class="tw-min-w-32 align-middle">
                    @include('common.input.input_price', [
                        'model' => 'assetRegister.tax_money',
                        'disabled' => 'isDisabled'
                    ])
                </td>
                <td x-text="total ?? '-'" class="text-center align-middle"></td>
                <td class="align-middle">
                    @include('common.select_custom.extent.select_single', [
                        'selected' => 'assetRegister.supplier_id',
                        'options' => 'listSupplier',
                        'placeholder' => 'Chọn NCC',
                        'disabled' => "isDisabled"
                    ])
                </td>
                <td class="align-middle">
                    <input class="form-control tw-w-fit" type="text" min="1" x-model="assetRegister.link"
                           :disabled="isDisabled">
                </td>
                <td class="align-middle" x-text="assetRegister.description ?? '-'"></td>
                <td x-show="stt === 0" :rowspan="stt === 0 ? organization.asset_register.new.length : 1"
                    class="text-center align-middle">
                    {{-- button view --}}
                    <button @click="handleShowModalDetailOrganization(organization.id)"
                            class="border-0 bg-white">
                        <i class="bi bi-eye text-info"></i>
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
