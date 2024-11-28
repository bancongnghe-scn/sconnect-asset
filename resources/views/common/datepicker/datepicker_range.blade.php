<div class="input-group">
    <input type="text" class="form-control datepicker"
           placeholder="{{$placeholder ?? 'Chọn ngày'}}" autocomplete="off"
           @if(isset($id)) id="{{$id}}" @endif
           @if(isset($disabled)) :disabled="{{$disabled}}" @endif
           x-init="new AirDatepicker($el, {
                range: true,
                multipleDatesSeparator: ' - ',
                autoClose: true,
                clearButton: true,
                locale: localeEn,
                dateFormat: 'dd/MM/yyyy',
                selectedDates: [{{$start}} !== null ? formatDate({{$start}}) : new Date(), {{$end}} !== null ? formatDate({{$end}}) : new Date()],
                onSelect: (selectedDates) => {
                    {{$start}} =  selectedDates.date[0] ? format(selectedDates.date[0], 'dd/MM/yyyy') : null
                    {{$end}} =  selectedDates.date[1] ? format(selectedDates.date[1], 'dd/MM/yyyy') : null
                }
            })"
    >
    <span class="input-group-text">
        <i class="fa-regular fa-calendar-days"></i>
    </span>
</div>
