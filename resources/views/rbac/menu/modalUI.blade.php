<?php
    $fontAwesomeIcons = config('icon');
?>
<div class="modal fade" id="idModalUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="titleModal + ' menu'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 d-flex gap-2">
                    <div class="col-6">
                        <label class="form-label">Tên menu<label class="color-red">*</label></label>
                        <input type="text" class="form-control" x-model="menu.name" placeholder="Nhập tên menu">
                    </div>

                    <div class="col-6">
                        <label class="form-label">Icon<label class="color-red">*</label></label>
                        <select class="form-select select2 icon-select2" id="selectIcon" data-placeholder="Chọn icon ..." x-model="menu.icon">
                            <option value=""></option>
                            @foreach($fontAwesomeIcons as $key => $value)
                                <option value="{{ $value }}">{{$key}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3 d-flex gap-2">
                    <div class="col-6">
                        <label class="form-label">Thứ tự<label class="color-red">*</label></label>
                        <input type="number" class="form-control" x-model="menu.order" placeholder="Nhập thứ tự">
                    </div>

                    <div class="col-6">
                        <label class="form-label">Đường dẫn</label>
                        <input type="text" class="form-control" x-model="menu.url" placeholder="Nhập đường dẫn">
                    </div>
                </div>

                <div class="mb-3 d-flex gap-2">
                    <div class="col-6">
                        <label class="form-label">Menu cha</label>
                        <select class="form-select select2" x-model="menu.parent_id" id="selectMenuParent">
                            <option value="">Chọn menu cha ...</option>
                            <template x-for="value in listMenuParent" :key="value.id">
                                <option :value="value.id" x-text="value.name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="col-6">
                        <label class="form-label">Danh sách vai trò</label>
                        <select class="form-select select2" x-model="menu.role_ids" id="selectRoles" multiple="multiple" data-placeholder="Chọn danh sách vai trò ...">
                            <template x-for="value in listRole" :key="value.id">
                                <option :value="value.id" x-text="value.name"></option>
                            </template>
                        </select>
                    </div>
                </div>


                <div class="mb-3">
                    <div class="col-auto">
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control tw-h-40" x-model="menu.description" placeholder="Nhập ghi chú"></textarea>
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

