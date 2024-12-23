<div class="modal fade" id="modalSelectTypeCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chọn loại thêm mới đơn hàng</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex tw-gap-x-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" :value="ORDER_TYPE_CREATE_WITH_PLAN" id="flexRadioDefault1" x-model="typeCreateOrder">
                        <label class="form-check-label" for="flexRadioDefault1">
                            Đơn hàng theo kế hoạch
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" :value="ORDER_TYPE_CREATE_WITH_NOT_PLAN" id="flexRadioDefault2" x-model="typeCreateOrder">
                        <label class="form-check-label" for="flexRadioDefault2">
                            Đơn hàng không theo kế hoạch
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sc" data-bs-dismiss="modal" @click="handleShowModalUI('create')">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
