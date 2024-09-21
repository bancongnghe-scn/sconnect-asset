<div>
    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label tw-font-bold">Số tài khoản</label>
            <input type="number" class="form-control" x-model="supplier.meta_data.payment_account.number">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Tên ngân hàng</label>
            <input type="text" class="form-control" x-model="supplier.meta_data.payment_account.name">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Chủ tài khoản</label>
            <input type="text" class="form-control" x-model="supplier.meta_data.payment_account.owner">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label tw-font-bold">Chi nhánh</label>
            <input type="text" class="form-control" x-model="supplier.meta_data.payment_account.branch">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Tỉnh/TP của ngân hàng</label>
            <input type="text" class="form-control" x-model="supplier.meta_data.payment_account.province">
        </div>
    </div>
</div>
