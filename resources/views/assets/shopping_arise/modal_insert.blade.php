<div class="modal fade" id="modalCreateShoppingArise" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Kế hoạch mua sắm phát sinh</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <div class="mb-3 active-link tw-w-fit">Thông tin chung</div>
                    <div>
                        <label>Nội dung<span class="tw-ml-1 tw-text-red-600 mb-0">*</span></label>
                        <input class="form-control" type="text" x-model="data.name">
                    </div>
                </div>

                <div>
                    <div class="mb-3 active-link tw-w-fit">Chi tiết kế hoạch</div>
                    <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <div class="table-responsive custom-scroll" style="max-height: 30rem">
                            <table id="example2" class="table table-bordered dataTable dtr-inline" aria-describedby="example2_info">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 18rem;">Loại tài sản</th>
                                    <th class="text-center" style="width: 6rem;">Số lượng</th>
                                    <th class="text-center" style="width: 15rem;">Vị trí chức danh</th>
                                    <th class="text-center" style="width: 11rem;">Thời gian cần</th>
                                    <th class="text-center" style="width: 15rem;">Mô tả</th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="(value, index) in data.assets" :key="index">
                                    <tr>
                                        <td class="align-middle">
                                            @include('common.select_custom.extent.select_single', [
                                                  'placeholder' => 'Chọn loại tài sản',
                                                  'selected' => 'value.asset_type_id',
                                                  'options' => 'list_asset_type',
                                            ])
                                        </td>
                                        <td class="align-middle">
                                            <input class="form-control" type="number" min="1"
                                                   x-model="value.quantity_registered">
                                        </td>
                                        <td class="align-middle">
                                            @include('common.select_custom.extent.select_single', [
                                                'placeholder' => 'Chọn chức danh',
                                                'selected' => 'value.job_id',
                                                'options' => 'list_job',
                                            ])
                                        </td>
                                        <td class="align-middle">
                                            @include('common.datepicker.datepicker', [
                                                'placeholder'=>"Thời gian cần",
                                                'model' => "value.receiving_time",
                                            ])
                                        </td>
                                        <td class="align-middle">
                                            <input class="form-control" type="text"
                                                   x-model="value.description">
                                        </td>
                                        <td class="text-center align-middle">
                                            <button class="border-0 bg-white" @click="data.assets.splice(index,1)">
                                                <i class="bi bi-trash3 text-red"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <button @click="addRowAsset" type="button" class="btn btn-sm btn-sc">Thêm hàng</button>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="createShoppingArise" type="button" class="btn btn-sc">Thêm mới</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>


