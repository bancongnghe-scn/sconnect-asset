<select class="form-control select2"
        @if(isset($multiple)) multiple="multiple" @endif
        @if(isset($id)) id="{{$id}}" @endif
        @if(isset($placeholder)) data-placeholder="{{$placeholder}}" @endif
        @if(isset($model)) x-model="{{$model}}" @endif
        @if(isset($disabled)) disabled @endif
    <template x-for="value in data" :key="value.id">
        <option :value="value.id" x-text="value.name"></option>
    </template>
</select>
