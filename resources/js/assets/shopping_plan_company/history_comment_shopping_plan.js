document.addEventListener('alpine:init', () => {
    Alpine.data('history_comment_shopping_plan', () => ({
            init() {
                this.getShoppingPlanLogByRecordId()
                this.listComment()
                this.handleComment()
            },

            //data
            logs: [],
            comments: [],

            async getShoppingPlanLogByRecordId() {
                const response = await window.getShoppingPlanLogByRecordId(this.id)
                if (response.success) {
                    this.logs = response.data.data
                    return
                }

                toast.error('Lấy lịch sử của kế hoạch thất bại !')
            },

            async listComment(reply = null) {
                const param = {
                    type: TYPE_COMMENT_SHOPPING_PLAN,
                    target_id: this.id,
                }
                const response = await window.apiGetComment(param)
                if (response.success) {
                    this.comments = response.data.data
                    return
                }
                toast.error(response.message)
            },

            handleComment() {
                window.Echo.channel('channel_shopping_plan_' + this.id)
                    .listen('.ShoppingPlanCommentEvent', (e) => {
                        this.comments.push(e)
                    }).error((error) => {
                    alert(error)
                });
            },
        })
    )
})
