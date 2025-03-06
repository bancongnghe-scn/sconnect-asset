document.addEventListener('alpine:init', () => {
    Alpine.data('order_detail', (id) => ({
        async init() {
            this.getListUser()
            this.getListIndustry()
            await this.findOrder()
            this.getShoppingAssetOrder()
        },

        data: [],
        listUser: [],
        listAssetType: [],
        listOrganization: [],
        listIndustry: [],

        async findOrder(){
            this.loading = true
            try {
                const response = await window.apiFindOrder(id)
                if (response.success) {
                    this.data = response.data
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListUser(){
            try {
                const response = await window.apiGetUser({})
                if (response.success) {
                    this.listUser = response.data.data
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
            }
        },

        async getShoppingAssetOrder() {
            try {
                let response = await window.apiGetShoppingAssetOrder({
                    order_id: [id]
                })

                if (response.success) {
                    this.data.shopping_assets_order = response.data
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
            }
        },

        async getListIndustry() {
            this.loading = true
            const response = await window.apiGetIndustry()
            if (response.success) {
                this.listIndustry = response.data.data
            } else {
                toast.error('Lấy danh sách ngành hàng thất bại !')
            }
            this.loading = false
        },
    }))
})
