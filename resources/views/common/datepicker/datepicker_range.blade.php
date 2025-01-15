<div class="input-group">
    <input type="text" class="form-control datepicker"
           placeholder="{{$placeholder ?? 'Chọn ngày'}}" autocomplete="off"
           @if(isset($id)) id="{{$id}}" @endif
           @if(isset($disabled)) :disabled="{{$disabled}}" @endif
           x-init="
                const datePicker = new AirDatepicker($el, {
                    range: true,
                    multipleDatesSeparator: ' - ',
                    autoClose: true,
                    clearButton: true,
                    locale: localeVi,
                    dateFormat: 'dd/MM/yyyy',
                    selectedDates: [{{$start}} !== null ? formatDate({{$start}}) : '', {{$end}} !== null ? formatDate({{$end}}) : ''],
                    onSelect: (selectedDates) => {
                        {{$start}} =  selectedDates.date[0] ? format(selectedDates.date[0], 'dd/MM/yyyy') : null
                        {{$end}} =  selectedDates.date[1] ? format(selectedDates.date[1], 'dd/MM/yyyy') : null
                    }
                });

                $watch(`{{$start}}`, (newValue) => {
                   if(newValue === null) {
                       datePicker.clear()
                   } else {
                        if({{$end}} !== null) {
                            datePicker.selectDate([{{$start}} !== null ? formatDate({{$start}}) : '', {{$end}} !== null ? formatDate({{$end}}) : ''])
                        }
                   }
                });

                $watch(`{{$end}}`, (newValue) => {
                   if(newValue === null) {
                       datePicker.clear()
                   } else {
                       if({{$start}} !== null) {
                            datePicker.selectDate([{{$start}} !== null ? formatDate({{$start}}) : '', {{$end}} !== null ? formatDate({{$end}}) : ''])
                        }
                   }
                });
           "
    >
    <span class="input-group-text">
        <i class="fa-regular fa-calendar-days"></i>
    </span>
</div>
