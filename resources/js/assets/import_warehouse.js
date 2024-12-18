document.addEventListener('alpine:init', () => {
    Alpine.data('import_warehouse_list', () => ({
        init() {
            this.list(this.filters)
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
        listUser: [],
        listOrders: [],
        listOrdersDelivered: [],

        //pagination
        totalPages: null,
        currentPage: 1,
        from: 0,
        to: 0,
        total: 0,
        limit: 10,

        //data
        filters: {
            'code_name': null,
            'status': null,
            'created_at': null,
            'created_by': null,
            'page': 1,
            'limit': 10
        },
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
        async list(filters) {
            this.loading = true
            try {
                const response = await window.apiGetImportWarehouse(filters)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                const data = response.data
                this.dataTable = data.data
                this.totalPages = data.last_page
                this.currentPage = data.current_page
                this.total = data.total ?? 0
                this.from = data.from ?? 0
                this.to = data.to ?? 0
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async create() {
            this.loading = true
            try {
                const response = await window.apiCreateImportWarehouse(this.data)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.list({page: 1, limit: 10})
                toast.success('Tạo phiếu nhập thành công')
                $('#modalUI').modal('hide')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async update() {
            this.loading = true
            try {
                const response = await window.apiUpdateImportWarehouse(this.id, this.data)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.list(this.filters)
                toast.success('Cập nhật phiếu nhập thành công')
                $('#modalUI').modal('hide')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async remove() {
            this.loading = true
            try {
                const response = await window.apiDeleteImportWarehouse(this.id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }

                this.dataTable = this.dataTable.filter((item) => item.id !== this.id)
                toast.success('Xóa phiếu nhập thành công')
                $('#modalConfirmDelete').modal('hide')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async completeImportWarehouse() {
            this.loading = true
            try {
                let response = null
                let id = null
                if (this.action === 'create') {
                    response = await window.apiCreateImportWarehouse(this.data)
                    if (!response.success) {
                        toast.error(response.message)
                        return
                    }
                    id = response.data.id
                } else {
                    const response = await window.apiUpdateImportWarehouse(this.id, this.data)
                    if (!response.success) {
                        toast.error(response.message)
                        return
                    }
                    id = this.id
                }

                response = await window.apiCompleteImportWarehouse(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }

                this.list(this.filters)
                toast.success('Hoàn thành phiếu nhập kho thành công')
                $('#modalUI').modal('hide')
                $('#modalConfirmComplete').modal('hide')
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
                this.listOrdersDelivered = this.listOrders.filter((item) => +item.status === ORDER_STATUS_DELIVERED)
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
                if (action === 'update') {
                    this.title = 'Cập nhật'
                } else {
                    this.title = 'Chi tiết'
                }
                this.id = id
                const response = await window.apiGetInfoImportWarehouse(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                const data = response.data
                this.data.id = data.id
                this.data.code = data.code
                this.data.name = data.name
                this.data.description = data.description
                this.data.order_ids = data.order_ids
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

        confirmRemove(id) {
           this.id = id
           $('#modalConfirmDelete').modal('show')
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
            this.filters = {
                'code_name': null,
                'status': null,
                'created_at': null,
                'created_by': null,
                'page': 1,
                'limit': 10
            }
            this.list(this.filters)
        },
    }));
});
