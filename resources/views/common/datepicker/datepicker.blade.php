<div class="input-group">
    <input type="text" class="form-control" placeholder="{{$placeholder ?? 'Chọn năm'}}" autocomplete="off"
           @if(isset($id)) id="{{$id}}" @endif
           @if(isset($model)) x-model="{{$model}}" @endif
           @if(isset($disabled)) :disabled="{{$disabled}}" @endif
           x-init="
            const datepicker = new AirDatepicker($el, {
                    autoClose: true,
                    clearButton: true,
                    locale: localeEn,
                    dateFormat: 'dd/MM/yyyy',
                    onSelect: ({date}) => {
                        {{$model}} = date != null ? format(date, 'dd/MM/yyyy') : null
                    }
            });

            $el.addEventListener('keydown', (e) => {
              if (e.key === 'Backspace' || e.key === 'Delete') {
                 setTimeout(() => {
                    {{$model}} = $el.value
                    if (!$el.value) {
                       {{$model}} = null
                    }}, 0)}
            });
        "
    >
    <span class="input-group-text">
        <i class="fa-regular fa-calendar-days"></i>
    </span>
</div>
