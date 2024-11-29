<select class="form-select select2"
        multiple="multiple"
        x-model="{{$model}}"
        data-placeholder="{{$placeholder ?? 'Chọn ...'}}"
        x-init="$nextTick(() => {
             $($el).on('change', () => { {{$model}} = $($el).val()});
        })"
        @if(isset($disabled)) :disabled="{{$disabled}}" @endif
>
    <template x-for="value in values" :key="value.id">
        <option :value="value.id" x-text="value.name"></option>
    </template>
</select>
