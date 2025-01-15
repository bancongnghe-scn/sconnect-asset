document.addEventListener('alpine:init', () => {
    Alpine.data('need_maintain', () => ({
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
            next_maintain_start: null,
            next_maintain_end: null,
            location: null,
            status: null,
            page: 1,
            limit: 10
        },
        timeCalendar: null,
        dataCalendar: [],

        async list(filters) {
            this.loading = true
            try {
                const response = await window.apiGetAssetNeedMaintain(filters)
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
                this.$dispatch('total-need-maintain', data.total ?? 0)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getAssetNeedMaintainWithMonth() {
            this.loading = true
            try {
                if (this.timeCalendar === null) {
                    const now = new Date();
                    const year = now.getFullYear();
                    const month = (now.getMonth() + 1).toString().padStart(2, '0');  // thêm 0 nếu tháng < 10
                    this.timeCalendar = `${month}/${year}`;
                }
                const response = await window.apiGetAssetNeedMaintainWithMonth(this.timeCalendar)
                if (!response.success) {
                    return toast.error(response.message)
                }

                this.dataCalendar = response.data.data

            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async handleShowModalCalendar() {
            await this.getAssetNeedMaintainWithMonth()
            $('#modalCalendar').modal('show')
        },

        watchFilters() {
            this.$watch('filters.next_maintain_start', (value) => {
                if (value !== null && this.filters.next_maintain_end) {
                    this.list(this.filters)
                }
            })

            this.$watch('filters.next_maintain_end', (value) => {
                if (this.filters.next_maintain_start !== null && value !== null) {
                    this.list(this.filters)
                }
            })

            this.$watch('filters.location', (value) => {
                if (value !== null) {
                    this.list(this.filters)
                }
            })

            this.$watch('filters.status', (value) => {
                if (value !== null) {
                    this.list(this.filters)
                }
            })


            this.$watch('timeCalendar', (value) => {
                if (value !== null) {
                    this.getAssetNeedMaintainWithMonth(value)
                }
            })
        },

        reloadPage() {
            this.filters = {
                name_code: null,
                next_maintain_start: null,
                next_maintain_end: null,
                location: null,
                status: null,
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
