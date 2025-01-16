document.addEventListener('alpine:init', () => {
    Alpine.data('maintaining', () => ({
        init() {
            this.list(this.filters)
            this.watchFilters()
        },

        //table
        dataTable: [],

        //pagination
        totalPages: null,
        currentPage: 1,
        total: 0,
        from: 0,
        to: 0,
        limit: 10,

        filters: {
            name_code: null,
            start_date_maintain: null,
            complete_date_maintain: null,
            location: null,
            page: 1,
            limit: 10
        },

        async list(filters) {
            this.loading = true
            try {
                const response = await window.apiGetAssetMaintaining(filters)
                if (!response.success) {
                    return toast.error(response.message)
                }

                const data = response.data.data
                this.dataTable = data.data
                this.totalPages = data.last_page
                this.currentPage = data.current_page
                this.total = data.total ?? 0
                this.from = data.from ?? 0
                this.to = data.to ?? 0
                this.$dispatch('total-maintain', data.total ?? 0)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        watchFilters() {

        },

        reloadPage() {
            this.filters = {
                name_code: null,
                start_date_maintain: null,
                complete_date_maintain: null,
                location: null,
                page: 1,
                limit: 10
            }
            this.list(this.filters)
        },

        changePage(page) {
            this.filters.page = page
            this.list(this.filters)
        },

        changeLimit() {
            this.filters.limit = this.limit
            this.list(this.filters)
        },
    }));
});
