document.addEventListener('alpine:init', () => {
    Alpine.data('order', () => ({
        async init() {
            this.getListShoppingPlanCompany()
            this.getListSupplier(64)
            this.list(this.filters)
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

        async handleShowModalUI(action, id = null) {
            this.loading = true
            try {
                if (action === 'create') {
                    this.resetData()
                    this.title = 'Tạo mới'
                    $('#modalUI').modal('show')
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

        async getListSupplier(id) {
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
