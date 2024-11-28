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
                selectedDates: [
                        new Date('{{ $start ?? null }}'),
                        new Date('{{ $end ?? null }}')
                    ],
                onSelect: (selectedDates) => {
                    @if(isset($start)) {{$start}} = selectedDates.date[0] ? format(selectedDates.date[0], 'dd/MM/yyyy') : null @endif
                    @if(isset($end)) {{$end}} = selectedDates.date[1] ? format(selectedDates.date[1], 'dd/MM/yyyy') : null @endif
                }
            })"
    >
    <span class="input-group-text">
        <i class="fa-regular fa-calendar-days"></i>
    </span>
</div>
