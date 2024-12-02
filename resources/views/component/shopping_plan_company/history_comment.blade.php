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
    <div class="mt-3 tw-h-[73dvh] overflow-y-scroll custom-scroll" style="border-top: 1px solid" x-show="activeLink.history">
        <div class="container mt-3">
            <div class="list-group">
                <template x-for="log in logs">
                    <div class="d-flex tw-gap-x-2">
                        <div class="tw-w-8 d-flex flex-column align-items-center">
                            <span class="text-primary border tw-rounded-full d-flex tw-bg-blue-100">
                                <i class="fa-regular fa-star tw-p-[5px] tw-text-blue-600" style="color: #74C0FC"></i>
                            </span>
                            <div class="border-start border-2 flex-grow-1"></div>
                        </div>
                        <div class="w-auto border rounded p-2 tw-bg-zinc-100 mb-3">
                            <p class="mb-1 text-muted small" x-text="log.created_at"></p>
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
    <div class="mt-3" style="border-top: 1px solid" x-show="activeLink.comment">
        <div class="mt-3 container" id="comment">
            <div class="tw-h-[61dvh] xl:tw-h-[65dvh] overflow-y-scroll custom-scroll">
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
@vite([
    'resources/js/assets/history_comment/history_comment.js',
    'resources/js/assets/api/apiShoppingPlanLog.js',
    'resources/js/assets/api/apiComment.js',
])
