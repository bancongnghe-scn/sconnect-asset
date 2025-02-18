<div x-data="history_comment">
    @include('component.history_comment.history_comment')
</div>

@vite([
    'resources/js/assets/history_comment/history_comment_shopping_plan_organization.js',
    'resources/js/assets/api/apiComment.js',
    'resources/js/assets/api/log/apiLog.js'
])
