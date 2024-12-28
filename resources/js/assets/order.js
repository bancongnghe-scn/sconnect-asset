document.addEventListener('alpine:init', () => {
    Alpine.data('order', () => ({
        async init() {
            this.list(this.filters)
            this.getListUser()
            this.watch()
            this.watchFilters()
        },

        //dataTable
        dataTable: [],
        columns: {
            name: 'Tên đơn hàng',
            code: 'Số đơn hàng',
            supplier_name: 'NCC',
            created_at: 'Ngày đơn hàng',
            delivery_date: 'Ngày giao hàng',
            purchasing_manager: 'Người phụ trách',
            status: 'Trạng thái',
        },

        // pagination
        totalPages: null,
        currentPage: 1,
        total: 0,
        from: 0,
        to: 0,
        limit: 10,
        showAction: {
            view: false,
            edit: true,
            remove: true
        },
        selectedRow: [],

        //data
        filters: {
            code_name: null,
            status: null,
            created_at: null,
            page: 1,
            limit: 10
        },
        typeCreateOrder: ORDER_TYPE_CREATE_WITH_PLAN,
        data: {
            shopping_plan_company_id: null,
            supplier_id: null,
            name: null,
            type: ORDER_TYPE_CREATE_WITH_PLAN,
            purchasing_manager_id: null,
            delivery_date: null,
            delivery_location: null,
            contact_person: null,
            contract_info: null,
            payment_time: null,
            status: ORDER_STATUS_NEW,
            shipping_costs: null,
            other_costs: null,
            shopping_assets_order: [],
        },
        listShoppingPlanCompany: [],
        listSupplier: [],
        listUser: [],
        listAssetType: [],
        listOrganization: [],
        title: null,
        action: null,
        id: null,
        reason: null,
        listStatus: {
            [ORDER_STATUS_NEW]: 'Mới tạo',
            [ORDER_STATUS_TRANSIT]: 'Đang vận chuyển',
            [ORDER_STATUS_DELIVERED]: 'Đã bàn giao',
        },

        //methods
        async list(filters) {
            this.loading = true
            try {
                let filtersFormat = JSON.parse(JSON.stringify(filters))
                if (filtersFormat.status !== null) {
                    filtersFormat.status = [filtersFormat.status]
                }

                const response = await window.apiGetListOrder(filtersFormat)
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
                const response = await window.apiCreateOrder(this.data)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                toast.success('Tạo đơn hàng thành công')
                $('#modalInsert').modal('hide')
                this.list(this.filters)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

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

        async remove() {
            this.loading = true
            try {
                const ids = Array.isArray(this.id) ? this.id : [this.id]
                const response = await window.apiRemoveOrder(ids, this.reason)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                toast.success('Xóa đơn hàng thành công')
                $('#modalReason').modal('hide')
                this.dataTable.filter((item) => {
                    if (ids.includes(item.id)) {
                        item.status = ORDER_STATUS_CANCEL
                    }
                })
                this.selectedRow = []
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async handleShowModalUI(action, id = null) {
            this.loading = true
            try {
                this.resetData()
                this.action = action
                if (action === 'create') {
                    this.data.type = this.typeCreateOrder
                    this.title = 'Tạo mới'
                    $('#modalInsert').modal('show')
                } else {
                    this.title = action === 'view' ? 'Chi tiết' : 'Cập nhật'
                    this.id = id
                    await this.findOrder(id)
                    $('#modalUpdate').modal('show')
                }
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListShoppingPlanCompany() {
            this.loading = true
            try {
                const response = await window.apiGetShoppingPlanCompany(
                    {status: STATUS_SHOPPING_PLAN_COMPANY_COMPLETE, type: TYPE_SHOPPING_PLAN_COMPANY_WEEK}
                )
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.listShoppingPlanCompany = response.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListSupplier() {
            this.loading = true
            try {
                const response = await window.apiGetSupplier({})
                if (!response.success) {
                    toast.error(response.message)
                    return
                }

                this.listSupplier = response.data.data.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getSupplierOfShoppingPlanWeek(id) {
            this.loading = true
            try {
                const response = await window.apiGetSupplierOfShoppingPlanWeek(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.listSupplier = response.data.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListUser(){
            this.loading = true
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
                this.loading = false
            }
        },

        async findOrder(id){
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

        async getShoppingAssets(){
            this.loading = true
            try {
                let response = await window.apiGetListShoppingAsset({
                    shopping_plan_company_id: this.data.shopping_plan_company_id,
                    supplier_id: this.data.supplier_id,
                    status: [SHOPPING_ASSET_STATUS_ACCOUNTANT_APPROVAL, SHOPPING_ASSET_STATUS_GENERAL_APPROVAL]
                })
                if (response.success) {
                    this.data.shopping_assets_order = response.data.data
                    this.data.shopping_assets_order.filter((item) => {
                        item.code = 'MH' + item.id
                    })
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getShoppingAssetOrder() {
            this.loading = true
            try {
                let response = await window.apiGetShoppingAssetOrder({
                    order_id: [this.id]
                })

                if (response.success) {
                    this.data.shopping_assets_order = response.data
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        watch() {
            this.$watch('data.type', (value) => {
                if (this.action === 'create' && value !== null) {
                    if (+value === ORDER_TYPE_CREATE_WITH_PLAN && this.listShoppingPlanCompany.length < 1) {
                        this.getListShoppingPlanCompany()
                    } else {
                        this.listSupplier = []
                        this.getListSupplier();
                    }
                }
            });

            this.$watch('data.shopping_plan_company_id', (value) => {
                if (this.action === 'create' && value !== null && this.data.type === ORDER_TYPE_CREATE_WITH_PLAN) {
                    console.log(11111111)
                    this.getSupplierOfShoppingPlanWeek(value);
                    this.data.supplier_id = null;
                }
            });

            this.$watch('data.supplier_id', (value) => {
                if (value !== null) {
                    if (this.action === 'create' && +this.data.type === ORDER_TYPE_CREATE_WITH_PLAN) {
                        this.getShoppingAssets()
                    } else if (this.action !== 'create') {
                        this.getShoppingAssetOrder()
                    }
                }
            });
        },

        watchFilters() {
            this.$watch('filters.created_at', (value) => {
                if (value !== null) {
                    this.list(this.filters)
                }
            });

            this.$watch('filters.status', (value) => {
                if (value === '#') {
                    this.filters.status = null
                    this.list(this.filters)
                } else if (value !== null) {
                    this.list(this.filters)
                }
            });
        },

        confirmRemove(multiple = false, id = null) {
            if (multiple) {
                this.id = []
                this.selectedRow.filter((value, key) => {
                    if (value) {
                        this.id.push(+key)
                    }
                })
            } else {
                this.id = id
            }
            $("#confirmRemove").modal('show');
            this.reason = null
        },

        addRows() {
            this.data.shopping_assets_order.push({
                code:  window.generateShortCode(),
                name: null,
                vat_rate: null,
                price: null,
                asset_type_id: null,
                description: null,
                organization_id: null,
            })
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
                shopping_plan_company_id: null,
                supplier_id: null,
                name: null,
                type: null,
                purchasing_manager_id: null,
                delivery_date: null,
                delivery_location: null,
                contact_person: null,
                contract_info: null,
                payment_time: null,
                status: ORDER_STATUS_NEW,
                shipping_costs: null,
                other_costs: null,
                shopping_assets_order: [],
            }
        },

        reloadPage() {
            this.resetFilters()
            this.list(this.filters)
        },

        resetFilters() {
            this.filters = {
                name: null,
                page: 1,
                limit: 10
            }
        },
    }));
});
