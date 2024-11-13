document.addEventListener('alpine:init', () => {
    Alpine.data('history_comment', () => ({
        //data
        activeLink: {
            history: true,
            comment: false
        },
        comment_message: null,
        message_edit: null,
        id_comment_edit: null,

        //methods
        async sentComment(reply = null) {
            const param = {
                type: TYPE_COMMENT_SHOPPING_PLAN,
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

        async deleteComment(id) {
            const response = await window.apiDeleteComment(id)
            if (response.success) {
                this.comments = this.comments.filter(item => +item.id !== +id);
                return
            }
            toast.error(response.message)
        },

        async editComment() {
            const response = await window.apiEditComment({id: this.id_comment_edit, message: this.message_edit})
            if (response.success) {
                let object = this.comments.find(obj => obj.id === this.id_comment_edit);

                if (object) {
                    object.message = this.message_edit
                }

                this.id_comment_edit = null
                return
            }
            toast.error(response.message)
        },

        handleEditComment(id, message) {
            this.id_comment_edit = id
            this.message_edit = message
        },

        handleShowActive(active) {
            for (const activeKey in this.activeLink) {
                this.activeLink[activeKey] = false
            }

            this.activeLink[active] = true
        },

        replyComment(username) {
            this.comment_message = `@${username} `;
            this.$refs.input_message.focus();
        }
    })
)})
