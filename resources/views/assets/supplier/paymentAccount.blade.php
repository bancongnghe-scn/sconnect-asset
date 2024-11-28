<div>
    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label tw-font-bold">Số tài khoản</label>
            <input type="text" class="form-control" x-model="data.meta_data.payment_account.number" placeholder="Nhập số tài khoản">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Tên ngân hàng</label>
            <input type="text" class="form-control" x-model="data.meta_data.payment_account.name" placeholder="Nhập tên ngân hàng">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Chủ tài khoản</label>
            <input type="text" class="form-control" x-model="data.meta_data.payment_account.owner" placeholder="Nhập chủ tài khoản">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label tw-font-bold">Chi nhánh</label>
            <input type="text" class="form-control" x-model="data.meta_data.payment_account.branch" placeholder="Nhập chi nhánh">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Tỉnh/TP của ngân hàng</label>
            <input type="text" class="form-control" x-model="data.meta_data.payment_account.province" placeholder="Nhập tỉnh/TP của ngân hàng">
        </div>
    </div>
</div>
