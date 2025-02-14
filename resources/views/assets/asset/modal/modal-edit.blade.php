<div class="modal fade" id="modalEditAsset" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="mb-0" style="font-weight: bold; color: #379237">Chỉnh sửa tài sản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4" style="padding-right: 60px;">
                        <ul class="sidebar-tab" style="padding: 0;">
                            <li @click="tabDetail='general-tab'" :class="tabDetail == 'general-tab' ? 'active-sidebar' : ''">Thông tin chung</li>
                            <li @click="tabDetail='guarantee-tab'" :class="tabDetail == 'guarantee-tab' ? 'active-sidebar' : ''">Bảo hành</li>
                            <li @click="tabDetail='maintain-tab'" :class="tabDetail == 'maintain-tab' ? 'active-sidebar' : ''">Bảo dưỡng</li>
                            <li @click="tabDetail='allocation-tab'" :class="tabDetail == 'allocation-tab' ? 'active-sidebar' : ''">Phân bổ</li>
                        </ul>
                        <span>Mã QR</span>
                        <img src="https://media.istockphoto.com/id/1095468748/vi/vec-to/m%C3%A3-qr-m%E1%BA%ABu-m%C3%A3-v%E1%BA%A1ch-hi%E1%BB%87n-%C4%91%E1%BA%A1i-vector-tr%E1%BB%ABu-t%C6%B0%E1%BB%A3ng-%C4%91%E1%BB%83-qu%C3%A9t-%C4%91i%E1%BB%87n-tho%E1%BA%A1i-th%C3%B4ng-minh-b%E1%BB%8B-c%C3%B4-l%E1%BA%ADp-tr%C3%AAn.jpg?s=612x612&w=0&k=20&c=nCjpoa8qW4lREJGqVCQZsWcrKGOcKKuy5RSsSVzqlL8=" alt="" style="width: 100%;">
                    </div>
                    <div class="col-8">
                        <div class="name-asset d-flex" style="gap: 10px;">
                            <h5 class="text-bold" x-text="assetEdit.name"></h5>
                            <span x-html="arrSvgStatus[assetEdit.status]"></span>
                        </div>
                        <div class="general-tab" x-show="tabDetail == 'general-tab'">
                            <h6 class="text-bold">Thông tin chung</h6>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Loại tài sản <span class="text-danger">*</span></span>
                                        {{-- <input type="text" class="form-control" x-model="assetObj.type"> --}}
                                        <select class="form-control select2" data-placeholder="Chọn loại tài sản" id="typeAssetEditSelect" x-model="assetEdit.asset_type_id">
                                            <option value="0" selected>Loại tài sản</option>
                                            <template x-for="(value, key) in assetType">
                                                <option :value="value.id" x-text="value.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Vị trí tài sản <span class="text-danger">*</span></span>
                                        <select class="form-control select2" data-placeholder="Vị trí" id="locationSearchEdit" x-model="assetEdit.location">
                                            <option value="0" selected>Vị trí</option>
                                            <template x-for="(value, key) in listLocation">
                                                <option :value="key" x-text="value"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Mã tài sản <span class="text-danger">*</span></span>
                                        <input type="text" class="form-control" x-model="assetEdit.code">

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Ngày mua <span class="text-danger">*</span></span>
                                        {{-- @include('common.datepicker.datepicker', [
                                            'placeholder' => "Ngày mua",
                                            'model' => "assetEdit.date_purchase",
                                        ]) --}}
                                        <input type="date" class="form-control" id="locationSearch" x-model="assetEdit.date_purchase" onclick="this.showPicker();">

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Tên tài sản <span class="text-danger">*</span></span>
                                        <input type="text" class="form-control" x-model="assetEdit.name">

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Nhà cung cấp <span class="text-danger">*</span></span>
                                        <select class="form-control select2" data-placeholder="Vị trí" id="supplierEdit" x-model="assetEdit.supplier_id">
                                            <option value="0" selected>Nhà cung cấp</option>
                                            <template x-for="(supplier, key) in listSupplier">
                                                <option :value="supplier.id" x-text="supplier.name"></option>
                                            </template>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Đơn vị tính</span>
                                        <input type="text" class="form-control" x-model="LIST_MEASURE[assetEdit.asset_type.measure]" disabled>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Giá trị <span class="text-danger">*</span></span>
                                        <input type="number" class="form-control" x-model="assetEdit.price">

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Số lượng</span></span>
                                        <input type="number" class="form-control" value="1" disabled>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Số serial</span>
                                        <input type="text" class="form-control" x-model="assetEdit.seri_number">

                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <span>Ghi chú</span>
                                        <textarea name="" class="form-control" style="width: 100%" rows="3" x-model="assetEdit.description"></textarea>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="guarantee-tab" x-show="tabDetail == 'guarantee-tab'">
                            <h6 class="text-bold">
                                Bảo hành
                            </h6>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Thời gian bảo hành</span>
                                        <input type="date" class="form-control date-disabled" x-model="assetEdit.date_warranty" disabled>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Hạn bảo hành</span></span>
                                        <input type="text" class="form-control" x-model="assetEdit.warranty_months">
                                    </div>
                                </div>
                                {{-- <div class="col-12">
                                    <div class="mb-3">
                                        <span>Điều kiện bảo hành</span>
                                        <textarea name="" class="form-control" style="width: 100%" rows="3"></textarea>

                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="maintain-tab" x-show="tabDetail == 'maintain-tab'">
                            <h6 class="text-bold">
                                Bảo dưỡng
                            </h6>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Ngày bảo dưỡng gần nhất</span>
                                        <input type="date" class="form-control" x-model="assetEdit.recent_maintenance_date" onclick="this.showPicker();">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Ngày bảo dưỡng tiếp theo</span></span>
                                        <input type="date" class="form-control" x-model="assetEdit.next_maintenance_date" onclick="this.showPicker();">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="allocation-tab" x-show="tabDetail == 'allocation-tab'">
                            <h6 class="text-bold">
                                Phân bổ
                            </h6>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Giá trị tính phân bổ</span>
                                        <input type="number" class="form-control" value="2000000">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Số kì phân bổ còn lại (tháng)</span></span>
                                        <input type="number" class="form-control" value="24">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Ngày bắt đầu phân bổ</span>
                                        <input type="date" class="form-control" value="2024-11-01" onclick="this.showPicker();">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <span>Giá trị đã phân bổ</span></span>
                                        <input type="number" class="form-control" value="83333">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="updateDataAsset()">Cập nhật</button>
            </div>
        </div>
    </div>
</div>