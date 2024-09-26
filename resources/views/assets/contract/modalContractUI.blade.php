<div class="modal fade" id="modalContractUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="titleModal + ' hợp đồng'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="mb-3 active-link tw-w-fit">Thông tin chung</div>
                    <div class="row">
                        <div class="col-2">
                            <label class="form-label">Mã hợp đồng<label class="tw-text-red-600">*</label></label>
                            <input type="text" class="form-control" x-model="contract.code">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Loại hợp đồng<label class="tw-text-red-600">*</label></label>
                            <select class="form-control" x-model="contract.type">
                                <option value="">Chọn loại hợp đồng ...</option>
                                <template x-for="(value, key) in listContractType" :key="key">
                                    <option :value="key" x-text="value"></option>
                                </template>
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Tên hợp đồng<label class="tw-text-red-600">*</label></label>
                            <input type="text" class="form-control" x-model="contract.name">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Nhà cung cấp<label class="tw-text-red-600">*</label></label>
                            <select class="form-control" x-model="contract.supplier_id">
                                <option value="">Chọn nhà cung cấp ...</option>
                                <template x-for="supplier in listSupplier" :key="supplier.id">
                                    <option :value="supplier.id" x-text="supplier.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group input-da">
                            <input type="text" class="form-control" value="2012-04-05">
                            <div class="input-group-addon">to</div>
                            <input type="text" class="form-control" value="2012-04-19">
                        </div>
                    </div>
                </div>

                <div class="container"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="$dispatch('save')" type="button" class="btn btn-primary">Lưu</button>
            </div>
        </div>
    </div>
</div>
