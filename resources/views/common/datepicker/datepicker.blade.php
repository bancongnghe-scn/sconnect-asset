<div class="input-group">
    <input type="text" class="form-control" placeholder="{{$placeholder ?? 'Chọn năm'}}" autocomplete="off"
           @if(isset($id)) id="{{$id}}" @endif
           x-model="selected"
           @if(isset($disabled)) :disabled="{{$disabled}}" @endif
           x-data="{
                init() {
                    const datepicker = new AirDatepicker($el, {
                        autoClose: true,
                        clearButton: true,
                        locale: localeVi,
                        dateFormat: 'dd/MM/yyyy',
                        onSelect: ({date}) => {
                            this.selected = date != null ? this.selected : null
                        }
                    })
                    $el.addEventListener('keydown', (e) => {
                      if (e.key === 'Backspace' || e.key === 'Delete') {
                         setTimeout(() => {
                            this.selected = $el.value
                            if (!$el.value) {
                               this.selected = null
                            }}, 0)}
                    })

                    this.$watch('{{$model}}', (value) => {
                        this.selected = value != null ? format(value, 'dd/MM/yyyy') : null
                    })

                    this.$watch('selected', (value) => {
                        {{$model}} = value != null ? formatDate(value) : null
                    })
                },

                selected: null
           }"
    >
    <span class="input-group-text">
        <i class="fa-regular fa-calendar-days"></i>
    </span>
</div>
