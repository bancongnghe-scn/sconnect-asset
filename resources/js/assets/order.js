document.addEventListener('alpine:init', () => {
    Alpine.data('order', () => ({
        init() {
        },

        //dataTable
        dataTable: [],
        columns: {
            code: 'Mã đơn hàng',

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
            code: null,
            status: null,
            created_at: null,
            page: 1,
            limit: 10
        },
        data: {
            name: null,
            description: null,
        },


        //methods
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
                name: null,
                description: null,
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
