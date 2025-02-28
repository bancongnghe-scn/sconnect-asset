<div class="mb-3 active-link tw-w-fit">Thông tin mặt hàng</div>
<div class="mt-3" x-data="{isOrderPlan: +data.type === ORDER_TYPE_CREATE_WITH_PLAN}">
    <table id="example2" class="table table-bordered dataTable dtr-inline" aria-describedby="example2_info">
        <thead>
        <tr>
            <th style="min-width: 20rem">Tên</th>
            <th style="min-width: 8rem">Đơn giá</th>
            <th style="min-width: 5rem">VAT (%)</th>
            <th style="min-width: 7rem">Tiền VAT</th>
            <th style="min-width: 5rem">SL</th>
            <th style="min-width: 8rem">Thành tiền</th>
            <th style="min-width: 16rem">Loại tài sản</th>
            <th style="min-width: 4rem">ĐVT</th>
            <th style="min-width: 20rem">Đơn vị</th>
            <th style="min-width: 15rem">Mô tả</th>
            <th x-show="!isOrderPlan"></th>
        </tr>
        </thead>
        <tbody>
        <template x-for="(asset,index) in data.shopping_assets_order" :key="index">
            <tr>
                <td>
                    <input class="form-control" type="text" x-model="asset.name">
                </td>
                <td>
                    @include('common.input.input_price', [
                        'model' => 'asset.price',
                    ])
                </td>
                <td>
                    <input class="form-control" type="number" min="1" x-model="asset.vat_rate">
                </td>
                <td class="align-middle" x-text="window.formatCurrencyVND(+asset.price * (+asset.vat_rate || 0) / 100)"></td>
                <td>
                    <input class="form-control" type="number" min="1" x-model="asset.total" :disabled="isOrderPlan">
                </td>
                <td class="align-middle" x-text="window.formatCurrencyVND(+asset.price + (+asset.price * (+asset.vat_rate || 0) / 100))"></td>
                <td>
                    @include('common.select_custom.extent.select_single', [
                         'selected' => 'asset.asset_type_id',
                         'options' => 'listAssetType',
                         'placeholder' => 'Loại tài sản',
                         'disabled' => 'isOrderPlan'
                    ])
                </td>
                <td class="align-middle" x-text="listAssetType.find((item) => +item.id === +asset.asset_type_id)?.measure">
                </td>
                <td>
                    @include('common.select_custom.extent.select_single', [
                         'selected' => 'asset.organization_id',
                         'options' => 'listOrganization',
                         'placeholder' => 'Đơn vị',
                         'disabled' => 'isOrderPlan'
                    ])
                </td>
                <td>
                    <input class="form-control" type="text" x-model="asset.description" :disabled="isOrderPlan">
                </td>
                <td class="text-center align-middle" x-show="!isOrderPlan">
                    <button class="border-0 bg-white" @click="data.shopping_assets_order.splice(index, 1)">
                        <i class="bi bi-trash text-red"></i>
                    </button>
                </td>
            </tr>
        </template>
        </tbody>
    </table>
</div>
<button x-show="+data.type === ORDER_TYPE_CREATE_WITH_NOT_PLAN"
        class="btn btn-sm btn-sc mt-3" @click="addRows()">Thêm hàng
</button>
