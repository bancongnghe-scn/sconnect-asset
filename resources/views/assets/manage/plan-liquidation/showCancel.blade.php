<div class="modal fade" id="idshowCancel" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="Close">
    <div class="modal-dialog modal-md">
        <div class="modal-content tw-overflow-auto tw-m-auto tw-mt-28">
            <div class="modal-header">
                <h4 class="modal-title" x-text="'Từ chối'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body tw-overflow-y-auto tw-max-h-60">
                <div class="container mb-3">
                    <label for="">Lý do từ chối <span class="text-danger">*</span></label>
                    <textarea class="form-control tw-h-40" x-model="reasonCancel"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="multiCancel ? handleUpdateAssetOfPlanMulti('cancel') : $dispatch('cancel', { id: idCancel })" type="button" class="btn btn-sc" data-bs-dismiss="modal">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>