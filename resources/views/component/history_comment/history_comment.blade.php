<style>
    .note-editor {
        padding-left: 10px;
        border-radius: 30px !important;
    }

    .note-toolbar {
        padding: 0 !important;
        background: none;
    }

    .note-btn {
        border: 0;
    }

    .note-btn:hover {
        background: none;
        border: 0;
    }

    .note-statusbar {
        background: none !important;
        border-top: 0 !important;
    }
</style>
<div x-data="{tab: 'comment'}">
    {{--button tab--}}
    <div class="container d-flex tw-gap-x-4 mt-3">
        <a class="tw-no-underline hover:tw-text-green-500"
           :class="tab === 'comment' ? 'active-link' : 'inactive-link'"
           @click="tab = 'comment'"
        >
            Bình luận
        </a>
        <a class="tw-no-underline hover:tw-text-green-500"
           :class="tab === 'history' ? 'active-link' : 'inactive-link'"
           @click="tab = 'history'"
        >
            Lịch sử
        </a>
    </div>

    <div class="mt-3" style="border-top: 1px solid;">
        {{--comment--}}
        <div x-show="tab === 'comment'">
            <div class="d-flex flex-column justify-content-between overflow-auto custom-scroll" style="max-height: 85dvh"  id="historyComment">
                <div x-data="{user_login: {{ \Illuminate\Support\Facades\Auth::id() }}}">
                    {{-- Comment --}}
                    <div :key="comment">
                        <template x-for="comment in comments" :key="comment.id">
                            <div class="mt-3">
                                <div class="tw-flex tw-gap-x-2 align-items-center ">
                                    <img
                                        src="https://lh3.googleusercontent.com/a/ACg8ocJ-NELNG55xGTjMztdZpSLwO6SsJiKCfW1UluF-QjAddVaFSQ=s96-c"
                                        class="tw-w-10 tw-h-10 border tw-rounded-full">
                                    <div class="card border p-2 tw-w-full mb-0" style="background: #E0E4EA40;">
                                        <div>
                                            <span class="tw-font-bold" style="color: #2067B0;"
                                                  x-text="comment.user_created"></span>
                                            <span class="text-xs opacity-50 ml-2" x-text="comment.created_at"></span>
                                        </div>
                                        <span x-html="comment.message"></span>
                                        <div>
                                            <div class="p-2 d-flex flex-wrap tw-gap-x-2">
                                                <template x-for="(file, index) in comment.files">
                                                    <div
                                                        class="tw-truncate tw-shadow-xl p-2 tw-rounded-2xl border border-2" style="width: 30%;">
                                                        <div style="height: 5rem;">
                                                            <a x-data='{
                                                                isImage: file.file_url.toLowerCase().endsWith(".jpg")
                                                                || file.file_url.toLowerCase().endsWith(".png")
                                                                || file.file_url.toLowerCase().endsWith(".jpeg")
                                                            }' :href="'/uploads/'+file.file_url" class="tw-cursor-pointer" target="_blank">
                                                                <template x-if="isImage">
                                                                    <img :src="'/uploads/'+file.file_url" class="w-100 h-100">
                                                                </template>
                                                                <template x-if="!isImage">
                                                                    <img src="/images/file-icon.png" class="w-100 h-100">
                                                                </template>
                                                            </a>
                                                        </div>
                                                        <span style="text-align: center;font-size: 10px;"
                                                              x-text="file.file_name">
                                                        </span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tw-ml-12">
                                    <template x-if="+user_login === +comment.created_by">
                                        <input type="text" class="form-control"
                                               x-show="+id_comment_edit === +comment.id"
                                               x-model="message_edit"
                                               @keydown.enter="editComment()"
                                        >
                                    </template>
                                    <div class="d-flex tw-gap-x-3 opacity-50">
                                        <template x-if="+user_login !== +comment.created_by">
                                            <span class="tw-cursor-pointer" @click="replyComment(comment.user_created)">Trả lời</span>
                                        </template>
                                        <template x-if="+user_login === +comment.created_by">
                                            <div class="d-flex tw-gap-x-3">
                                                <span class="tw-cursor-pointer"
                                                      @click="handleEditComment(comment.id, comment.message)">Sửa</span>
                                                <span class="tw-cursor-pointer"
                                                      @click="deleteComment(comment.id)">Xóa</span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <hr>

                <div x-data="comment" class="tw-mb-4">
                    {{--input message--}}
                    <textarea x-data="summerNoteEditor()"></textarea>

                    {{--button action--}}
                    <div class="tw-relative">
                        <div class="d-flex tw-gap-x-4">
                            <span class="tw-text-gray-500 tw-cursor-pointer" @click="showUser=!showUser">@Nhắc đến</span>
                            <span class="tw-text-gray-500 tw-cursor-pointer" @click="showUpload = !showUpload"><i
                                    class="fa fa-upload"></i>Tập tin</span>
                            <span class="tw-text-gray-500 tw-cursor-pointer" @click="showIcon = true"><i
                                    class="fa fa-smile emoji"></i></span>
                        </div>

                        {{--select user nhac den--}}
                        <div class="tw-absolute tw-bottom-[9.5rem]">
                            @include('component.history_comment.select_user', [
                                'selected' => 'user_id',
                                'options' => 'listUser',
                                'open' => 'showUser'
                            ])
                        </div>
                    </div>

                    {{--action--}}
                    <div>
                        {{--emoji--}}
                        <emoji-picker id="emojiPicker" x-show="showIcon"
                                      style="position: absolute;right: 0;bottom: 12rem"
                                      @click.outside="showIcon = false"
                                      @emoji-click="comment_message += $event.detail.unicode; showIcon = false; unicode = $event.detail.unicode">
                        </emoji-picker>

                        {{--form upload--}}
                        <div x-show="showUpload" class="border border-1 tw-rounded-t-lg tw-rounded-b-3xl" style="background-color: #f8f9fa;">
                            {{--list file--}}
                            <div class="p-2 d-flex flex-wrap tw-gap-x-2">
                                <template x-for="(file, index) in files" :key="index">
                                    <div class="tw-relative tw-truncate tw-shadow-xl p-2 tw-rounded-2xl border border-2" style="width: 30%;">
                                        <div style="height: 7rem;">
                                            <!-- Nếu là ảnh, hiển thị preview -->
                                            <template x-if="file.type.startsWith('image/')">
                                                <img :src="file.preview" class="w-100 h-100">
                                            </template>

                                            <!-- Nếu là file khác, chỉ hiển thị tên file -->
                                            <template x-if="!file.type.startsWith('image/')">
                                                <img src="/images/file-icon.png" class="w-100 h-100">
                                            </template>
                                        </div>
                                        <span style="text-align: center;font-size: 10px;"
                                              x-text="file.name">
                                        </span>

                                        <!-- Nút xóa file -->
                                        <button @click="removeFile(index)" class="tw-absolute top-0 tw-right-0 border-0 tw-rounded-full">x</button>
                                    </div>
                                </template>
                            </div>

                            {{--input file--}}
                            <div class="border-top">
                                <div class="text-center" style="padding: 10px 70px 10px 70px;">
                                    <label class="tw-rounded-full tw-border-dashed tw-cursor-pointer tw-w-full m-0"
                                         style="border-color: rgba(82, 92, 105, .15);"
                                         for="fileInput"
                                    >
                                        Thả tập tin của bạn vào đây
                                    </label>
                                    <input class="form-control d-none" type="file" id="fileInput" multiple
                                           x-ref="fileInput" @change="handleFiles" accept="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex tw-gap-x-4 mt-2">
                        <button class="btn btn-sc" @click="sentComment(); resetInput = !resetInput">Gửi</button>
                        <button class="btn btn-light" @click="resetInput = !resetInput">Hủy bỏ</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lich su --}}
        <div x-show="tab === 'history'" class="list-group pr-2 overflow-auto custom-scroll mt-3" style="max-height: 83dvh" :key="history">
            <template x-for="log in logs">
                <div class="d-flex tw-gap-x-2">
                    <div class="tw-w-8 d-flex flex-column align-items-center">
                            <span class="text-primary border tw-rounded-full d-flex tw-bg-blue-100">
                                <i class="fa-regular fa-star tw-p-[5px] tw-text-blue-600" style="color: #74C0FC"></i>
                            </span>
                        <div class="border-start border-2 flex-grow-1"></div>
                    </div>
                    <div class="border rounded p-2 tw-bg-zinc-100 mb-3 flex-grow-1">
                        <p class="mb-1 text-muted small"
                           x-text="log.created_at"
                        ></p>
                        <p class="mb-0">
                            <a href="#" class="text-primary fw-bold" x-text="log.created_by"></a>
                            <span x-text="log.desc"></span>
                        </p>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
