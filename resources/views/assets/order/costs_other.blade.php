<div class="d-flex justify-content-between tw-pb-8">
    <div class="col-4">
        <div class="mb-2">
            <label>Chi phí vận chuyển, lắp đặt</label>
            @include('common.input.input_price', [
                'model' => 'data.shipping_costs',
                'placeholder' => "Nhập số",
                'disabled' => $disabled ?? false
            ])
        </div>
        <div>
            <label>Chi phí khác</label>
            @include('common.input.input_price', [
                'model' => 'data.other_costs',
                'placeholder' => "Nhập số",
                'disabled' => $disabled ?? false
            ])
        </div>
    </div>
    <div class="col-4" x-data="{
        get totalPrice () {
            if (data.shopping_assets_order === undefined) {
               return 0
            }
            let totalPrice = 0
            data.shopping_assets_order.filter((item) => {
                const vat = +item.price * (+item.vat_rate || 0) / 100
                totalPrice = totalPrice + ((+item.price + vat) * item.total)
            })

            return totalPrice
        }
    }">
        <div>
            <div class="d-flex justify-content-between">
                <label>Tổng tiền hàng</label>
                <label x-text="window.formatCurrencyVND(totalPrice)"></label>
            </div>
            <div class="d-flex justify-content-between">
                <label>Tổng chi phí</label>
                <label
                    x-text="window.formatCurrencyVND(+data.shipping_costs + (+data.other_costs))"></label>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            <label>Tổng tiền thanh toán</label>
            <label
                x-text="window.formatCurrencyVND(totalPrice + (+data.shipping_costs) + (+data.other_costs))"></label>
        </div>
    </div>
</div>
