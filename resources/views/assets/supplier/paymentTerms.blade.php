<div>
    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label tw-font-bold">Số ngày được nợ</label>
            <input type="number" class="form-control" x-model="supplier.meta_data.payment_terms.debt_day" placeholder="Nhập số ngày được nợ">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Thời gian hưởng triết khấu(ngày)</label>
            <input type="number" class="form-control" x-model="supplier.meta_data.payment_terms.discount_period" placeholder="Nhập thời gian hưởng triết khấu">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Tỷ lệ chiết khấu(%)</label>
            <input type="number" class="form-control" x-model="supplier.meta_data.payment_terms.discount_rate" placeholder="Nhập tỷ lệ chiết khấu">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label tw-font-bold">Số tiền đặt cọc</label>
            <input type="number" class="form-control" x-model="supplier.meta_data.payment_terms.deposit_amount" placeholder="Nhập số tiền đặt cọc">
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <label class="form-label tw-font-bold">Diễn giải</label>
            <textarea class="form-control tw-h-40" x-model="supplier.meta_data.payment_terms.description" placeholder="Nhập diễn giải"></textarea>
        </div>
    </div>
</div>
