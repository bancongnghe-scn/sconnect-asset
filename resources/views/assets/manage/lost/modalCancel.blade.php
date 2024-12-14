<div class="modal fade" id="idModalCancelUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="'Xác nhận hủy tài sản'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mb-3">
                    <div class="mb-3 active-link tw-w-fit">Thông tin tài sản</div>
                    <div class="row mb-3 d-flex">
                        <div class="col-6 mb-3">
                            <label class="form-label">Mã tài sản</label>
                            <span x-text="data.code"></span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Giá trị tài sản</label>
                            <span x-text="data.price"></span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Tên tài sản</label>
                            <span x-text="data.name"></span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Giá trị còn lại</label>
                            <span x-text="data.price"></span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Loại tài sản</label>
                            <span x-text="data.asset_type_id"></span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Vị trí</label>
                            <span x-text="data.code"></span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Ngày mua</label>
                            <span x-text="data.created_at"></span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Người sử dụng</label>
                            <span x-text="data.code"></span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Hạn bảo hành</label>
                            <span x-text="data.code"></span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Trạng thái</label>
                            <span x-text="data.status"></span>
                        </div>
                    </div>
                    <div class="mb-3 active-link tw-w-fit">Thông tin ghi nhận tài sản đã hủy</div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label class="tw-font-bold">Ngày hủy</label>
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" id="selectSigningDate"
                                       placeholder="Lựa chọn ngày" autocomplete="off">
                                <span class="input-group-text">
                                    <i class="fa-regular fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label class="form-label">Lý do hủy</label>
                            <textarea type="text" class="form-control tw-h-24" x-model="data.description" placeholder="Nhập ghi chú"></textarea>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="cancel()" type="button" class="btn btn-sc">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>

