<div>
    <div class="container d-flex tw-gap-x-4 mt-3">
        <a href="#" class="tw-no-underline hover:tw-text-green-500"
           :class="activeLink.history ? 'active-link' : 'inactive-link'"
           @click="handleShowActive('history')"
        >
            Lịch sử
        </a>
        <a href="#" class="tw-no-underline hover:tw-text-green-500"
           :class="activeLink.comment ? 'active-link' : 'inactive-link'"
           @click="handleShowActive('comment')"
        >
            Bình luận
        </a>
    </div>
    <div class="mt-3" style="border-top: 1px solid">
        <div class="container mt-3" x-show="activeLink.history">
            <template x-for="log in logs">
                <p x-text="`${log.created_at} - ${log.created_by} : ${log.desc}`"></p>
            </template>
        </div>
        <div class="container mt-3" id="comment" x-show="activeLink.comment">
            <div>
                <template x-for="comment in comments">
                    <div class="card border p-2" style="background: #E0E4EA40;">
                        <div>
                            <span class="tw-font-bold" x-text="comment.user_name">Lê Mai Linh</span>
                            <span x-text="comment.created_at"></span>
                        </div>
                        <span x-text="comment.message"></span>
                    </div>
                </template>
            </div>

            <div class="input-group border rounded">
                <input type="text" class="form-control border-0" placeholder="Thêm bình luận...">
                <button class="btn" type="button">
                    <i class="fas fa-paper-plane color-sc"></i>
                </button>
            </div>
        </div>
    </div>
</div>
