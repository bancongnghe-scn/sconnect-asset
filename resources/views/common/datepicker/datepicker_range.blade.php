<div class="input-group">
    <input type="text" class="form-control datepicker"
           placeholder="{{$placeholder ?? 'Chọn ngày'}}" autocomplete="off"
           @if(isset($id)) id="{{$id}}" @endif
           @if(isset($disabled)) :disabled="{{$disabled}}" @endif
           x-data="{
              init() {
                    const datePicker = new AirDatepicker($el, {
                        range: true,
                        multipleDatesSeparator: ' - ',
                        autoClose: true,
                        clearButton: true,
                        locale: localeVi,
                        dateFormat: 'dd/MM/yyyy',
                        selectedDates: [this.start !== null ? formatDate(this.start) : '', this.end !== null ? formatDate(this.end) : ''],
                        onSelect: (selectedDates) => {
                            this.start =  selectedDates.date[0] ? format(selectedDates.date[0], 'dd/MM/yyyy') : null
                            this.end =  selectedDates.date[1] ? format(selectedDates.date[1], 'dd/MM/yyyy') : null
                        }
                    });

                    $watch('start', (newValue) => {
                       if(newValue === null) {
                           datePicker.clear()
                           {{$start}} = null
                       } else {
                         {{$start}} = formatDate(newValue)
                         if(this.end !== null) {
                            datePicker.selectDate([this.start !== null ? formatDate(this.start) : '', this.end !== null ? formatDate(this.end) : ''])
                         }
                       }
                    });

                    $watch('end', (newValue) => {
                       if(newValue === null) {
                           datePicker.clear()
                           {{$end}} = null
                       } else {
                           {{$end}} = formatDate(newValue)
                           if(this.start !== null) {
                                datePicker.selectDate([this.start !== null ? formatDate(this.start) : '', this.end !== null ? formatDate(this.end) : ''])
                           }
                       }
                    });

                    $watch('{{$start}}', (newValue) => {
                       this.start = {{$start}} !== null ? format({{$start}}, 'dd/MM/yyyy') : null
                    });

                    $watch('{{$end}}', (newValue) => {
                       this.end = {{$end}} !== null ? format({{$end}}, 'dd/MM/yyyy') : null
                    });
              },

              start: null,
              end: null
           }"
    >
    <span class="input-group-text">
        <i class="fa-regular fa-calendar-days"></i>
    </span>
</div>
