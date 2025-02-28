<div class="modal fade" id="modalInsert" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tạo mới đơn hàng</h4>
                <div>
                    <button @click="create()" type="button" class="btn btn-sc">Lưu</button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-warning text-white"
                            @click="$('#modalSelectTypeCreate').modal('show')"
                    >Quay lại</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="card h-100 overflow-y-auto custom-scroll">
                    <div class="card-body">
                        {{--Thong tin chung--}}
                        <div class="mb-3">
                            @include('assets.order.order_info_general', ['disabled' => false, 'action' => 'create'])
                        </div>

                        {{--  thông tin mặt hàng--}}
                        <div class="mb-3">
                            @include('assets.order.shopping_asset_info')
                        </div>
                        <hr>

                        {{--chi phi khac--}}
                        @include('assets.order.costs_other', ['disabled' => false])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