<script>
    function comment() {
        return {
            resetInput: false,
            unicode: null,
            showIcon: false,
            showUpload: false,
            showUser: false,
            user_id: null,

            init() {
                this.$watch('showUpload', (value) => this.scrollBottom())
            },
        }
    }

    function summerNoteEditor() {
        return {
            el: null,
            init() {
                this.el = this.$el
                this.initSummer();
                this.$watch('resetInput', (value) => {
                    this.resetInputSummer();
                });
                this.$watch('unicode', (value) => {
                    if(value) {
                        let editor = $(this.el);
                        let currentContent = editor.summernote('code'); // Lấy nội dung hiện tại
                        editor.summernote('code', currentContent + value); // Cộng thêm nội dung mới
                        this.unicode = null
                    }
                });
                this.$watch('user_id', (value) => {
                    if(value) {
                        const user = this.listUser.find(option => option.id === value);
                        const userName = `<a href='#' target='_blank'>${user.name}</a>`;
                        let editor = $(this.el);
                        let currentContent = editor.summernote('code'); // Lấy nội dung hiện tại
                        editor.summernote('code', currentContent + userName); // Cộng thêm nội dung mới
                        this.user_id = null
                    }
                });
            },

            initSummer() {
                const summerNote = $(this.el);
                summerNote.summernote({
                    height: 40,
                    placeholder: 'Nhập nội dung...',
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['insert', ['link']],
                    ],
                    callbacks: {
                        onChange: (contents) => {
                            this.comment_message = contents;
                        }
                    }
                }).summernote('code', '');

                summerNote.on('summernote.keydown', (we, e) => {
                    if (e.keyCode === 13 && !e.shiftKey) {
                        e.preventDefault();
                        this.sentComment();
                        this.resetInputSummer();
                    }
                });
            },

            resetInputSummer() {
                $(this.el).summernote('code', '');
                this.showUpload = false
                this.$refs.fileInput.value = '';
                this.$refs.fileInput.dispatchEvent(new Event('change'));
            },
        }
    }
</script>
