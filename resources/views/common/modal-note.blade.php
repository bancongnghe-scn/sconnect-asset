<div class="modal fade"
     @if(isset($id)) id="{{$id}}" @endif
     tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{$title ?? 'Lý do từ chối'}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea class="form-control tw-h-40"
                          placeholder="{{$placeholder ?? 'Nhập lý do từ chối'}}"
                          @if(isset($model)) x-model="{{$model}}" @endif
                >
                </textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="$dispatch('ok')" type="button" class="btn btn-sc">Gửi</button>
            </div>
        </div>
    </div>
</div>
