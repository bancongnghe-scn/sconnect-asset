document.addEventListener('alpine:init', () => {
    Alpine.data('history_comment_company', (id) => ({
        init() {
            this.getLogByRecordId()
            this.listComment()
            this.handleComment()
            this.scrollBottom()
            this.getListUser()
        },
        //data
        message_edit: null,
        id_comment_edit: null,
        comment_message: null,
        user_id: null,
        logs: [],
        comments: [],
        listUser: [],

        //methods
        async sentComment() {
            if (!this.comment_message) {
                return
            }
            const param = {
                type: TYPE_COMMENT_SHOPPING_PLAN_COMPANY,
                target_id: id,
                message: this.comment_message,
            }
            const response = await window.apiSentComment(param)
            if (response.success) {
                this.comment_message = null
                return
            }
            toast.error(response.message)
        },

        async listComment() {
            const param = {
                type: TYPE_COMMENT_SHOPPING_PLAN_COMPANY,
                target_id: id,
            }
            const response = await window.apiGetComment(param)
            if (response.success) {
                this.comments = response.data.data
                return
            }
            toast.error(response.message)
        },

        async getLogByRecordId() {
            const response = await window.getShoppingPlanLogByRecordId(id)
            if (response.success) {
                this.logs = response.data.data
                return
            }

            toast.error('Lấy lịch sử của kế hoạch thất bại !')
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

        async getListUser() {
            this.loading = true
            const response = await window.apiGetUser({})
            if (response.success) {
                this.listUser = response.data.data
            } else {
                toast.error(response.message)
            }
            this.loading = false
        },

        handleEditComment(id, message) {
            this.id_comment_edit = id
            this.message_edit = message
        },

        handleComment() {
            window.Echo.channel('channel_shopping_plan_' + id)
                .listen('.ShoppingPlanCommentEvent', (e) => {

                    this.comments.push(e)
                }).error((error) => {
                alert(error)
            });
        },

        replyComment(username) {
            this.comment_message = `@${username} `;
            this.$refs.input_message.focus();
        },

        scrollBottom() {
            const scroll = $('#historyComment').get(0);
            if (scroll) {
                scroll.scrollTop = scroll.scrollHeight; // Cuộn xuống dưới
            }
        },
    }))
})
