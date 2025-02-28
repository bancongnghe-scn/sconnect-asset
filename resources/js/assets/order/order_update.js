document.addEventListener('alpine:init', () => {
    Alpine.data('order_update', (id) => ({
        async init() {
            this.getListUser()
            await this.findOrder(id)
            this.getShoppingAssetOrder()
            this.$watch('data', (value) => console.log(value))
        },

        data: [],
        listUser: [],
        listAssetType: [],
        listOrganization: [],

        async update() {
            this.loading = true
            try {
                console.log(this.data)
                const response = await window.apiUpdateOrder(this.data)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                toast.success('Cập nhật đơn hàng thành công')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

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
                if (this.data.shopping_assets_order.length > 0) {
                    this.getListAssetType()
                    this.getListOrganization()
                }
            }
        },

        async getListAssetType() {
            try {
                const response = await window.apiGetAssetType({})
                if (response.success) {
                    this.listAssetType = response.data.data
                    return
                }
                toast.error('Lấy danh sách loại tài sản thất bại !')
            } catch (e) {
                toast.error(e)
            } finally {
            }
        },

        async getListOrganization() {
            try {
                const response = await window.apiGetOrganization({})
                if (response.success) {
                    this.listOrganization = response.data.data
                    return
                }
                toast.error('Lấy danh sách đơn vị thất bại !')
            } catch (e) {
                toast.error(e)
            } finally {
            }
        },
    }))
})
