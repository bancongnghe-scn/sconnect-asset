document.addEventListener('alpine:init', () => {
    Alpine.data('import_warehouse_detail', (id) => ({
        init() {
            this.getListOrder()
            this.getInfoImportWarehouse()
        },

        data: {
            id: null,
            code: null,
            name: null,
            description: null,
            order_ids: [],
            shopping_assets: []
        },
        listOrders: [],

        async getInfoImportWarehouse() {
            this.loading = true
            try {
                const response = await window.apiGetInfoImportWarehouse(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }

                this.data = response.data
                this.getAssetForImportWarehouse(this.data.order_ids)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListOrder() {
            this.loading = true
            try {
                const response = await window.apiGetListOrder({status: [ORDER_STATUS_DELIVERED, ORDER_STATUS_WAREHOUSED]})
                if (!response.success) {
                    toast.error(response.message)
                    return
                }

                this.listOrders = response.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getAssetForImportWarehouse(orderIds) {
            this.loading = true
            try {
                const response = await window.apiGetAssetForImportWarehouse(orderIds)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }

                this.data.shopping_assets = response.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },
    }))
})
