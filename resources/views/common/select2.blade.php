<select class="form-select select2"
        @if(isset($model)) x-model="{{$model}}" @endif
        @if(isset($multiple)) multiple="multiple" @endif
        @if(isset($id)) id="{{$id}}" @endif
        @if(isset($placeholder) && isset($multiple)) data-placeholder="{{$placeholder}}" @endif
        @if(isset($disabled)) disabled @endif
>
    @if(!isset($multiple))
        <option disabled>{{$placeholder ?? 'Chọn ...'}}</option>
    @endif
    <template x-for="value in data" :key="value.id">
        <option :value="value.id" x-text="value.name"></option>
    </template>
</select>


