<div>
    <div class="row">
        <div class="col-4">
            <label class="form-label tw-font-bold">Số ngày được nợ</label>
            <input type="text" class="form-control" x-model="supplier.meta_data.payment_terms.debt_day">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Thời gian hưởng triết khấu(ngày)</label>
            <input type="text" class="form-control" x-model="supplier.meta_data.payment_terms.discount_period">
        </div>
        <div class="col-4">
            <label class="form-label tw-font-bold">Tỷ lệ chiết khấu(%)</label>
            <input type="text" class="form-control" x-model="supplier.meta_data.payment_terms.discount_rate">
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <label class="form-label tw-font-bold">Số tiền đặt cọc</label>
            <input type="text" class="form-control" x-model="supplier.meta_data.payment_terms.deposit_amount">
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <label class="form-label tw-font-bold">Diễn giải</label>
            <textarea class="form-control tw-h-40" x-model="supplier.meta_data.payment_terms.description"></textarea>
        </div>
    </div>
</div>
