<div class="input-group">
    <input type="text" class="form-control" placeholder="{{$placeholder ?? 'Chọn tháng'}}" autocomplete="off"
           x-model="{{$model}}"
        @if(isset($id)) id="{{$id}}" @endif
        @if(isset($disabled)) :disabled="{{$disabled}}" @endif
        x-init="
            new AirDatepicker($el, {
                    view: 'months',
                    minView: 'months',
                    dateFormat: 'MMMM yyyy',
                    locale: localeVi,
                    autoClose: true, // Tự động đóng sau khi chọn năm
                    clearButton: true, // Nút xóa để bỏ chọn
                    onSelect: ({date}) => {
                        {{$model}} = date != null ? format(date, 'MM/yyyy') : null
                    }
            })

            $el.addEventListener('keydown', (e) => {
              if (e.key === 'Backspace' || e.key === 'Delete') {
                 setTimeout(() => {
                    {{$model}} = $el.value
                    if (!$el.value) {
                       {{$model}} = null
                    }}, 0)}
            })
        "
    >
    <span class="input-group-text"><i class="fa-regular fa-calendar-days"></i></span>
</div>
