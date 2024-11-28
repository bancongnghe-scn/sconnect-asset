<select class="form-select select2"
        x-model="{{$model}}"
        x-init="$nextTick(() => {
             $($el).on('change', () => { {{$model}} = $($el).val()});
        })"
        @if(isset($disabled)) :disabled="{{$disabled}}" @endif
>
    <option value="">{{$placeholder ?? 'Chọn ...'}}</option>
    <template x-for="value in values" :key="value.id">
        <option :value="value.id" x-text="value.name"></option>
    </template>
</select>
