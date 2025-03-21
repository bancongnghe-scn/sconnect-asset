<div class="modal fade" id="modalConfirmComplete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Hoàn thành kế hoạch kiểm kê</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    Bạn có chắc chắn muốn
                    <span class="tw-font-bold">chuyển Kế hoạch kiểm kê thành “ Đã hoàn thành” ?</span>
                </div>
                <div class="text-red">
                    <span class="tw-font-bold">Lưu ý</span>: Khi hoàn thành kiểm kê các tài sản chưa hoàn thành kiểm kê sẽ không được chỉnh sửa lại thông tin
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="completePlanInventory()" type="button" class="btn btn-sc">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
