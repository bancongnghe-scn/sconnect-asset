<div class="d-flex justify-content-between align-items-center form-group">
    <div class="col-10 p-0">
        <div class="d-grid tw-grid-cols-12 tw-gap-x-4 align-items-center">
            <div class="tw-col-span-3">
                <input class="form-control" type="text" x-model="filters.code_name" placeholder="Tên/mã phiếu nhập" @keydown.enter="list(filters)">
            </div>

            <div class="tw-col-span-2">
                <div>
                    @include('common.select_custom.simple.select_single', [
                         'selected' => 'filters.status',
                         'options' => 'LIST_STATUS_IMPORT_WAREHOUSE',
                         'placeholder' => 'Chọn trạng thái',
                    ])
                </div>
            </div>

            <div class="tw-col-span-3">
                <div>
                    @include('common.user.select_single', [
                        'selected' => 'filters.created_by',
                        'options' => 'listUser',
                        'placeholder' => 'Người nhập',
                    ])
                </div>
            </div>

            <div class="tw-col-span-2">
                @include('common.datepicker.datepicker', ['placeholder' => "Thời gian", 'model' => "filters.created_at"])
            </div>

            <div class="tw-col-span-2">
                <button @click="reloadPage()" type="button" class="btn btn-sm btn-outline-danger">Xóa lọc</button>
            </div>
        </div>
    </div>
    <div class="d-flex tw-gap-x-2 align-items-center">
        <button class="btn btn-sc btn-sm px-3" type="button" @click="handleShowModalUI('create')">
            <span>+ Thêm</span>
        </button>
        <a href="/api/import-warehouse/export" download>
            <button type="button" class="btn btn-sm btn-outline-success">
                <i class="fa-solid fa-file-export"></i>
                Xuất Excel
            </button>
        </a>
    </div>
</div>

