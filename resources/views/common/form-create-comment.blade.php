<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@vite(['resources/css/comment.css'])

<div class="list-comments-body"></div>

<div class="comment-box">
    <div class="user-comment">
        <div class="row">
            <div class="col-2">
                <div class="user-avatar propose__box-avatar rounded-circle">
                    <img 
                        onerror="this.src='https://office.sconnect.com.vn/images/avatar-default.png'" 
                        class="w-100 h-100 rounded-circle object-fit" 
                        src="{{ optional(Auth::user())->avatar 
                            ? (Str::contains(optional(Auth::user())->avatar, '/uploads/') 
                                ? 'https://office.sconnect.com.vn' . Auth::user()->avatar 
                                : Auth::user()->avatar) 
                            : 'https://office.sconnect.com.vn/images/avatar-default.png' }}" 
                        alt="User Avatar">
                </div>
            </div>
            <div class="col">
                <div class="form-comment">
                    <div data-url="{{ route('comment-create') }}" id="form-create-comment">
                        <input type="text" class="filter-create" name="type" value="{{ $comment_type }}" hidden>
                        <input type="text" class="filter-create" id="target_id" name="target_id" value="{{ $target_id }}" hidden>

                        <textarea placeholder="Thêm bình luận" type="text" name="comment" id="comment-input" class="form-control comment-input filter-create" style="resize: none;"></textarea>

                        <span class="required-error error"></span>
                        <div class="bottom-form" style="display: none">
                            <div class="form-action">
                                <span class="tagUser">
                                    <div class="dropup">
                                        <a  href="#" class="nav-link btn-click-tagUser" id="" data-toggle="dropdown">
                                            <span class="btn-action"> @ Nhắc đến </span>
                                        </a>
                                        <div class="dropdown-menu">
                                            <div class="dropdown-list-user">
                                                <div class="search-user">
                                                    <input type="text" class="form-control search-user-tag" placeholder="Tìm kiếm">
                                                </div>
                                                <hr>
                                                <div class="list-user"></div>
                                            </div>
                                        </div>
                                    </div>
                                </span>

                                <span class="btn-action uploadFile"> <i class="fa fa-upload"></i>Tập tin</span>

                                <span>
                                    <div class="dropup">
                                        <a href="#" class="nav-link" data-toggle="dropdown">
                                            <span class="btn-action"><i class="fa fa-smile emoji"></i></span>
                                        </a>
                                        <div class="dropdown-menu">
                                            <emoji-picker emoji-version="15.0" class="light"></emoji-picker>
                                        </div>
                                    </div>
                                </span>
                            </div>
                            <div class="box-files">
                                <div class="drag-white-space">
                                    <span>Thả tập tin ở đây</span>
                                </div>
                                <div class="list-file"></div>

                                <div class="btn-upload">
                                    <div class="ui-btn-upload" >
                                        <div class="drop-click-upload">Thả tập tin của bạn vào đây</div>
                                        <input type="file" class="upload-input" multiple hidden>
                                        <input type="file" class="upload-files-list" name="files[]" multiple hidden>
                                    </div>
                                </div>
                            </div>
                            <div class="form-btn">
                                <button class="btn btn-success mr-4" id="btn-submit-comment" type="button">Gửi</button>
                                <button id="cancel-comment" class="btn btn-light" type="button">Hủy bỏ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    var clsComment = null;
    const cmTypeCreate = @json(\App\Support\Constants\SOfficeConstant::CM_CREATE_TYPE),
          cmTypeUpdate = @json(\App\Support\Constants\SOfficeConstant::CM_EDIT_TYPE),
          cmTypeDel = @json(\App\Support\Constants\SOfficeConstant::CM_DEL_TYPE),
          cmTypeReact = @json(\App\Support\Constants\SOfficeConstant::CM_REACTION_TYPE);
    const cmProposalType = @json(\App\Support\Constants\SOfficeConstant::TYPE_PLAN_LIQUIDATION);
    const fileTypesAllow = @json(\App\Support\Constants\SOfficeConstant::FILE_TYPE_ALLOW),
          fileSizeAllow = @json(\App\Support\Constants\SOfficeConstant::FILE_SIZE_ALLOW),
          fileTypePath = @json(\App\Support\Constants\SOfficeConstant::FILE_TYPE_PATH);
    const cmReactTypeLike = @json(\App\Support\Constants\SOfficeConstant::CM_REACT_LIKE);
    const baseUrl = window.location.origin,
          commentListUrl = "{{ route('comment-list') }}",
          commentReactUrl = "{{ route('comment-react') }}",
          commentDeleteUrl = "{{ route('comment-del', ['id' => ':id']) }}",
          getCommentEditUrl = "{{ route('get-comment-edit') }}",
          getUserTagUrl = "{{ route('comment-tag-list-user') }}",
          getMoreComments = "{{ route('comment-list-more') }}",
          getListReactUserUrl = "{{ route('get-list-react-user')}}";
</script>
@vite(['resources/js/assets/comment.js'])
