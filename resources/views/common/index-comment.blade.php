@if($oldComments > 0)
    <div class="show-hide-more-comment" style="margin-bottom: 5px;">
        <span class="btn-show-hide btn-show-more" data-elementid="{{$elementId}}" data-count="{{$oldComments}}">Các bình luận trước ({{ $oldComments }})</span>
        <span class="btn-show-hide btn-hide" style="display: none">Ẩn bình luận</span>
    </div>
    <div class="old-comments-list" style="display: none">
       
    </div>
@endif

@include('comment.list-comment')
