<div x-data="history_comment">
    <div class="container d-flex tw-gap-x-4 mt-3">
        <a class="tw-no-underline hover:tw-text-green-500"
           :class="activeLink.history ? 'active-link' : 'inactive-link'"
           @click="handleShowActive('history')"
        >
            Lịch sử
        </a>
        <a class="tw-no-underline hover:tw-text-green-500"
           :class="activeLink.comment ? 'active-link' : 'inactive-link'"
           @click="handleShowActive('comment')"
        >
            Bình luận
        </a>
    </div>
    <div class="mt-3 tw-h-[70dvh]" style="border-top: 1px solid">
        <div class="overflow-y-scroll custom-scroll mt-3" x-show="activeLink.history">
            <div class="container">
                <template x-for="log in logs">
                    <p x-text="`${log.created_at} - ${log.created_by} : ${log.desc}`"></p>
                </template>
            </div>
        </div>
        <div class="mt-3" id="comment" x-show="activeLink.comment">
            <div class="overflow-y-scroll custom-scroll tw-h-[65dvh]">
                <div class="container" x-data="{user_login: {{\Illuminate\Support\Facades\Auth::id()}}}">
                    <template x-for="comment in comments" :key="comment.id">
                        <div class="mb-3">
                            <div class="tw-flex tw-gap-x-2 align-items-center ">
                                <img src="https://lh3.googleusercontent.com/a/ACg8ocJ-NELNG55xGTjMztdZpSLwO6SsJiKCfW1UluF-QjAddVaFSQ=s96-c"
                                     class="tw-w-10 tw-h-10 border tw-rounded-full">
                                <div class="card border p-2 tw-w-full mb-0" style="background: #E0E4EA40;">
                                    <div>
                                        <span class="tw-font-bold" style="color: #2067B0;" x-text="comment.user_created"></span>
                                        <span class="text-xs opacity-50 ml-2" x-text="comment.created_at"></span>
                                    </div>
                                    <span x-text="comment.message"></span>
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

            <div class="container">
                <div class="input-group border rounded mt-3">
                    <input type="text" class="form-control border-0" placeholder="Thêm bình luận..."
                           x-model="comment_message"
                           x-ref="input_message"
                           @keydown.enter="sentComment()">
                    <button class="btn" type="button" @click="sentComment()">
                        <i class="fas fa-paper-plane color-sc"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@vite([
    'resources/js/assets/history_comment/history_comment.js',
    'resources/js/assets/api/apiShoppingPlanLog.js',
    'resources/js/assets/api/apiComment.js',
])
