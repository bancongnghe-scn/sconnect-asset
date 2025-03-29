document.addEventListener('alpine:init', () => {
    Alpine.data('shopping_arise_company_list', () => ({
        init() {
            this.getListShoppingArise(this.filters)
            this.watchFilters()
            this.setConfigTable()
        },

        //pagination
        dataTable: [],
        totalPages: null,
        currentPage: 1,
        from: 0,
        to: 0,
        total: 0,
        limit: 10,

        filters: {
            name: null,
            start_time: null,
            end_time: null,
            status: null,
            page: 1,
            limit: 10,
            type: GET_SHOPPING_ARISE_TYPE_COMPANY
        },
        selectedRow: [],
        list_asset_type: [],
        list_job: [],
        data: {
            name: null,
            assets: []
        },

        async getListShoppingArise(filters) {
            this.loading = true
            try {
                const response = await window.apiGetListShoppingArise(filters)
                if (response.success) {
                    const data = response.data
                    this.dataTable = data.data.data || []
                    this.totalPages = data.data.last_page
                    this.currentPage = data.data.current_page
                    this.from = data.data.from ?? 0
                    this.to = data.data.to ?? 0
                    this.total = data.data.total ?? 0
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

        watchFilters() {
            this.$watch('filters.start_time', (value) => {
                if (value && this.filters.end_time) {
                    this.getListShoppingArise(this.filters)
                }
            });

            this.$watch('filters.end_time', (value) => {
                if (value && this.filters.start_time) {
                    this.getListShoppingArise(this.filters)
                }
            });

            this.$watch('filters.status', (value) => {
                if (value) {
                    this.getListShoppingArise(this.filters)
                }
            });
        },

        setConfigTable() {
            this.configButtonsTable = {
                condition: (status) =>
                    (
                        [
                            STATUS_SHOPPING_ARISE_PENDING_PROCESSING,
                            STATUS_SHOPPING_ARISE_HR_PROCESSING,
                            STATUS_SHOPPING_ARISE_HR_SYNTHETIC,
                            STATUS_SHOPPING_ARISE_PENDING_MANAGER_HR,
                            STATUS_SHOPPING_ARISE_PENDING_ACCOUNTANT,
                            STATUS_SHOPPING_ARISE_PENDING_MANAGER
                        ].includes(status)
                        && this.permission.includes('shopping_plan_company.week.handle_shopping')
                    )
                    ||
                    (
                        status === STATUS_SHOPPING_ARISE_PENDING_MANAGER_HR
                        && this.permission.includes('shopping_plan_company.week.hr_manager_approval')
                    )
                    ||
                    (
                        status === STATUS_SHOPPING_ARISE_PENDING_ACCOUNTANT
                        && this.permission.includes('shopping_plan_company.accounting_approval')
                    )
                    ||
                    (
                        status === STATUS_SHOPPING_ARISE_PENDING_MANAGER
                        && this.permission.includes('shopping_plan_company.general_approval')
                    )
            }
        },

        reloadPage() {
            this.filters = {
                name: null,
                start_time: null,
                end_time: null,
                status: null,
                type: GET_SHOPPING_ARISE_TYPE_COMPANY,
                page: this.page,
                limit: this.limit,
            }
            this.getListShoppingArise(this.filters)
        },

        changePage(page) {
            this.filters.page = page
            this.getListShoppingArise(this.filters)
        },

        changeLimit(limit) {
            this.filters.limit = limit
            this.getListShoppingArise(this.filters)
        },
    }))
})
