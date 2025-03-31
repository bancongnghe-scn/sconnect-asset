<table id="example2" class="table table-bordered dataTable dtr-inline" aria-describedby="example2_info"
       x-data="table_synthetic_action_new">
    <thead>
    <tr>
        <th class="text-center"
            x-show="isStatusHandle"
        >
            <input type="checkbox" @click="selectedAll">
        </th>
        <th class="text-center" style="min-width: 11rem;">Loại tài sản</th>
        <th class="text-center"
            style="min-width: 9rem"
            x-show="isStatusHandle"
        >Trạng thái</th>
        <th class="text-center" style="min-width: 2rem;">SL</th>
        <th class="text-center" style="min-width: 5rem;">SL duyệt</th>
        <th class="text-center" style="min-width: 14rem;">Vị trí chức danh</th>
        <th class="text-center" style="min-width: 8rem;">Thời gian cần</th>
        <th class="text-center">Mô tả</th>
        <th class="text-center" style="min-width: 8rem;">Đơn giá</th>
        <th class="text-center" style="min-width: 8rem;">Thuế</th>
        <th class="text-center" style="min-width: 8rem;">Tổng</th>
        <th class="text-center" style="min-width: 16rem;">NCC</th>
        <th class="text-center" style="min-width: 20rem;">Link</th>
    </tr>
    </thead>
    <tbody>
    <template x-for="(value, index) in assetSynthetic.new" :key="index">
         <tr>
             <td class="text-center align-middle"
                 x-show="isStatusHandle"
             >
                 <input type="checkbox" x-model="selectedRow[value.id]" x-bind:checked="selectedRow[value.id]">
             </td>
             <td class="align-middle" x-text="list_asset_type.find(item => item.id === value.asset_type_id)?.name"></td>
             <td class="align-middle" x-show="isStatusHandle">
                 @include('component.status.status_shopping_asset', ['status' => 'value.status'])
             </td>
             <td class="align-middle text-center" x-text="value.quantity_registered"></td>
             <td class="align-middle">
                 <input class="form-control" x-model="value.quantity_approved" type="number">
             </td>
             <td class="align-middle" x-text="list_job.find(item => item.id === value.job_id)?.name"></td>
             <td class="align-middle text-center" x-text="formatDateVN(value.receiving_time)"></td>
             <td class="align-middle" x-text="value.description"></td>
             <td class="align-middle">
                 @include('common.input.input_price', [
                      'model' => 'value.price',
                      'placeholder' => 'Nhập giá'
                 ])
             </td>
             <td class="align-middle">
                 @include('common.input.input_price', [
                      'model' => 'value.tax_money',
                      'placeholder' => 'Nhập giá'
                 ])
             </td>
             <td class="align-middle" x-text="formatCurrencyVND((+value.price + (+value.tax_money))*value.quantity_approved)"></td>
             <td>
                 @include('common.select_custom.extent.select_single', [
                    'selected' => 'value.supplier_id',
                    'options' => 'list_supplier',
                    'placeholder' => 'Chọn NCC',
                 ])
             </td>
             <td class="align-middle">
                 <input class="form-control" type="text" x-model="value.link">
             </td>
         </tr>
    </template>
    </tbody>

    <script>
        function table_synthetic_action_new() {
            return {
                init() {
                    this.$watch('data.status', (value) => {
                        this.isStatusHandle = [
                            STATUS_SHOPPING_ARISE_PENDING_MANAGER_HR,
                            STATUS_SHOPPING_ARISE_PENDING_ACCOUNTANT,
                            STATUS_SHOPPING_ARISE_PENDING_MANAGER
                        ].includes(+value)
                    })
                },

                isStatusHandle: false,
            }
        }
    </script>
</table>

