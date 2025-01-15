document.addEventListener('alpine:init', () => {
    Alpine.data('comment_shopping_plan', () => ({
            init() {
                this.$watch('id', (newValue, oldValue) => {
                    if (newValue !== null) {
                        this.listComment()
                        this.handleComment()
                    }
                });
            },

            //data
            comments: [],
            comment_message: null,

            async sentComment(reply = null) {
                const param = {
                    type: TYPE_COMMENT_SHOPPING_PLAN_COMPANY,
                    target_id: this.id,
                    message: this.comment_message,
                    reply: reply
                }
                const response = await window.apiSentComment(param)
                this.comment_message = null
                if (response.success) {
                    return
                }
                toast.error(response.message)
            },

            async listComment() {
                const param = {
                    type: TYPE_COMMENT_SHOPPING_PLAN_COMPANY,
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
