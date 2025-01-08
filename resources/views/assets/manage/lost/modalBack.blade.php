<div class="modal fade" id="idModalBackUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <h4 class="modal-title" x-text="title + ' hợp đồng'"></h4> --}}
                <h4 class="modal-title" x-text="'Xác nhận tìm thấy tài sản'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mb-3">
                    <div class="mb-3 active-link tw-w-fit">Thông tin tài sản</div>
                    <div class="row mb-3 d-flex">
                        <div class="col-6 mb-3">
                            <div class="row">
                                <label class="form-label col-5">Mã tài sản</label>
                                <span x-text="data.code" class="col-7"></span>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="row">
                                <label class="form-label col-5">Giá trị tài sản</label>
                                <span x-text="data.price" class="col-7"></span>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="row">
                                <label class="form-label col-5">Tên tài sản</label>
                                <span x-text="data.name" class="col-7"></span>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="row">
                                <label class="form-label col-5">Giá trị còn lại</label>
                                <span x-text="data.price" class="col-7"></span>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="row">
                                <label class="form-label col-5">Loại tài sản</label>
                                <span x-text="data.asset_type ? data.asset_type.name : 'Chưa xác định'" class="col-7"></span>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="row">
                                <label class="form-label col-5">Vị trí</label>
                                <span x-text="data.code" class="col-7"></span>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="row">
                                <label class="form-label col-5">Ngày mua</label>
                                <span x-text="formatDate(data.created_at)" class="col-7"></span>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="row">
                                <label class="form-label col-5">Người sử dụng</label>
                                <span x-text="data.user ? data.user.name : ''" class="col-7"></span>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="row">
                                <label class="form-label col-5">Hạn bảo hành</label>
                                <span x-text="data.code" class="col-7"></span>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="row">
                                <label class="form-label col-5">Trạng thái</label>
                                <span x-text="listStatus[data.status]" class="col-7"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 active-link tw-w-fit">Thông tin ghi nhận tài sản được tìm thấy</div>
                    <div class="row mb-3">
                        <div class="col-8">
                            <span>
                                <label class="tw-font-bold">Ngày tìm thấy </label>
                            </span>
                            <span class="text-danger">*</span>
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
                        <div class="col-8">
                            <label class="form-label">Ghi chú</label>
                            <textarea type="text" class="form-control tw-h-24" x-model="data.description" placeholder="Nhập ghi chú"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="revert()" type="button" class="btn btn-sc">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>

