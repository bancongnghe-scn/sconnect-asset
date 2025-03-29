document.addEventListener('alpine:init', () => {
    Alpine.data('shopping_arise_organization_update', (id) => ({
        init() {
            this.findShoppingArise()
            this.getListAssetType()
            this.getListJobOfManager()
        },

        list_asset_type: [],
        list_job: [],
        data: {
            name: null,
            assets: []
        },

        async findShoppingArise() {
            this.loading = true
            try {
                const response = await window.apiFindShoppingArise(id)
                if (response.success) {
                    this.data = response.data.data
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async updateShoppingArise() {
            this.loading = true
            try {
                const response = await window.apiUpdateShoppingArise(this.data, id)
                if (response.success) {
                    toast.success('Cập nhật đề xuất mua sắm thành công !')
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async sendShoppingArise() {
            this.loading = true
            try {
                const response = await window.apiSendShoppingArise(id)
                if (response.success) {
                    toast.success('Gửi duyệt thành công !')
                    $('#modalConfirmSend').modal('hide')
                    window.location.href = `/shopping-arise/list`
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListAssetType() {
            this.loading = true
            try {
                const response = await window.apiGetAssetType({})
                if (response.success) {
                    this.list_asset_type = response.data.data
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListJobOfManager(){
            this.loading = true
            try {
                const response = await window.apiGetListJobOfManager()
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.list_job = response.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        addRowAsset() {
            this.data.assets.push({
                asset_type_id: null,
                quantity_registered: null,
                job_id: null,
                receiving_time: null,
                description: null
            })
        },
    }))
})
