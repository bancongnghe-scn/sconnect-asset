document.addEventListener('alpine:init', () => {
    Alpine.data('import_warehouse_list', () => ({
        init() {
            this.getListOrder()
            window.initSelect2Modal('modalUI')
            this.$watch('data.order_ids', (newValue, oldValue) => {
                this.handleGetImportWarehouseAsset(newValue, oldValue)
            })
        },

        //dataTable
        dataTable: [],
        columns: {
            code: 'Mã',
            name: 'Tên phiếu',
            created_at: 'Thời gian nhập',
            created_by: 'Người nhập',
            status: 'Trạng thái',
        },
        showAction: {
            view: false,
            edit: true,
            remove: true
        },
        selectedRow: [],
        listUser: [],
        listOrders: [],
        //pagination
        totalPages: null,
        currentPage: 1,
        from: 0,
        to: 0,
        total: 0,
        limit: 10,

        //data
        filters: {},
        data: {
            id: null,
            code: null,
            name: null,
            description: null,
            order_ids: [],
            shopping_assets: []
        },
        title: null,
        action: null,
        id: null,

        //methods
        async create() {
            this.loading = true
            try {
                const response = await window.apiCreateImportWarehouse(this.data)
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

        async getListOrder() {
            this.loading = true
            try {
                const response = await window.apiGetListOrder({status: ORDER_STATUS_COMPLETE})
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

        async getAssetForImportWarehouse(order_ids) {
            this.loading = true
            try {
                const response = await window.apiGetAssetForImportWarehouse(order_ids)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }

                return response.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async handleShowModalUI(action, id = null) {
            this.loading = true
            this.action = action
            if (action === 'create') {
                this.title = 'Thêm mới'
                this.resetData()
                window.generateShortCode().then(code => {
                    this.data.code = 'PN_'+code
                })
            } else {
                this.title = 'Cập nhật'
                this.id = id
                const response = await window.apiShowAssetType(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.data = response.data.data
            }
            this.loading = false
            $('#modalUI').modal('show');
        },

        async handleGetImportWarehouseAsset(newValue, oldValue) {
            if (newValue.length === 0) {
                this.data.shopping_assets = []
                return
            }

            const orderRemove = oldValue.filter(item => !newValue.includes(item));
            if (orderRemove.length > 0) {
                this.data.shopping_assets = this.data.shopping_assets.filter((item) => !orderRemove.includes((String(item.order_id))))
            }
            const orderNew = newValue.filter(item => !oldValue.includes(item));
            if (orderNew.length > 0) {
                const data = await this.getAssetForImportWarehouse(orderNew)
                this.data.shopping_assets = [...this.data.shopping_assets, ...data]
            }
        },

        changePage(page) {
            this.filters.page = page
            this.list(this.filters)
        },

        changeLimit() {
            this.filters.limit = this.limit
            this.list(this.filters)
        },

        resetData() {
            this.data = {
                id: null,
                code: null,
                name: null,
                description: null,
                order_ids: [],
                shopping_assets: []
            }
        },

        reloadPage() {
            this.resetFilters()
            this.list(this.filters)
        },
    }));
});
