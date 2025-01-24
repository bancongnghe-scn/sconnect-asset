document.addEventListener('alpine:init', () => {
    Alpine.data('plan_inventory', () => ({
        init() {
            this.list({page: 1, limit: 10})
            this.watchFilters()
        },

        //dataTable
        dataTable: [],

        //pagination
        totalPages: null,
        currentPage: 1,
        total: 0,
        from: 0,
        to: 0,
        limit: 10,
        selectedRow: [],

        //data
        filters: {
            name: null,
            start_time: null,
            end_time: null,
            status: null,
            from : null,
            limit: 10,
            page: 1
        },
        data: [],
        title: null,
        action: null,
        id: null,

        //methods
        async list(filters){
            this.loading = true
            const response = await window.apiGetPlanInventory(filters)
            if (response.success) {
                const data = response.data
                this.dataTable = data.data.data
                this.totalPages = data.data.last_page
                this.currentPage = data.data.current_page
                this.total = data.data.total ?? 0
                this.from = data.data.from ?? 0
                this.to = data.data.to ?? 0
            } else {
                toast.error(response.message)
            }
            this.loading = false
        },

        async create() {
            this.loading = true
            const response = await window.apiCreateAppendix(this.data)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            toast.success('Tạo phụ lục hợp đồng thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            this.reloadPage()
            this.loading = false
        },

        async edit() {
            this.loading = true
            const response = await window.apiUpdateAppendix(this.data, this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }

            toast.success('Cập nhập phụ lục hợp đồng thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            await this.list(this.filters)
            this.loading = false
        },

        async remove() {
            this.loading = true
            const response = await window.apiRemoveAppendix(this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            $("#"+this.idModalConfirmDelete).modal('hide')
            await this.list(this.filters)
            toast.success('Xóa phụ lục hợp đồng thành công !')
            this.loading = false
        },

        async handleShowModalUI(action, id = null) {
            this.loading = true
            this.action = action
            if (action === 'create') {
                this.title = 'Thêm mới'
                this.resetData()
            } else {
                this.title = 'Cập nhật'
                this.id = id
                const response = await window.apiShowAppendix(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.data = this.formatDateAppendix(response.data.data)
            }

            $('#'+this.idModalUI).modal('show');
            this.loading = false
        },

        watchFilters() {
            this.$watch('filters.start_time', (value) => {
                if (value !== null && this.filters.end_time !== null) {
                    this.list(this.filters)
                }
            })

            this.$watch('filters.end_time', (value) => {
                if (this.filters.start_time !== null && value !== null) {
                    this.list(this.filters)
                }
            })

            this.$watch('filters.status', (value) => {
                if (value !== null) {
                    this.list(this.filters)
                }
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
                contract_id: null,
                code: null,
                name: null,
                signing_date: null,
                from: null,
                to: null,
                user_ids: [],
                description: null,
                files: [],
            }
        },

        reloadPage() {
            this.filters = {
                name: null,
                start_time: null,
                end_time: null,
                status: null,
                from : null,
                limit: 10,
                page: 1
            }

            this.list(this.filters)
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },
    }));
});
