<div class="input-group">
    <input type="text" class="form-control datepicker" id="{{$id}}"
           placeholder="{{$placeholder}}" autocomplete="off" @if(isset($model)) x-model="{{ $model }}" @endif>
    <span class="input-group-text">
        <i class="fa-regular fa-calendar-days"></i>
    </span>
</div>
