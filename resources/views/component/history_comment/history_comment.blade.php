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
<div>
    <div class="container d-flex tw-gap-x-4 mt-3">
        <a class="tw-no-underline hover:tw-text-green-500"
           :class="activeLink.comment ? 'active-link' : 'inactive-link'"
           @click="handleShowActive('comment')"
        >
            Bình luận
        </a>
        <a class="tw-no-underline hover:tw-text-green-500"
           :class="activeLink.history ? 'active-link' : 'inactive-link'"
           @click="handleShowActive('history')"
        >
            Lịch sử
        </a>
    </div>

    <div class="mt-3 d-flex flex-column justify-content-between" style="border-top: 1px solid; max-height: 83dvh">
        <div class="overflow-auto custom-scroll mt-3"
             x-data="{user_login: {{ \Illuminate\Support\Facades\Auth::id() }}}"
             id="historyComment"
        >
            {{-- Lich su --}}
            <div class="list-group pr-2" x-show="activeLink.history" :key="history">
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
            </div  >

            {{-- Comment --}}
            <div x-show="activeLink.comment" :key="comment">
                <template x-for="comment in comments" :key="comment.id">
                    <div class="mt-3">
                        <div class="tw-flex tw-gap-x-2 align-items-center ">
                            <img src="https://lh3.googleusercontent.com/a/ACg8ocJ-NELNG55xGTjMztdZpSLwO6SsJiKCfW1UluF-QjAddVaFSQ=s96-c"
                                 class="tw-w-10 tw-h-10 border tw-rounded-full">
                            <div class="card border p-2 tw-w-full mb-0" style="background: #E0E4EA40;">
                                <div>
                                    <span class="tw-font-bold" style="color: #2067B0;" x-text="comment.user_created"></span>
                                    <span class="text-xs opacity-50 ml-2" x-text="comment.created_at"></span>
                                </div>
                                <span x-html="comment.message"></span>
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
                                        <span class="tw-cursor-pointer" @click="handleEditComment(comment.id, comment.message)">Sửa</span>
                                        <span class="tw-cursor-pointer" @click="deleteComment(comment.id)">Xóa</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <hr>

        <div x-show="activeLink.comment" x-data="{resetInput: false, unicode: null, showIcon: false, showUser: false, userId: null}">
            <textarea
                x-data="{
                    init() {
                        this.$watch('showModal', (value) => {
                            this.initSummer();
                        });
                        this.$watch('resetInput', (value) => {
                            this.resetInputSummer();
                        });
                        this.$watch('unicode', (value) => {
                            if(value) {
                                let editor = $($el);
                                let currentContent = editor.summernote('code'); // Lấy nội dung hiện tại
                                editor.summernote('code', currentContent + value); // Cộng thêm nội dung mới
                                this.unicode = null
                            }
                        });
                    },

                    initSummer() {
                        const summerNote = $($el);
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
                        $($el).summernote('code', '');
                    },
                }"
            ></textarea>

            <div class="d-flex tw-gap-x-4">
                <span class="tw-text-gray-500 tw-cursor-pointer" @click="showUser = true">@Nhắc đến</span>
                <span class="tw-text-gray-500 tw-cursor-pointer"><i class="fa fa-upload"></i>Tập tin</span>
                <span class="tw-text-gray-500 tw-cursor-pointer" @click="showIcon = true"><i class="fa fa-smile emoji"></i></span>
            </div>

            <div>
                <emoji-picker id="emojiPicker" x-show="showIcon"
                              style="position: absolute;right: 0;bottom: 12rem"
                              @click.outside="showIcon = false"
                              @emoji-click="comment_message += $event.detail.unicode; showIcon = false; unicode = $event.detail.unicode">
                </emoji-picker>
                <div>
                    @include('common.uploadFile.form_upload_file')
                </div>
            </div>

            <div class="d-flex tw-gap-x-4 mt-2">
                <button class="btn btn-sc" @click="sentComment()">Gửi</button>
                <button class="btn btn-light" @click="resetInput = !resetInput">Hủy bỏ</button>
            </div>
        </div>
    </div>
</div>
<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>

