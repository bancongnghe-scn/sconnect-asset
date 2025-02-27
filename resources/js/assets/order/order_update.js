document.addEventListener('alpine:init', () => {
    Alpine.data('order_update', () => ({
        async update() {
            this.loading = true
            try {
                const response = await window.apiUpdateOrder(this.data)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                toast.success('Cập nhật đơn hàng thành công')
                $('#modalUpdate').modal('hide')
                this.list(this.filters)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async findOrder(id){
            this.loading = true
            try {
                const response = await window.apiFindOrder(id)
                if (response.success) {
                    this.data = response.data
                    this.data.delivery_date = formatDateVN(this.data.delivery_date)
                    this.data.payment_time = formatDateVN(this.data.payment_time)
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },
    }))
})
