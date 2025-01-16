<div class="col-10 tw-grid tw-grid-cols-4 tw-gap-x-3">
    <div>
        <input class="form-control" type="text" x-model="filters.name" placeholder="Tên/mã tài sản" @keydown.enter="list(filters)">
    </div>
    <div>
        @include('common.datepicker.datepicker_range', [
           'placeholder' => 'Chọn ngày',
           'start' => 'filters.start_date_maintain',
           'end' => 'filters.complete_date_maintain',
        ])
    </div>
    <div>
        @include('common.select_custom.simple.select_single', [
           'selected' => 'filters.location',
           'options' => 'LIST_LOCATION_ASSET',
           'placeholder' => 'Vị trí',
        ])
    </div>
    <div>
        <button @click="reloadPage()" type="button" class="btn btn-outline-danger">Xóa lọc</button>
    </div>
</div>
