<div class="input-group">
    <input type="text" class="form-control" placeholder="{{$placeholder ?? 'Chọn năm'}}" autocomplete="off"
        @if(isset($id)) id="{{$id}}" @endif
        @if(isset($model)) x-model="{{$model}}" @endif
        @if(isset($disabled)) :disabled="{{$disabled}}" @endif
        x-init="
            new AirDatepicker($el, {
                    view: 'years', // Hiển thị danh sách năm khi mở
                    minView: 'years', // Giới hạn chỉ cho phép chọn năm
                    dateFormat: 'yyyy', // Định dạng chỉ hiển thị năm
                    autoClose: true, // Tự động đóng sau khi chọn năm
                    clearButton: true, // Nút xóa để bỏ chọn
                    onSelect: ({date}) => {
                        {{$model}} = date.getFullYear()
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
