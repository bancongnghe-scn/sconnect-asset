<select class="form-control select2"
        @if(isset($multiple)) multiple="multiple" @endif
        @if(isset($id)) id="{{$id}}" @endif
        @if(isset($placeholder)) data-placeholder="{{$placeholder}}" @endif>
    <template x-for="(value, key) in data">
        <option :value="key" x-text="value"></option>
    </template>
</select>
