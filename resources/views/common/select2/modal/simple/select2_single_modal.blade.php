<select class="form-control select2"
        x-model="{{$model}}"
        x-init="$nextTick(() => {
             $($el).on('change', () => { {{$model}} = $($el).val()});
        })"
        @if(isset($disabled)) :disabled="{{$disabled}}" @endif
>
    <option value="">{{$placeholder ?? 'Chọn ...'}}</option>
    <template x-for="(value, key) in values">
        <option :value="key" x-text="value"></option>
    </template>
</select>
