<div class="modal fade" id="modalUIOrganization" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Định mức cấp phát</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body overflow-auto custom-scroll tw-h-[40rem]">
                <div class="tw-grid tw-grid-cols-2 tw-gap-x-3">
                    <div>
                        <label class="form-label">Đơn vị<label class="tw-text-red-600 mb-0">*</label></label>
                        @include('common.select_custom.extent.select_single', [
                             'selected' => 'data.organization_id',
                             'options' => 'listOrganization',
                             'placeholder' => 'Chọn đơn vị',
                             'disabled' => "action === 'update'"
                        ])
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">Định mức cấp phát</label>
                    <div class="row">
                        <div class="col-12">
                            <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="example2" class="table table-bordered dataTable dtr-inline"
                                               aria-describedby="example2_info">
                                            <thead>
                                                <tr>
                                                    <th rowspan="1" colspan="1" class="text-center tw-w-52">Loại tài sản</th>
                                                    <th rowspan="1" colspan="1" class="text-center tw-w-40">Hạng</th>
                                                    <th rowspan="1" colspan="1" class="text-center tw-w-60">Giá</th>
                                                    <th rowspan="1" colspan="1" class="text-center">Ghi chú</th>
                                                    <th rowspan="1" colspan="1"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <template x-for="(config, index) in data.configs" :key="index">
                                                     <tr>
                                                         <td>
                                                             @include('common.select_custom.extent.select_single', [
                                                                 'selected' => 'config.asset_type_id',
                                                                 'options' => 'listAssetType',
                                                                 'placeholder' => 'Chọn loại',
                                                            ])
                                                         </td>
                                                         <td>
                                                             @include('common.select_custom.simple.select_single', [
                                                                 'selected' => 'config.level',
                                                                 'options' => 'LEVEL_ALLOCATION_RATE',
                                                                 'placeholder' => 'Chọn hạng',
                                                            ])
                                                         </td>
                                                         <td>
                                                             <input class="form-control" type="number" x-model="config.price" placeholder="Nhập giá">
                                                         </td>
                                                         <td>
                                                             <input class="form-control" type="text" x-model="config.description" placeholder="Nhập ghi chú">
                                                         </td>
                                                         <td class="text-center align-middle">
                                                             <button class="border-0 bg-white" @click="data.configs.splice(index, 1)">
                                                                 <i class="bi bi-trash text-red"></i>
                                                             </button>
                                                         </td>
                                                     </tr>
                                                 </template>
                                            </tbody>
                                        </table>
                                        <button @click="addRowTable()" type="button" class="btn btn-sm btn-sc">Thêm hàng</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="action === 'create' ? create() : edit()" type="button" class="btn btn-sc">Lưu</button>
            </div>
        </div>
    </div>
</div>
