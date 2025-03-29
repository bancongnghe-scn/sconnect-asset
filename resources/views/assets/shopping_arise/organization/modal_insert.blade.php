<div class="modal fade" id="modalCreateShoppingArise" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Kế hoạch mua sắm phát sinh</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('assets.shopping_arise.organization.shopping_arise_info', ['view' => 'insert'])
            </div>
            <div class="modal-footer">
                <button @click="createShoppingArise" type="button" class="btn btn-sc">Thêm mới</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>


