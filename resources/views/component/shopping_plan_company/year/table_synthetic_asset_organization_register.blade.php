<table id="example2" class="table table-bordered dataTable dtr-inline"
       aria-describedby="example2_info">
    <thead class="position-sticky z-1" style="top: -1px">
    <tr>
        <th rowspan="2" class="text-center">STT</th>
        <th rowspan="2" class="text-center">Đơn vị</th>
        <th rowspan="2" class="text-center">Loại tài sản</th>
        <th colspan="12" class="text-center">Số lượng đăng ký theo tháng</th>
        <th rowspan="2" class="text-center">Tổng Số lượng</th>
        <th rowspan="2" class="text-center">Tổng Thành tiền</th>
        <template x-if="action === 'update'">
            <th rowspan="2" class="text-center tw-w-28">Thao tác</th>
        </template>
    </tr>
    <tr>
        <template x-for="number in Array.from({ length: 12 }, (_, i) => i + 1)" :key="number">
            <th x-text="`T` + number" class="text-center"></th>
        </template>
    </tr>
    </thead>
    <tbody>
    <template x-for="(organization, index) in register.organizations" :key="index">
        <template x-for="(assetRegister, stt) in organization.asset_register" :key="index + '_' + stt">
            <tr>
                <td x-text="register.organizations.length - index" x-show="stt === 0" :rowspan="stt === 0 ? organization.asset_register.length : 1"></td>
                <td x-text="organization.name" x-show="stt === 0" :rowspan="stt === 0 ? organization.asset_register.length : 1" class="tw-font-bold"></td>
                <td x-text="assetRegister.asset_type_name ?? '-'" class="text-center"></td>
                <template x-for="number in Array.from({ length: 12 }, (_, i) => i + 1)" :key="index + '_' + stt + '_' + number">
                    <td x-text="assetRegister.register?.[number - 1] ?? '-'" class="text-center"></td>
                </template>
                <td x-text="assetRegister.total_register ?? '-'" class="text-center"></td>
                <td x-text="window.formatCurrencyVND(organization.total_price)" x-show="stt === 0" :rowspan="stt === 0 ? organization.asset_register.length : 1" class="text-center"></td>
                <template x-if="action === 'update'">
                    <td x-show="stt === 0" :rowspan="stt === 0 ? organization.asset_register.length : 1" class="text-center">
                        <button @click="window.location.href = `/shopping-plan-organization/year/register/${organization.id}`" class="border-0 bg-body">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        @can('shopping_plan_company.accounting_approval')
                            <template x-if="+data.status !== STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL">
                                <div>
                                    <button class="border-0 bg-body"><i class="fa-solid fa-thumbs-up"></i></button>
                                    <button class="border-0 bg-body"><i class="fa-solid fa-thumbs-down"></i></button>
                                </div>
                            </template>
                        @endcan
                    </td>
                </template>
            </tr>
        </template>
    </template>
    </tbody>
</table>
