<div class="tw-grid tw-grid-cols-5 tw-gap-x-3">
    <div>
        <input class="form-control" type="text" x-model="filters.name_code" placeholder="Tên/mã tài sản" @keydown.enter="list(filters)">
    </div>
    <div>
        @include('common.datepicker.datepicker_range', [
           'placeholder' => 'Chọn ngày bảo dưỡng tiếp theo',
           'start' => 'filters.next_maintain_start',
           'end' => 'filters.next_maintain_end',
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
        @include('common.select_custom.simple.select_single', [
           'selected' => 'filters.status',
           'options' => 'LIST_STATUS_MAINTAIN',
           'placeholder' => 'Trạng thái',
        ])
    </div>
    <div class="d-flex justify-content-between">
        <button @click="reloadPage()" type="button" class="btn btn-outline-danger">Xóa lọc</button>
        <button class="btn border-success" @click="handleShowModalCalendar()"><i class="bi bi-calendar4-event color-sc"></i></button>
    </div>
</div>
