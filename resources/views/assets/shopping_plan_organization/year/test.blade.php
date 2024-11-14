<div x-data="purchasePlan()">
    <template x-for="(month, monthIndex) in months" :key="monthIndex">
        <div class="month-section">
            <h3>Tháng <span x-text="monthIndex + 1"></span></h3>
            <table>
                <thead>
                <tr>
                    <th>Loại tài sản</th>
                    <th>Đơn vị tính</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Tổng</th>
                </tr>
                </thead>
                <tbody>
                <template x-for="(item, index) in month.items" :key="index">
                    <tr>
                        <td x-text="item.assetType"></td>
                        <td x-text="item.unit"></td>
                        <td>
                            <input type="number" x-model.number="item.quantity" @input="calculateMonthTotal(monthIndex)" />
                        </td>
                        <td>
                            <input type="number" x-model.number="item.price" @input="calculateMonthTotal(monthIndex)" />
                        </td>
                        <td x-text="formatCurrency(item.quantity * item.price)"></td>
                    </tr>
                </template>
                </tbody>
            </table>
            <p>Tổng số lượng: <span x-text="month.totalQuantity"></span></p>
            <p>Tổng giá trị: <span x-text="formatCurrency(month.totalPrice)"></span></p>
        </div>
    </template>
</div>

<script>
    function purchasePlan() {
        return {
            months: [
                { items: [{ assetType: 'Máy tính', unit: 'Bộ', quantity: 1, price: 15120000 }], totalQuantity: 0, totalPrice: 0 },
                { items: [{ assetType: 'Laptop', unit: 'Chiếc', quantity: 2, price: 12334555 }], totalQuantity: 0, totalPrice: 0 },
                // Thêm các tháng khác tương tự
            ],

            calculateMonthTotal(monthIndex) {
                let month = this.months[monthIndex];
                month.totalQuantity = month.items.reduce((sum, item) => sum + item.quantity, 0);
                month.totalPrice = month.items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
            },

            formatCurrency(value) {
                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
            }
        };
    }
</script>
