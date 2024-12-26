document.addEventListener('alpine:init', () => {
    Alpine.data('order', () => ({
        async init() {
            this.list(this.filters)
            this.getListUser()
            this.getListShoppingPlanCompany()
            this.watch()
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
        title: null,
        action: null,
        id: null,


        //methods
        async list(filters) {
            this.loading = true
            try {
                const response = await window.apiGetListOrder(filters)
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
                if (action === 'create') {
                    this.data.type = this.typeCreateOrder
                    this.title = 'Tạo mới'
                    this.action = action
                } else if (action === 'view') {
                    this.title = 'Chi tiết'
                    this.action = action
                    this.id = id
                    await this.findOrder(id)
                }
                $('#modalUI').modal('show')
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
                let response = {}
                if (this.action === 'create') {
                     response = await window.apiGetListShoppingAsset({
                        shopping_plan_company_id: this.data.shopping_plan_company_id,
                        supplier_id: this.data.supplier_id,
                        status: [SHOPPING_ASSET_STATUS_ACCOUNTANT_APPROVAL, SHOPPING_ASSET_STATUS_GENERAL_APPROVAL]
                    })
                } else {
                     response = await window.apiGetShoppingAssetOrder({
                        order_ids: [this.id]
                    })
                }
                if (response.success) {
                    this.data.shopping_assets_order = this.action === 'create' ? response.data.data : response.data
                    if (this.action === 'create') {
                        this.data.shopping_assets_order.filter((item) => {
                            window.generateShortCode().then(code => {
                                item.code = code + item.id
                            })
                        })
                    }
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
                if (value !== null) {
                    if (+value === ORDER_TYPE_CREATE_WITH_NOT_PLAN) {
                        this.getListSupplier();
                    } else {
                        this.listSupplier = [];
                    }
                }
            });

            this.$watch('data.shopping_plan_company_id', (value) => {
                if (value !== null && +this.data.type === ORDER_TYPE_CREATE_WITH_PLAN) {
                    this.getSupplierOfShoppingPlanWeek(value);
                    this.data.supplier_id = null;
                }
            });

            this.$watch('data.supplier_id', (value) => {
                if (value !== null && this.data.shopping_plan_company_id !== null) {
                    this.getShoppingAssets();
                }
            });
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
